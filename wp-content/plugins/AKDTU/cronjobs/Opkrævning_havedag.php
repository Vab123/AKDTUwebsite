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

		# Date formatters
		$month = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$month->setPattern('MMMM');
		$monthnum = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$monthnum->setPattern('MM');
		$year = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$year->setPattern('YYYY');

		$gardenday = next_gardenday('da', 1);
		
		# Check if this is a test-run, and no future garden days were found. Revert to using the past garden day
		if ($debug && is_null($gardenday)) {
			# Debug is on, but no future events was found. Go back to past events
			$gardenday = previous_gardenday('da', 1);
		}
	
		# Check if a garden day was found
		if (!is_null($gardenday)) {
			$gardenday = $gardenday[0];

			# Get all translations of the event
			$translations = pll_get_post_translations($gardenday->post_id);

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
			$res = $wpdb->get_col('SELECT showed_up FROM wp_em_tilmeldinger WHERE event_id = ' . $gardenday->event_id);
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

			# Go through all users that should pay
			$payment_info = join('<br>', array_map(function($apartment) use($moved_users, $gardenday) {
				# Info format for the real email
				$replaces = array(
					'#APT' => padded_apartment_number_from_apartment_number($apartment) . (in_array($apartment, $moved_users) ? ' (Tidligere beboer)' : '' ),
					'#PRICE' => number_format(gardenday_price($apartment, $gardenday->event_id), 2, ",", "."),
					'#BOARDSTATUS' => (is_boardmember_from_apartment_number($apartment) ? HAVEDAG_BOARDMEMBER : '') . (is_board_deputy_from_apartment_number($apartment) ? HAVEDAG_BOARD_DEPUTY : '')
				);
				
				# Add info to the real email
				return str_replace(array_keys($replaces), $replaces, nl2br(HAVEDAG_FORMAT));
			}, $users_that_should_pay));
			
			# Current time
			$now = new DateTime;

			# End-time of the last garden day
			$ago = new DateTime($gardenday->event_end_date . " 00:00:00", new DateTimeZone('Europe/Copenhagen'));

			# Time difference between now and the end-time of the last garden day
			$diff = $now->diff($ago);

			# Replacements for real email subject
			$real_message_subject_replaces = array(
				'#SEASON' => ((new DateTime($gardenday->event_end_date . " " . $gardenday->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
				'#YEAR' => $year->format(new DateTime($gardenday->event_end_date . " " . $gardenday->event_end_time, new DateTimeZone('Europe/Copenhagen')))
			);

			# Replacements for real email content
			$real_message_content_replaces = array(
				'#PAYMENT_INFO' => $payment_info,
				'#SEASON' => ((new DateTime($gardenday->event_end_date . " " . $gardenday->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
				'#YEAR' => $year->format(new DateTime($gardenday->event_end_date . " " . $gardenday->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
				'#LASTSIGNUPDATE' => (new DateTime($latest_signup_date))->format('d-m-Y'),
			);

			# Replacements for warning email subject
			$warning_message_subject_replaces = array(
				'#SEASON' => ((new DateTime($gardenday->event_end_date . " " . $gardenday->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
				'#YEAR' => $year->format(new DateTime($gardenday->event_end_date . " " . $gardenday->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
				'#DAYS' => HAVEDAG_WARNING_DAYS,
			);
	
			# Replacements for warning email content
			$warning_message_content_replaces = array(
				'#SEASON' => ((new DateTime($gardenday->event_end_date . " " . $gardenday->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
				'#YEAR' => $year->format(new DateTime($gardenday->event_end_date . " " . $gardenday->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
				'#DAYS' => HAVEDAG_WARNING_DAYS,
				'#REALMESSAGECONTENT' => AKDTU_email_content($real_message_content_replaces, 'HAVEDAG'),
			);


			# Check if the real email should be sent or echoed
			if ((HAVEDAG_DAYS >= 0 && $diff->days == HAVEDAG_DAYS) || $debug) {
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
				send_AKDTU_email($debug, $real_message_subject_replaces, $real_message_content_replaces, 'HAVEDAG');

				# Remove menu item for the event if this is a real run
				if (!$debug) {
					# Names of menus to remove items from
					$menu_names = array(
						"da" => "Dansk - logget ind",
						"en" => "Engelsk - logget ind"
					);

					# Remove all events from menus
					foreach (pll_get_post_translations($gardenday->post_id) as $language_code => $post_id) {
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
				send_AKDTU_email($debug, $warning_message_subject_replaces, $warning_message_content_replaces, 'HAVEDAG_WARNING');
			}
		} else {
			# Write error if there was not found any garden days. If this is reached, there may be no garden days in the system
			echo "Ingen havedag blev fundet. DETTE ER EN FEJL.";
		}
	}
}

?>