<?php

/**
 * @file Functionality related to the deletion of users when residents move out
 */

/**
 * Deletes signups to previous garden days, used when a user is reset because the resident moves out.
 * 
 * Info about the deleted signups is formatted and returned
 * 
 * @param int $user_id ID of the user to investigate
 * @param int $archive_user_id ID of the corresponding archive-user
 * @param bool $debug Flag, indicating if the function call is a real run, or just a test to output sample results
 * 
 * @return string Formatted string with info about the deleted signups, formatted according to FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS
 */
function delete_previous_gardendays($user_id, $archive_user_id, $debug = false) {
	global $wpdb;
	#
	# Glossary
	# Booking: Signup for a specific user to a gardenday
	# Ticket: Date for a specific garden day, to which a user can sign up
	# Event: Collection of all garden days for an entire season, with a ticket for each individual garden day, and potentially with bookings from many users
	#

	# Prepare string with info about deleted signups
	$returnstring = '';

	# Get EM-structs for the user, and all of their bookings
	$EM_Person = new EM_Person($user_id);
	$EM_Bookings = $EM_Person->get_bookings();
	$Bookings_count = count($EM_Bookings->bookings);

	# Counter for the amount of processed bookings
	$it = 1;

	# Check if the person has made any bookings
	if ($Bookings_count > 0) {
		# Current time, formatted as string
		$current_time = new DateTime("now", new DateTimeZone('Europe/Copenhagen'));
		$current_time = $current_time->format("Y-m-d H:i:s");

		# Go through all of their bookings/signups
		foreach ($EM_Bookings as $EM_Booking) {
			# Get the event, corresponding to the booking
			$event_id = $EM_Booking->event_id;
			$EM_Event = em_get_event($event_id);

			# Get all tickets 
			$ticketid = array_keys($EM_Booking->get_tickets()->tickets)[0];
			$ticket_date = new DateTime($EM_Booking->get_tickets()->tickets[$ticketid]->__get('ticket_name'));

			# Only delete bookings where the event has already happened. Other bookings are kept
			if ($EM_Event->event_start_date . ' ' . $EM_Event->event_start_time < $current_time) {
				if ($EM_Booking->status <= 1) {
					# Check if booking is pending or approved
					# 0 => Pending
					# 1 => Approved
					# 2 => Rejected
					# 3 => Cancelled
					# 4 => Awaiting Online Payment
					# 5 => Awaiting payment
					#
					# Booking is Pending or Approved: Save information about event
					
					# Save information about event
					$replaces = array(
						'#NAME' => $EM_Event->event_name,

						'#DATE_YEAR' => $ticket_date->format('Y'),
						'#DATE_MONTH' => $ticket_date->format('m'),
						'#DATE_DAY' => $ticket_date->format('d'),
						'#DATE_HOUR' => $ticket_date->format('H'),
						'#DATE_MINUTE' => $ticket_date->format('i'),
						'#DATE_SECOND' => $ticket_date->format('s')
					);

					# Append information to return string
					$returnstring .= str_replace(array_keys($replaces), $replaces, nl2br(FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS));

					# If this is not the last booking, add linebreaks to return string
					if ($it < $Bookings_count) {
						$returnstring .= '<br><br>';
					}
				}

				# Check if this is a real call, or only a check of dummy results
				if (!$debug) {
					# Real run. Move booking to archive user
					$sql = $wpdb->prepare("UPDATE " . EM_BOOKINGS_TABLE . " SET person_id=%d WHERE booking_id=%d", $archive_user_id, $EM_Booking->booking_id);
					$result = $wpdb->query($sql);
				}

				# Increment counter of the current booking
				$it++;
			} else {
				# Booking is in the future, and should not be deleted. Decrease total amount of bookings
				$Bookings_count--;
			}
		}
	}

	# Check if there were any bookings to delete
	if ($it == 1) {
		# Return string is information about the fact that no bookings was deleted
		$returnstring = '(Ingen tilmeldinger til tidligere havedage blev fundet)';
	}

	# Return formatted string
	return $returnstring;
}

/**
 * Notes signups to future garden days, used when a user is reset because the resident moves out.
 * 
 * Info about the signups is formatted and returned
 * 
 * @param int $user_id ID of the user to investigate
 * @param int $archive_user_id ID of the corresponding archive-user
 * @param bool $debug Flag, indicating if the function call is a real run, or just a test to output sample results
 * 
 * @return string Formatted string with info about the signups, formatted according to FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS
 */
function find_future_gardendays($user_id, $archive_user_id, $debug = false) {
	global $wpdb;
	#
	# Glossary
	# Booking: Signup for a specific user to a gardenday
	# Ticket: Date for a specific garden day, to which a user can sign up
	# Event: Collection of all garden days for an entire season, with a ticket for each individual garden day, and potentially with bookings from many users
	#

	# Prepare string with info about deleted signups
	$returnstring = '';

	# Get EM-structs for the user, and all of their bookings
	$EM_Person = new EM_Person($user_id);
	$EM_Bookings = $EM_Person->get_bookings();
	$Bookings_count = count($EM_Bookings->bookings);

	# Counter for the amount of processed bookings
	$it = 1;

	# Check if the person has made any bookings
	if ($Bookings_count > 0) {
		# Current time, formatted as string
		$current_time = new DateTime("now", new DateTimeZone('Europe/Copenhagen'));
		$current_time = $current_time->format("Y-m-d H:i:s");

		# Go through all of their bookings
		foreach ($EM_Bookings as $EM_Booking) {
			$event_id = $EM_Booking->event_id;
			$EM_Event = em_get_event($event_id);

			# Get all tickets 
			$ticketid = array_keys($EM_Booking->get_tickets()->tickets)[0];
			$ticket_date = new DateTime($EM_Booking->get_tickets()->tickets[$ticketid]->__get('ticket_name'));

			# Only show bookings where the event has not yet happened.
			if ($EM_Event->event_start_date . ' ' . $EM_Event->event_start_time >= $current_time) {

				if ($EM_Booking->booking_status <= 1) {
					# Check if booking is pending or approved
					# 0 => Pending
					# 1 => Approved
					# 2 => Rejected
					# 3 => Cancelled
					# 4 => Awaiting Online Payment
					# 5 => Awaiting payment
					#
					# Booking is Pending or Approved: Do not delete

					# Save information about event
					$replaces = array(
						'#NAME' => $EM_Event->event_name,

						'#DATE_YEAR' => $ticket_date->format('Y'),
						'#DATE_MONTH' => $ticket_date->format('m'),
						'#DATE_DAY' => $ticket_date->format('d'),
						'#DATE_HOUR' => $ticket_date->format('H'),
						'#DATE_MINUTE' => $ticket_date->format('i'),
						'#DATE_SECOND' => $ticket_date->format('s')
					);

					# Append information to return string
					$returnstring .= str_replace(array_keys($replaces), $replaces, nl2br(FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS));

					# If this is not the last booking, add linebreaks to return string
					if ($it < $Bookings_count) {
						$returnstring .= '<br><br>';
					}

					$it++;
				} else {
					# Booking is neither Pending nor Approved: Delete

					# Check if this is a real call, or only a check of dummy results
					if (!$debug) {
						# Real run. Move booking to archive user
						$sql = $wpdb->prepare("UPDATE " . EM_BOOKINGS_TABLE . " SET person_id=%d WHERE booking_id=%d", $archive_user_id, $EM_Booking->booking_id);
						$wpdb->query($sql);
					}

					# Booking is neither Pending nor Approved, and should not be moved. Decrease total amount of bookings
					$Bookings_count--;
				}
			} else {
				# Booking is not in the future, and should not be moved. Decrease total amount of bookings
				$Bookings_count--;
			}
		}
	}
	
	# Check if there were any bookings to delete
	if ($it == 1) {
		# Return string is information about the fact that no bookings was deleted
		$returnstring = '(Ingen tilmeldinger til fremtidige havedage blev fundet)';
	}

	# Return formatted string
	return $returnstring;
}

/**
 * Deletes rentals of the common house, used when a user is reset because the resident moves out.
 * 
 * Info about the rentals is formatted and returned
 * 
 * @param int $user_id ID of the user to investigate
 * @param int $archive_user_id ID of the corresponding archive-user
 * @param bool $debug Flag, indicating if the function call is a real run, or just a test to output sample results
 * 
 * @return string Formatted string with info about the rentals, formatted according to FJERNBRUGERADGANG_FORMAT_RENTALS
 */
function delete_rentals($user_id, $archive_user_id, $debug = false) {
	global $wpdb;

	# Prepare string with info about deleted signups
	$returnstring = '';

	# Get EM-structs for the user, and all of their rentals
	$event_ids = $wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_owner = "' . $user_id . '" AND event_status >= 0');
	$events_count = count($event_ids);
	$EM_Events = EM_Events::get($event_ids);
	$Events_count = count($EM_Events);

	# Check if the apartment has rented the common house, at some point
	if ($Events_count > 0 && $events_count > 0) {
		# Counter for the amount of processed rentals
		$it = 1;

		# Go through all of their rentals
		foreach ($EM_Events as $EM_Event) {
			# Get start- and finish-times of the event
			$start_date = new DateTime($EM_Event->event_start_date . " " . $EM_Event->event_start_time);
			$end_date = new DateTime($EM_Event->event_end_date . " " . $EM_Event->event_end_time);

			# Save information about the rental
			$replaces = array(
				# '#NAME'  => $EM_Event->event_name,
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

			# Add information about the rental to return string
			$returnstring .= str_replace(array_keys($replaces), $replaces, nl2br(FJERNBRUGERADGANG_FORMAT_RENTALS));

			# If this is not the last rental, add linebreaks to return string
			if ($it < $Events_count) {
				$returnstring .= '<br><br>';
				$it++;
			}

			# Check if this is a real call, or only a check of dummy results
			if (!$debug) {
				# Real run. Move the event to archive user
				$sql = $wpdb->prepare("UPDATE " . EM_EVENTS_TABLE . " SET event_owner=%d WHERE event_id=%d", $archive_user_id, $EM_Event->event_id);
				$wpdb->query($sql);

				# Move the corresponding post to archive user
				$post_updated = wp_update_post(
					array(
						'ID'          => $EM_Event->post_id,
						'post_author' => $archive_user_id,
					)
				);
			}
		}
	} else {
		# There were no rentals to delete. Return string is information about the fact that no rentals was deleted
		$returnstring = '(Ingen udlejninger af fælleshuset blev fundet)';
	}

	return $returnstring;
}

/**
 * Changes information about the user, such as password, name, and email, used when a user is reset because the resident moves out.
 * 
 * @param int $user_id ID of the user to investigate
 * @param string $new_pass Plain-text version of the new password for the user
 * @param string $new_first_name Plain-text version of the new first name for the user
 * @param string $new_last_name Plain-text version of the new last name for the user
 * @param string $new_email Plain-text version of the new email address for the user
 * @param string $apartment_num Plain-text version of the apartment number for the user. Currently unused
 * @param bool $debug Flag, indicating if the function call is a real run, or just a test to output sample results
 */
function reset_user_info($user_id, $new_pass, $new_first_name, $new_last_name, $new_email, $apartment_num, $debug = false) {
	if (!$debug) {
		# Update password of the user
		wp_set_password($new_pass, $user_id);

		# Update email, first name, and last name
		$args = array(
			'ID'         => $user_id,
			'user_email' => $new_email,
			'first_name' => $new_first_name,
			'last_name' => $new_last_name
		);
		wp_update_user($args);
	}
}
