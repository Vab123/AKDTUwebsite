<?php

/**
 * @file Action to delete a registration for an apartment to a garden day
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'afmeld_havedag' && isset($_REQUEST['havedag_event_id']) && isset($_REQUEST['havedag_dato']) && isset($_REQUEST['user'])){
		remove_signup_to_gardenday_for_apartment($_REQUEST['user'], $_REQUEST['havedag_event_id'], $_REQUEST['havedag_dato']);
	}
}

/**
 * Delete a registration for an apartment to a garden day
 * 
 * @param int $apartment_number Apartment number
 * @param int $havedag_event_id Event id of the garden day
 * @param string $havedag_dato Date of the garden day, where the apartment should no longer be signed up
 */
function remove_signup_to_gardenday_for_apartment($apartment_number, $havedag_event_id, $havedag_dato){
	# Check if the apartment number is valid
	if ($apartment_number > 0) {
		global $wpdb;

		# Get the id of the user corresponding to the apartment
		$user_id = id_from_apartment_number($apartment_number);

		# Get the garden day event
		$event = em_get_event($havedag_event_id,'event_id');
		
		# Get all bookings for the garden day
		$bookings = $event->get_bookings();

		# Get all tickets for the garden day
		$tickets = $bookings->get_tickets()->tickets;

		# Get the bookings of the user to the garden day
		$booking_ids = $wpdb->get_col("SELECT booking_id FROM " . EM_BOOKINGS_TABLE . " WHERE event_id=" . $havedag_event_id . " AND person_id=" . $user_id);

		# Check if the garden day is valid, and the user is currently signed up
		if (isset($tickets[$havedag_dato]) && count($booking_ids) > 0){
			# Remove tickets from the booking
			$result2 = $wpdb->delete(EM_TICKETS_BOOKINGS_TABLE, array( 'booking_id' => $booking_ids[0], 'ticket_id' => $havedag_dato ) );

			# Remove the booking from the user
			$result = $wpdb->delete(EM_BOOKINGS_TABLE, array('event_id' => $havedag_event_id, 'person_id' => $user_id ));

			# Write success message to admin interface
			new AKDTU_notice('success', 'Lejlighed ' . $apartment_number . ' er nu ikke længere tilmeldt havedagen ' . $tickets[$havedag_dato]->ticket_name . '.');
		} else {
			# Something went wrong. Output an error message
			if (isset($tickets[$havedag_dato])) {
				# The apartment was not signed up to the garden day on that date
				new AKDTU_notice('error', 'Lejlighed ' . $apartment_number . ' var i forvejen ikke tilmeldt havedagen ' . $tickets[$havedag_dato]->ticket_name . '.');
			} else {
				# The apartment was not signed up to the garden day at all
				new AKDTU_notice('error', 'Lejlighed ' . $apartment_number . ' var i forvejen ikke tilmeldt havedagen.');
			}
		}
	}
}
