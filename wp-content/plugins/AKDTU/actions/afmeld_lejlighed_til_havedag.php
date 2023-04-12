<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'afmeld_havedag' && isset($_REQUEST['havedag_event_id']) && isset($_REQUEST['havedag_dato'])){
		afmeld_havedag();
	}
}

function afmeld_havedag(){
	global $wpdb;
	if ($_REQUEST['user'] > 0) {
		$apartment_num = $_REQUEST['user'];
		$user = get_user_by('login', 'lejl' . str_pad($_REQUEST['user'],3,"0",STR_PAD_LEFT));
		$user_id = $user->ID;

		$event = em_get_event($_REQUEST['havedag_event_id'],'event_id');
		$bookings = $event->get_bookings();
		$tickets = $bookings->get_tickets()->tickets;

		$booking_ids = $wpdb->get_col("SELECT booking_id FROM " . EM_BOOKINGS_TABLE . " WHERE event_id=" . $_REQUEST['havedag_event_id'] . " AND person_id=" . $user_id);

		if (isset($tickets[$_REQUEST['havedag_dato']]) && count($booking_ids) > 0){
			$result2 = $wpdb->delete(EM_TICKETS_BOOKINGS_TABLE, array( 'booking_id' => $booking_ids[0], 'ticket_id' => $_REQUEST['havedag_dato'] ) ); // Remove tickets from booking

			$result = $wpdb->delete(EM_BOOKINGS_TABLE, array('event_id' => $_REQUEST['havedag_event_id'], 'person_id' => $user_id )); // Remove booking from user

			new AKDTU_notice('success', 'Lejlighed ' . $apartment_num . ' er nu ikke længere tilmeldt havedagen ' . $tickets[$_REQUEST['havedag_dato']]->ticket_name . '.');
		} else {
			if (isset($tickets[$_REQUEST['havedag_dato']])) {
				new AKDTU_notice('error', 'Lejlighed ' . $apartment_num . ' var i forvejen ikke tilmeldt havedagen ' . $tickets[$_REQUEST['havedag_dato']]->ticket_name . '.');
			} else {
				new AKDTU_notice('error', 'Lejlighed ' . $apartment_num . ' var i forvejen ikke tilmeldt havedagen.');
			}
		}
	}
}
