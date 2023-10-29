<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'tilmeld_havedag' && isset($_REQUEST['havedag_event_id']) && isset($_REQUEST['havedag_dato'])){
		tilmeld_lejlighed_til_havedag();
	}
}

function tilmeld_lejlighed_til_havedag(){
	global $wpdb;
	if ($_REQUEST['user'] > 0) {
		$apartment_num = $_REQUEST['user'];
		$user_id = id_from_apartment_number($apartment_num);

		if ($wpdb->get_var("SELECT COUNT(*) FROM " . EM_BOOKINGS_TABLE . " WHERE event_id=" . $_REQUEST['havedag_event_id'] . " AND person_id=" . $user_id) > 0 . " AND status=1") {
			new AKDTU_notice('warning','Lejlighed ' . $apartment_num . ' var allerede tilmeldt havedagen. Tilmelder igen.');
		}

		$result = $wpdb->insert(EM_BOOKINGS_TABLE, array('event_id' => $_REQUEST['havedag_event_id'], 'person_id' => $user_id, 'booking_spaces' => 1, 'booking_comment' => '', 'booking_date' => gmdate('Y-m-d H:i:s'), 'booking_status' => 1, 'booking_price' => 0, 'booking_tax_rate' => 0, 'booking_meta' => 'a:0:{}' )); // Add booking to user

		$result2 = $wpdb->insert(EM_TICKETS_BOOKINGS_TABLE, array( 'booking_id' => $wpdb->insert_id, 'ticket_id' => $_REQUEST['havedag_dato'], 'ticket_booking_spaces' => 1, 'ticket_booking_price' => 0 ) ); // Add tickets to booking

		$event = em_get_event($_REQUEST['havedag_event_id'],'event_id');
		$bookings = $event->get_bookings();
		$tickets = $bookings->get_tickets()->tickets;

		if ($result === 1 && $result2 === 1 && isset($tickets[$_REQUEST['havedag_dato']])) {
			new AKDTU_notice('success','Lejlighed ' . $apartment_num . ' er nu tilmeldt havedagen ' . $tickets[$_REQUEST['havedag_dato']]->ticket_name . '.');
		}else{
			if (isset($tickets[$_REQUEST['havedag_dato']])){
				new AKDTU_notice('error','Lejlighed ' . $apartment_num . ' kunne ikke tilmeldes havedagen ' . $tickets[$_REQUEST['havedag_dato']]->ticket_name . '.');
			} else {
				new AKDTU_notice('error','Lejlighed ' . $apartment_num . ' kunne ikke tilmeldes havedagen.');
			}
		}
	}
}
