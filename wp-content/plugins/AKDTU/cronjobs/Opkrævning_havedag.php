<?php

function send_opkrævning_havedag($debug = false) {
	require_once WP_PLUGIN_DIR . '/AKDTU/definitions.php';

	if (HAVEDAG_TO != '' || HAVEDAG_WARNING_TO != '' || $debug) {
		require_once WP_PLUGIN_DIR . '/AKDTU/functions/send_mail.php';
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

		$scope = 'past';
		$search_limit = 20;
		$offset = 0;
		$order = 'DESC';
		$owner = false;
	
		$events = array_filter(EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0)), function ($event) {
			return pll_get_post_language($event->post_id, "slug") == "da";
		});
	
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

			$all_users = array();
			$status = array();
			for ($floor = 0; $floor < 3; $floor++) {
				for ($apartment = 1; $apartment < 25; $apartment++) {
					array_push($all_users, $floor * 100 + $apartment);
					$status[$floor * 100 + $apartment] = false;
				}
			}
			$res = $wpdb->get_col('SELECT showed_up FROM wp_em_tilmeldinger WHERE event_id = ' . $events[0]->event_id);
			$res = array_map(function ($a) {
				return json_decode($a);
			}, $res);
			foreach ($res as $arr) {
				foreach ($arr as $user_id => $stat) {
					$user_login = get_user_by('id', $user_id)->user_login;
					if (substr($user_login, 0, 4) == "lejl") {
						$status[ltrim(substr($user_login, 4, 3), "0")] = (isset($status[ltrim(substr($user_login, 4, 3), "0")]) ? ($stat || $status[ltrim(substr($user_login, 4, 3), "0")]) : $stat);
					}
				}
			}

			$latest_signup_date = em_get_event($bookings[0]->event_id, 'event_id')->rsvp_date;

			$query = $wpdb->prepare('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_membercreation WHERE allow_creation_date >= "' . $latest_signup_date . '" AND initial_reset = 1 ORDER BY allow_creation_date ASC, apartment_number ASC');
			$moved_users = $wpdb->get_col($query);

			$users_that_should_pay = array();
			$users_that_should_pay_archive = array();

			foreach ($all_users as $apartment) {
				if ($status[$apartment] == 1) {
					// Apartment showed up. Do nothing
				} else {
					array_push($users_that_should_pay, $apartment);
					array_push($users_that_should_pay_archive, (in_array($apartment, $moved_users) ? true : false));
				}
			}

			$it = 1;
			foreach ($users_that_should_pay as $index => $apartment) {
				$replaces = array(
					'#APT' => ($users_that_should_pay_archive[$index] ? $apartment . ' (Tidligere beboer)' : $apartment ),
					'#PRICE' => $price
				);

				$payment_info .= str_replace(array_keys($replaces), $replaces, nl2br(HAVEDAG_FORMAT));
				$payment_info_warning .= str_replace(array_keys($replaces), $replaces, nl2br(HAVEDAG_WARNING_FORMAT));

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
						echo '<h3>Varselsmail - Sendes automatisk ' . $ago->modify($send_mail_days_before . " days")->format("Y-m-d") . '</h3>';
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
