<?php

/**
 * @file Cron-job responsible for sending billing information for residents not showing up to garden days
 */

/**
 * Cron-job responsible for sending billing information for residents not showing up to garden days
 * 
 * @param bool $debug Flag, for whether the email should actually be sent (false), or if this is a test run to show sample results (true)
 */
function send_opkrævning_havedag($debug = false) {
	# Check if an email should be sent
	if (HAVEDAG_TO != '' || HAVEDAG_WARNING_TO != '' || $debug) {
		global $wpdb;

		# Price for not showing up to a garden day
		$price = 750;

		# Prepare strings for payment info
		$payment_info = "";
		$payment_info_warning = "";

		# Date formatters
		$month = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$month->setPattern('MMMM');
		$monthnum = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$monthnum->setPattern('MM');
		$year = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$year->setPattern('YYYY');

		# Settings for lookup for garden days
		$scope = ($debug ? 'future' : 'past');
		$search_limit = 20;
		$offset = 0;
		$order = ($debug ? 'ASC' : 'DESC');
		$owner = false;
	
		# Get past or future garden days
		$events = array_filter(EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0)), function ($event) {
			return pll_get_post_language($event->post_id, "slug") == "da";
		});
		
		# Check if this is a test-run, and no future garden days were found. Revert to using the past garden day
		if ($debug && count($events) == 0) {
			# Debug is on, but no future events was found. Go back to past events
			$scope = 'past';
			$order = 'DESC';
	
			# Get past garden days
			$events = array_filter(EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0)), function ($event) {
				return pll_get_post_language($event->post_id, "slug") == "da";
			});
		}
	
		# Check if a garden day was found
		if (count($events) > 0) {
			# Get all translations of the event
			$translations = pll_get_post_translations($events[0]->post_id);

			# Prepare array for all tickets for garden days
			$tickets = array();

			# Go through all translations
			foreach ($translations as $translation) {
				# Store all tickets on the translations
				foreach (em_get_event($translation, 'post_id')->get_bookings()->get_tickets()->tickets as $translated_ticket) {
					$tickets[$translated_ticket->ticket_id] = $translated_ticket;
				}
			}

			# Prepare array for all bookings for garden days
			$bookings = array();

			# Go through all tickets
			foreach ($tickets as $translated_ticket) {
				# Store all bookings on the garden days
				foreach ($translated_ticket->get_bookings()->bookings as $booking) {
					if ($booking->booking_status == 1) {
						array_push($bookings, $booking);
					}
				}
			}

			# Prepare array for the status of apartments
			$status = array();

			# Initially set all apartments as not having showed up
			foreach (all_apartments() as $apartment) {
				$status[$apartment] = false;
			}

			# Get array of arrays of signups to each garden day
			$res = $wpdb->get_col('SELECT showed_up FROM wp_em_tilmeldinger WHERE event_id = ' . $events[0]->event_id);
			$res = array_map(function ($a) {
				return json_decode($a);
			}, $res);

			# Go through each possible garden day
			foreach ($res as $arr) {
				# Go through the status of each user signed up to that garden day
				foreach ($arr as $user_id => $stat) {
					# If it is an apartment user
					if (is_apartment_from_id($user_id)) { 
						# Store if the apartment has showed up or not
						$status[apartment_number_from_id($user_id)] = $stat || $status[apartment_number_from_id($user_id)];
					}
				}
			}

			# Get the date for the last allowed signup date
			$latest_signup_date = em_get_event($bookings[0]->event_id, 'event_id')->rsvp_date;

			# Get all apartments where the resident has moved after the last allowed signup date
			$moved_users = all_moved_after_apartment_numbers($latest_signup_date);

			# Get a list of all users that did not show up to the garden day
			$users_that_should_pay = array_filter(all_apartments(), function($apartment) use($status) { return !$status[$apartment]; });

			# User counter
			$it = 1;

			# Go through all users that should pay
			foreach ($users_that_should_pay as $apartment) {
				# Info format for the real email
				$replaces = array(
					'#APT' => padded_apartment_number_from_apartment_number($apartment) . (in_array($apartment, $moved_users) ? ' (Tidligere beboer)' : '' ),
					'#PRICE' => $price,
					'#BOARDMEMBER' => (is_boardmember_from_apartment_number($apartment) ? HAVEDAG_BOARDMEMBER : '')
				);

				# Add info to the real email
				$payment_info .= str_replace(array_keys($replaces), $replaces, nl2br(HAVEDAG_FORMAT));
				
				# Info format for the warning email
				$replaces_warning = array(
					'#APT' => padded_apartment_number_from_apartment_number($apartment) . (in_array($apartment, $moved_users) ? ' (Tidligere beboer)' : '' ),
					'#PRICE' => $price,
					'#BOARDMEMBER' => (is_boardmember_from_apartment_number($apartment) ? HAVEDAG_WARNING_BOARDMEMBER : '')
				);

				# Add info to the warning email
				$payment_info_warning .= str_replace(array_keys($replaces_warning), $replaces_warning, nl2br(HAVEDAG_WARNING_FORMAT));

				# Add spaces if this is not the last user that did not show up
				if ($it < count($users_that_should_pay)) {
					$payment_info .= '<br>';
					$payment_info_warning .= '<br>';
				}

				# Increment counter
				$it++;
			}
			
			# Current time
			$now = new DateTime;

			# End-time of the last garden day
			$ago = new DateTime($events[0]->event_end_date . " 00:00:00", new DateTimeZone('Europe/Copenhagen'));

			# Time difference between now and the end-time of the last garden day
			$diff = $now->diff($ago);
	
			# Check if the real email should be sent or echoed
			if ((HAVEDAG_DAYS >= 0 && $diff->days == HAVEDAG_DAYS) || $debug) {
				# Replacements for real email subject
				$subject_replaces = array(
					'#SEASON' => ((new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
					'#YEAR' => $year->format(new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))
				);
		
				# Replacements for real email content
				$content_replaces = array(
					'#PAYMENT_INFO' => $payment_info,
					'#SEASON' => ((new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
					'#YEAR' => $year->format(new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))
				);

				# Add headline if real email should be echoed
				if ($debug) {
					# Check if real email will be sent automatically or not
					if (HAVEDAG_DAYS >= 0) {
						echo '<h3>Opkrævningsmail - Sendes automatisk ' . $ago->modify(HAVEDAG_DAYS . " days")->format("Y-m-d") . '</h3>';
					} else {
						echo '<h3>Opkrævningsmail - Sendes IKKE automatisk, grundet afsendelsestidspunkt</h3>';
					}
				}

				# Send or echo mail
				send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'HAVEDAG');

				# Remove menu item for the event if this is a real run
				if (!$debug) {
					# Names of menus to remove items from
					$menu_names = array(
						"da" => "Dansk - logget ind",
						"en" => "Engelsk - logget ind"
					);

					# Remove all events from menus
					foreach (pll_get_post_translations($events[0]->post_id) as $language_code => $post_id) {
						# Get the relevant menu
						$menu = wp_get_nav_menu_object( $menu_names[$language_code] );

						# Check if a menu was found
						if (is_nav_menu($menu)) {
							# Get all items in the menu pointing to this event
							$menu_items = array_filter(
								wp_get_nav_menu_items( $menu->term_id ) ,
								function($menu_item) use ($post_id) {
									return $menu_item->object_id == $post_id;
								}
							);
			
							# Remove the menu item from the menu
							foreach ($menu_items as $menu_item) {
								wp_delete_post($menu_item->ID);	# wp_delete_post removes the item from the menu
							}
						}
					}
				}
			}
			
			# Check if the warning email should be sent or echoed
			if ((HAVEDAG_DAYS >= 0 && HAVEDAG_WARNING_DAYS >= 0 && $diff->days == HAVEDAG_DAYS - HAVEDAG_WARNING_DAYS) || $debug) {
				# Replacements for warning email subject
				$subject_replaces_warning = array(
					'#SEASON' => ((new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
					'#YEAR' => $year->format(new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
					'#DAYS' => HAVEDAG_WARNING_DAYS,
				);
		
				# Replacements for warning email content
				$content_replaces_warning = array(
					'#PAYMENT_INFO' => $payment_info_warning,
					'#SEASON' => ((new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
					'#YEAR' => $year->format(new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
					'#DAYS' => HAVEDAG_WARNING_DAYS,
				);

				# Add headline if warning email should be echoed
				if ($debug) {
					echo '<hr>';
					# Check if warning email will be sent automatically or not
					if (HAVEDAG_DAYS >= 0 && HAVEDAG_WARNING_DAYS >= 0) {
						echo '<h3>Varselsmail - Sendes automatisk ' . $ago->modify(( - HAVEDAG_WARNING_DAYS) . " days")->format("Y-m-d") . '</h3>';
					} else {
						echo '<h3>Varselsmail - Sendes IKKE automatisk, grundet afsendelsestidspunkt</h3>';
					}
				}

				# Send or echo mail
				send_AKDTU_email($debug, $subject_replaces_warning, $content_replaces_warning, 'HAVEDAG_WARNING');
			}
		} else {
			# Write error if there was not found any garden days. If this is reached, there may be no garden days in the system
			echo "Ingen havedag blev fundet. DETTE ER EN FEJL.";
		}
	}
}

?>