<?php

function delete_previous_gardendays($user_id, $archive_user_id, $debug = false) {
	global $wpdb;

	$returnstring = '';

	$EM_Person = new EM_Person($user_id);
	$EM_Bookings = $EM_Person->get_bookings();
	$Bookings_count = count($EM_Bookings->bookings);

	$it = 1;
	if ($Bookings_count > 0) {
		$current_time = new DateTime("now", new DateTimeZone('Europe/Copenhagen'));
		$current_time = $current_time->format("Y-m-d H:i:s");

		foreach ($EM_Bookings as $EM_Booking) {
			$event_id = $EM_Booking->event_id;
			$EM_Event = em_get_event($event_id);

			$ticketid = array_keys($EM_Booking->get_tickets()->tickets)[0];
			$ticket_date = new DateTime($EM_Booking->get_tickets()->tickets[$ticketid]->__get('ticket_name'));

			// Only delete bookings where the event has already happened. Other bookings are kept
			if ($EM_Event->event_start_date . ' ' . $EM_Event->event_start_time < $current_time) {

				if ($EM_Booking->status <= 1) {
					// 0 => Pending
					// 1 => Approved
					// 2 => Rejected
					// 3 => Cancelled
					// 4 => Awaiting Online Payment
					// 5 => Awaiting payment
					//
					// Booking is Pending or Approved: Save information about event
					$replaces = array(
						'#NAME' => $EM_Event->event_name,

						'#DATE_YEAR' => $ticket_date->format('Y'),
						'#DATE_MONTH' => $ticket_date->format('m'),
						'#DATE_DAY' => $ticket_date->format('d'),
						'#DATE_HOUR' => $ticket_date->format('H'),
						'#DATE_MINUTE' => $ticket_date->format('i'),
						'#DATE_SECOND' => $ticket_date->format('s')
					);

					$returnstring .= str_replace(array_keys($replaces), $replaces, nl2br(FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS));

					if ($it < $Bookings_count) {
						$returnstring .= '<br><br>';
					}
				}

				if (!$debug) {
					// Move booking to archive user
					$sql = $wpdb->prepare("UPDATE " . EM_BOOKINGS_TABLE . " SET person_id=%d WHERE booking_id=%d", $archive_user_id, $EM_Booking->booking_id);
					$result = $wpdb->query($sql);
				}
				$it++;
			} else {
				$Bookings_count--;
			}
		}
	}
	if ($it == 1) {
		$returnstring = '(Ingen tilmeldinger til tidligere havedage blev fundet)';
	}

	return $returnstring;
}

function find_future_gardendays($user_id, $archive_user_id, $debug = false) {
	global $wpdb;

	$returnstring = '';

	$EM_Person = new EM_Person($user_id);
	$EM_Bookings = $EM_Person->get_bookings();
	$Bookings_count = count($EM_Bookings->bookings);

	$it = 1;

	if ($Bookings_count > 0) {
		$current_time = new DateTime("now", new DateTimeZone('Europe/Copenhagen'));
		$current_time = $current_time->format("Y-m-d H:i:s");

		foreach ($EM_Bookings as $EM_Booking) {
			$event_id = $EM_Booking->event_id;
			$EM_Event = em_get_event($event_id);

			$ticketid = array_keys($EM_Booking->get_tickets()->tickets)[0];
			$ticket_date = new DateTime($EM_Booking->get_tickets()->tickets[$ticketid]->__get('ticket_name'));

			// Only show bookings where the event has not yet happened.
			if ($EM_Event->event_start_date . ' ' . $EM_Event->event_start_time >= $current_time) {

				if ($EM_Booking->booking_status <= 1) {
					// 0 => Pending
					// 1 => Approved
					// 2 => Rejected
					// 3 => Cancelled
					// 4 => Awaiting Online Payment
					// 5 => Awaiting payment
					//
					// Booking is Pending or Approved: Do not delete

					// Save information about event
					$replaces = array(
						'#NAME' => $EM_Event->event_name,

						'#DATE_YEAR' => $ticket_date->format('Y'),
						'#DATE_MONTH' => $ticket_date->format('m'),
						'#DATE_DAY' => $ticket_date->format('d'),
						'#DATE_HOUR' => $ticket_date->format('H'),
						'#DATE_MINUTE' => $ticket_date->format('i'),
						'#DATE_SECOND' => $ticket_date->format('s')
					);

					$returnstring .= str_replace(array_keys($replaces), $replaces, nl2br(FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS));

					if ($it < $Bookings_count) {
						$returnstring .= '<br><br>';
					}

					$it++;
				} else {
					// Booking is neither Pending nor Approved: Delete

					if (!$debug) {
						// Move booking to archive user
						$sql = $wpdb->prepare("UPDATE " . EM_BOOKINGS_TABLE . " SET person_id=%d WHERE booking_id=%d", $archive_user_id, $EM_Booking->booking_id);
						$wpdb->query($sql);
					}

					$Bookings_count--;
				}
			} else {
				$Bookings_count--;
			}
		}
	}
	if ($it == 1) {
		$returnstring = '(Ingen tilmeldinger til fremtidige havedage blev fundet)';
	}

	return $returnstring;
}

function delete_rentals($user_id, $archive_user_id, $debug = false) {
	global $wpdb;
	$event_ids = $wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_owner = "' . $user_id . '" AND event_status >= 0');
	$events_count = count($event_ids);

	$EM_Events = EM_Events::get($event_ids);
	$Events_count = count($EM_Events);

	$returnstring = '';

	if ($Events_count > 0 && $events_count > 0) {
		$it = 1;
		foreach ($EM_Events as $EM_Event) {
			$start_date = new DateTime($EM_Event->event_start_date . " " . $EM_Event->event_start_time);
			$end_date = new DateTime($EM_Event->event_end_date . " " . $EM_Event->event_end_time);

			$replaces = array(
				// '#NAME'  => $EM_Event->event_name,
				'#NAME' => str_replace(array("#_RENTAL_BEFORE_APARTMENTNUM", "#_RENTAL_AFTER_APARTMENTNUM"), array(pll_translate_string('RENTAL_BEFORE_APARTMENTNUM', 'da_DK'), pll_translate_string('RENTAL_AFTER_APARTMENTNUM', 'da_DK')), $EM_Event->event_name),

				'#START_DATE_YEAR' => $start_date->format('Y'),
				'#START_DATE_MONTH' => $start_date->format('m'),
				'#START_DATE_DAY' => $start_date->format('d'),
				'#START_DATE_HOUR' => $start_date->format('H'),
				'#START_DATE_MINUTE' => $start_date->format('i'),
				'#START_DATE_SECOND' => $start_date->format('s'),

				'#END_DATE_YEAR' => $end_date->format('Y'),
				'#END_DATE_MONTH' => $end_date->format('m'),
				'#END_DATE_DAY' => $end_date->format('d'),
				'#END_DATE_HOUR' => $end_date->format('H'),
				'#END_DATE_MINUTE' => $end_date->format('i'),
				'#END_DATE_SECOND' => $end_date->format('s')
			);

			$returnstring .= str_replace(array_keys($replaces), $replaces, nl2br(FJERNBRUGERADGANG_FORMAT_RENTALS));

			if ($it < $Events_count) {
				$returnstring .= '<br><br>';
				$it++;
			}

			if (!$debug) {
				// Move the event to archive user
				$sql = $wpdb->prepare("UPDATE " . EM_EVENTS_TABLE . " SET event_owner=%d WHERE event_id=%d", $archive_user_id, $EM_Event->event_id);
				$wpdb->query($sql);

				// Move the corresponding post to archive user
				$post_updated = wp_update_post(
					array(
						'ID'          => $EM_Event->post_id,
						'post_author' => $archive_user_id,
					)
				);
			}
		}
	} else {
		$returnstring = '(Ingen udlejninger af fælleshuset blev fundet)';
	}

	return $returnstring;
}

function reset_user_info($user_id, $new_pass, $new_first_name, $new_last_name, $new_email, $apartment_num, $debug = false) {
	global $wpdb;

	if (!$debug) {
		// Update password
		wp_set_password($new_pass, $user_id);

		// Update email, first name, and last name
		$args = array(
			'ID'         => $user_id,
			'user_email' => $new_email,
			'first_name' => $new_first_name,
			'last_name' => $new_last_name
		);
		wp_update_user($args);
	}
}
