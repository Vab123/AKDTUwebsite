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
 * @return string Formatted string with info about the deleted signups, formatted according to `FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS`
 */
function delete_previous_gardendays($user_id, $archive_user_id, $debug = false) {
	global $wpdb;
	#
	# Glossary
	# Booking: Signup for a specific user to a gardenday
	# Ticket: Date for a specific garden day, to which a user can sign up
	# Event: Collection of all garden days for an entire season, with a ticket for each individual garden day, and potentially with bookings from many users
	#

	# Current time, formatted as string
	$current_time = (new DateTime("now", new DateTimeZone('Europe/Copenhagen')))->format("Y-m-d H:i:s");

	# Get EM-structs for the user, and all of their bookings
	$EM_Bookings = array_filter((new EM_Person($user_id))->get_bookings()->load(), function ($EM_Booking) use($current_time) {
		$EM_Event = em_get_event($EM_Booking->event_id);
		$event_time = $EM_Event->event_start_date . ' ' . $EM_Event->event_start_time;

		return $event_time < $current_time;
	});

	if (!$debug) {
		array_walk($EM_Bookings, function ($EM_Booking) use($archive_user_id) {
			move_booking_to_user($archive_user_id, $EM_Booking);
		});
	}

	$pending_or_approved_bookings = array_filter($EM_Bookings, function ($EM_Booking) {
		# Check if booking is pending or approved
		# 0 => Pending
		# 1 => Approved
		# 2 => Rejected
		# 3 => Cancelled
		# 4 => Awaiting Online Payment
		# 5 => Awaiting payment
		return $EM_Booking->booking_status <= 1;
	});

	# Check if the person has made any bookings
	if (count($pending_or_approved_bookings) > 0) {
		return join('<br><br>', array_map(function ($EM_Booking) {
			$event_id = $EM_Booking->event_id;
			$EM_Event = em_get_event($event_id);

			# Get all tickets 
			$ticketid = array_keys($EM_Booking->get_tickets()->tickets)[0];
			$ticket_date = new DateTime($EM_Booking->get_tickets()->tickets[$ticketid]->__get('ticket_name'));

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
			return str_replace(array_keys($replaces), $replaces, nl2br(FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS));
		}, $pending_or_approved_bookings));
	} else {
		# Return string is information about the fact that no bookings was deleted
		return '(Ingen tilmeldinger til tidligere havedage blev fundet)';
	}
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
 * @return string Formatted string with info about the signups, formatted according to `FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS`
 */
function find_future_gardendays($user_id, $archive_user_id, $debug = false) {
	global $wpdb;
	#
	# Glossary
	# Booking: Signup for a specific user to a gardenday
	# Ticket: Date for a specific garden day, to which a user can sign up
	# Event: Collection of all garden days for an entire season, with a ticket for each individual garden day, and potentially with bookings from many users
	#

	# Current time, formatted as string
	$current_time = (new DateTime("now", new DateTimeZone('Europe/Copenhagen')))->format("Y-m-d H:i:s");

	# Get EM-structs for the user, and all of their bookings
	$EM_Bookings = array_filter((new EM_Person($user_id))->get_bookings()->load(), function ($EM_Booking) use($current_time) {
		$EM_Event = em_get_event($EM_Booking->event_id);
		$event_time = $EM_Event->event_start_date . ' ' . $EM_Event->event_start_time;

		return $event_time >= $current_time;
	});

	if (!$debug) {
		array_walk($EM_Bookings, function ($EM_Booking) use($archive_user_id) {
			move_booking_to_user($archive_user_id, $EM_Booking);
		});
	}

	$pending_or_approved_bookings = array_filter($EM_Bookings, function ($EM_Booking) {
		# Check if booking is pending or approved
		# 0 => Pending
		# 1 => Approved
		# 2 => Rejected
		# 3 => Cancelled
		# 4 => Awaiting Online Payment
		# 5 => Awaiting payment
		return $EM_Booking->booking_status <= 1;
	});

	# Check if the person has made any bookings
	if (count($pending_or_approved_bookings) > 0) {
		return join('<br><br>', array_map(function ($EM_Booking) {
			$event_id = $EM_Booking->event_id;
			$EM_Event = em_get_event($event_id);

			# Get all tickets 
			$ticketid = array_keys($EM_Booking->get_tickets()->tickets)[0];
			$ticket_date = new DateTime($EM_Booking->get_tickets()->tickets[$ticketid]->__get('ticket_name'));

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
			return str_replace(array_keys($replaces), $replaces, nl2br(FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS));
		}, $pending_or_approved_bookings));
	} else {
		# Return string is information about the fact that no bookings was deleted
		return '(Ingen tilmeldinger til fremtidige havedage blev fundet)';
	}
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
 * @return string Formatted string with info about the rentals, formatted according to `FJERNBRUGERADGANG_FORMAT_RENTALS`
 */
function delete_rentals($user_id, $archive_user_id, $debug = false) {
	global $wpdb;

	$EM_Events = array_map(function ($event_id) {
		return em_get_event($event_id, 'event_id');
	},	$wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_owner = "' . $user_id . '" AND event_status >= 0'));

	# Check if this is a real call, or only a check of dummy results
	if (!$debug) {
		# Real run. Move the events to archive user
		array_walk($EM_Events, function ($EM_Event) use($archive_user_id) {
			move_event_to_user($archive_user_id, $EM_Event);
		});
	}

	if (count($EM_Events) > 0) {
		return join('<br><br>', array_map(function ($EM_Event) {
			# Get start- and finish-times of the event
			$start_date = new DateTime($EM_Event->event_start_date . " " . $EM_Event->event_start_time);
			$end_date = new DateTime($EM_Event->event_end_date . " " . $EM_Event->event_end_time);

			# Save information about the rental
			$replaces = array(
				'#NAME' => format_common_house_rental_name($EM_Event, "da_DK"),

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
			return str_replace(array_keys($replaces), $replaces, nl2br(FJERNBRUGERADGANG_FORMAT_RENTALS));
		}, $EM_Events));
	} else {
		# There were no rentals to delete. Return string is information about the fact that no rentals was deleted
		return '(Ingen udlejninger af fælleshuset blev fundet)';
	}
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
			'last_name' => $new_last_name,
			'display_name' => $new_first_name . ' ' . $new_last_name,
		);
		wp_update_user($args);
	}
}

/**
 * Moves a booking to a different user
 * 
 * @param int $user_id ID of the user to move the booking to
 * @param EM_Booking $EM_Booking The booking to be moved
 */
function move_booking_to_user($user_id, $EM_Booking) {
	global $wpdb;

	$sql = $wpdb->prepare("UPDATE " . EM_BOOKINGS_TABLE . " SET person_id=%d WHERE booking_id=%d", $user_id, $EM_Booking->booking_id);
	$wpdb->query($sql);
}

/**
 * Moves an event to a different user
 * 
 * @param int $user_id ID of the user to move the event to
 * @param EM_Event $EM_Event The event to be moved
 */
function move_event_to_user($user_id, $EM_Event) {
	global $wpdb;

	$sql = $wpdb->prepare("UPDATE " . EM_EVENTS_TABLE . " SET event_owner=%d WHERE event_id=%d", $user_id, $EM_Event->event_id);
	$wpdb->query($sql);

	# Move the corresponding post to archive user
	$post_updated = wp_update_post(
		array(
			'ID'          => $EM_Event->post_id,
			'post_author' => $user_id,
		)
	);
}