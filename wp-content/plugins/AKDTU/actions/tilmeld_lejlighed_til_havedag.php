<?php

/**
 * @file Action to add a registration for an apartment to a garden day
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'tilmeld_havedag' && isset($_REQUEST['havedag_event_id']) && isset($_REQUEST['havedag_dato'])){
		add_signup_to_gardenday_for_apartment($_REQUEST['user'], $_REQUEST['havedag_event_id'], $_REQUEST['havedag_dato']);
	}
}

/**
 * Add a registration for an apartment to a garden day
 * 
 * @param int $apartment_number Apartment number
 * @param int $havedag_event_id Event id of the garden day
 * @param string $gardenday_date Date of the garden day, where the apartment should be signed up
 */
function add_signup_to_gardenday_for_apartment($apartment_number, $gardenday_event_id, $gardenday_date){
	if ($apartment_number > 0) {
		global $wpdb;

		# Get the id of the user corresponding to the apartment
		$user_id = id_from_apartment_number($apartment_number);

		# Check if the apartment is already signed up to any of the garden days
		if ($wpdb->get_var("SELECT COUNT(*) FROM " . EM_BOOKINGS_TABLE . " WHERE event_id=" . $gardenday_event_id . " AND person_id=" . $user_id) > 0 . " AND status=1") {
			# Apartment is already signed up. Write warning message to admin interface
			new AKDTU_notice('warning','Lejlighed ' . $apartment_number . ' var allerede tilmeldt havedagen. Tilmelder igen.');
		}

		# Add booking to user
		$result = $wpdb->insert(EM_BOOKINGS_TABLE, array('event_id' => $gardenday_event_id, 'person_id' => $user_id, 'booking_spaces' => 1, 'booking_comment' => '', 'booking_date' => gmdate('Y-m-d H:i:s'), 'booking_status' => 1, 'booking_price' => 0, 'booking_tax_rate' => 0, 'booking_meta' => 'a:0:{}' ));

		# Add tickets to booking
		$result2 = $wpdb->insert(EM_TICKETS_BOOKINGS_TABLE, array( 'booking_id' => $wpdb->insert_id, 'ticket_id' => $gardenday_date, 'ticket_booking_spaces' => 1, 'ticket_booking_price' => 0 ) );

		# Get the garden day event
		$event = em_get_event($gardenday_event_id,'event_id');
		
		# Get all bookings  for the garden day event
		$bookings = $event->get_bookings();
		
		# Get all tickets to all bookings for the garden day event
		$tickets = $bookings->get_tickets()->tickets;

		# Check if new booking was successfully added
		if ($result === 1 && $result2 === 1 && isset($tickets[$gardenday_date])) {
			# Booking was added. Write success message to admin interface
			new AKDTU_notice('success','Lejlighed ' . $apartment_number . ' er nu tilmeldt havedagen ' . $tickets[$gardenday_date]->ticket_name . '.');
		}else{
			# Booking was not added

			# Check if the garden day exists
			if (isset($tickets[$gardenday_date])){
				# Garden day exists, but user could not be signed up. Write error message to admin interface
				new AKDTU_notice('error','Lejlighed ' . $apartment_number . ' kunne ikke tilmeldes havedagen ' . $tickets[$gardenday_date]->ticket_name . '.');
			} else {
				# Garden day does not exist, and user could not be signed up. Write error message to admin interface
				new AKDTU_notice('error','Lejlighed ' . $apartment_number . ' kunne ikke tilmeldes havedagen.');
			}
		}
	}
}
