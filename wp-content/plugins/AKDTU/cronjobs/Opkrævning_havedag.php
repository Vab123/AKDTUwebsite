<?php

function send_opkrævning_havedag($debug = false) {

	if (HAVEDAG_TO != '' || HAVEDAG_WARNING_TO != '' || $debug) {
		global $wpdb;

		$send_mail_days_before = HAVEDAG_DAYS;
		$send_warning_mail_days_before = HAVEDAG_WARNING_DAYS;

		$price = 750;
		$payment_info = "";
		$payment_info_warning = "";

		$month = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$month->setPattern('MMMM');
		$monthnum = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$monthnum->setPattern('MM');
		$year = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$year->setPattern('YYYY');

		$scope = ($debug ? 'future' : 'past');
		$search_limit = 20;
		$offset = 0;
		$order = ($debug ? 'ASC' : 'DESC');
		$owner = false;
	
		$events = array_filter(EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0)), function ($event) {
			return pll_get_post_language($event->post_id, "slug") == "da";
		});
		
		if ($debug && count($events) == 0) {
			// Debug is on, but no future events was found. Go back to past events
			$scope = 'past';
			$order = 'DESC';
	
			$events = array_filter(EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0)), function ($event) {
				return pll_get_post_language($event->post_id, "slug") == "da";
			});
		}
	
		$actual_limit = 1;

		$send_email_deadline = new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen'));
		$send_email_deadline->modify('+7 day');

		$confirmation_email_deadline = new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen'));
		$confirmation_email_deadline->modify('+5 day');
	
		if (count($events) > 0) {
			$translations = pll_get_post_translations($EM_Event->post_id);
			$tickets = array();
			foreach ($translations as $translation) {
				foreach (em_get_event($translation, 'post_id')->get_bookings()->get_tickets()->tickets as $translated_ticket) {
					$tickets[$translated_ticket->ticket_id] = $translated_ticket;
				}
			}

			$bookings = array();
			foreach ($tickets as $translated_ticket) {
				foreach ($translated_ticket->get_bookings()->bookings as $booking) {
					if ($booking->booking_status == 1) {
						array_push($bookings, $booking);
					}
				}
			}

			$status = array();
			foreach (all_apartments() as $apartment) {
				$status[$apartment] = false;
			}

			$res = $wpdb->get_col('SELECT showed_up FROM wp_em_tilmeldinger WHERE event_id = ' . $events[0]->event_id);
			$res = array_map(function ($a) {
				return json_decode($a);
			}, $res);
			foreach ($res as $arr) {
				foreach ($arr as $user_id => $stat) {
					if (is_apartment_from_id($user_id)) {
						$status[apartment_number_from_id($user_id)] = $stat || $status[apartment_number_from_id($user_id)];
					}
				}
			}

			$latest_signup_date = em_get_event($bookings[0]->event_id, 'event_id')->rsvp_date;

			$moved_users = all_moved_after_apartment_numbers($latest_signup_date);

			$users_that_should_pay = array_filter(all_apartments(), function($apartment) use($status) { return !$status[$apartment]; });

			$it = 1;
			foreach ($users_that_should_pay as $apartment) {
				$replaces = array(
					'#APT' => padded_apartment_number_from_apartment_number($apartment) . (in_array($apartment, $moved_users) ? ' (Tidligere beboer)' : '' ),
					'#PRICE' => $price,
					'#BOARDMEMBER' => (is_boardmember_from_apartment_number($apartment) ? HAVEDAG_BOARDMEMBER : '')
				);

				$payment_info .= str_replace(array_keys($replaces), $replaces, nl2br(HAVEDAG_FORMAT));
				
				$replaces_warning = array(
					'#APT' => padded_apartment_number_from_apartment_number($apartment) . (in_array($apartment, $moved_users) ? ' (Tidligere beboer)' : '' ),
					'#PRICE' => $price,
					'#BOARDMEMBER' => (is_boardmember_from_apartment_number($apartment) ? HAVEDAG_WARNING_BOARDMEMBER : '')
				);

				$payment_info_warning .= str_replace(array_keys($replaces_warning), $replaces_warning, nl2br(HAVEDAG_WARNING_FORMAT));

				if ($it < count($users_that_should_pay)) {
					$payment_info .= '<br>';
					$payment_info_warning .= '<br>';
				}
				$it++;
			}
			
			$now = new DateTime;
			$ago = new DateTime($events[0]->event_end_date . " 00:00:00", new DateTimeZone('Europe/Copenhagen'));
			$diff = $now->diff($ago);

			$subject_replaces = array(
				'#SEASON' => ((new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
				'#YEAR' => $year->format(new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))
			);
	
			$content_replaces = array(
				'#PAYMENT_INFO' => $payment_info,
				'#SEASON' => ((new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
				'#YEAR' => $year->format(new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))
			);
	
			if (($send_mail_days_before >= 0 && $diff->days == $send_mail_days_before) || $debug) {
				if ($debug) {
					if ($send_mail_days_before >= 0) {
						echo '<h3>Opkrævningsmail - Sendes automatisk ' . $ago->modify($send_mail_days_before . " days")->format("Y-m-d") . '</h3>';
					} else {
						echo '<h3>Opkrævningsmail - Sendes IKKE automatisk, grundet afsendelsestidspunkt</h3>';
					}
				}
				send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'HAVEDAG');
			}
	
			$subject_replaces_warning = array(
				'#SEASON' => ((new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
				'#YEAR' => $year->format(new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
				'#DAYS' => $send_warning_mail_days_before,
			);
	
			$content_replaces_warning = array(
				'#PAYMENT_INFO' => $payment_info_warning,
				'#SEASON' => ((new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen')))->format('m') > 6 ? "efterår" : "forår"),
				'#YEAR' => $year->format(new DateTime($events[0]->event_end_date . " " . $events[0]->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
				'#DAYS' => $send_warning_mail_days_before,
			);
			
			if (($send_mail_days_before >= 0 && $send_warning_mail_days_before >= 0 && $diff->days == $send_mail_days_before - $send_warning_mail_days_before) || $debug) {
				if ($debug) {
					echo '<hr>';
					if ($send_mail_days_before >= 0 && $send_warning_mail_days_before >= 0) {
						echo '<h3>Varselsmail - Sendes automatisk ' . $ago->modify(( - $send_warning_mail_days_before) . " days")->format("Y-m-d") . '</h3>';
					} else {
						echo '<h3>Varselsmail - Sendes IKKE automatisk, grundet afsendelsestidspunkt</h3>';
					}
				}
				send_AKDTU_email($debug, $subject_replaces_warning, $content_replaces_warning, 'HAVEDAG_WARNING');
			}
			
		} else {
			echo "Ingen havedag blev fundet. DETTE ER EN FEJL.";
		}

	}
}
