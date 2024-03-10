<?php

save_settings($AKDTU_OPTIONS['havedag-tilmelding-beboer.php']);

# Write settings interface
function AKDTU_havedag_tilmelding_beboer_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['havedag-tilmelding-beboer.php']);
}

function test_havedag_tilmelding_beboer_mail() {
	$events = EM_Events::get(array('limit' => 10, 'offset' => 0, 'order' => 'DESC', 'orderby' => 'event_start', 'bookings' => true, 'owner' => false, 'pagination' => 0));

	$events = array_filter($events, function($EM_Event) {return count($EM_Event->get_bookings()->bookings) > 0;});

	$danish_events = array_filter($events, function ($event) {
		return pll_get_post_language($event->post_id, 'locale') == "da_DK";
	});
	
	$english_events = array_filter($events, function ($event) {
		return pll_get_post_language($event->post_id, 'locale') == "en_US";
	});

	if (count($danish_events) > 0 && count($english_events) > 0) {
		$EM_Event_da = $danish_events[array_keys($danish_events)[0]];
		$EM_Event_en = $english_events[array_keys($english_events)[0]];

		$bookings_da = $EM_Event_da->get_bookings()->bookings;
		$bookings_en = $EM_Event_en->get_bookings()->bookings;

		usort($bookings_da, function($a, $b) {return ((new DateTime($a->date()->format()))->getTimestamp() > (new DateTime($b->date()->format()))->getTimestamp() ? -1 : 1);});
		usort($bookings_en, function($a, $b) {return ((new DateTime($a->date()->format()))->getTimestamp() > (new DateTime($b->date()->format()))->getTimestamp() ? -1 : 1);});

		$EM_Booking_da = $bookings_da[array_keys($bookings_da)[0]];
		$EM_Booking_en = $bookings_en[array_keys($bookings_en)[0]];

		$to_email_da = $EM_Booking_da->get_person()->data->user_email;
		$to_email_en = $EM_Booking_en->get_person()->data->user_email;
		$from_email = get_option('dbem_event_submitted_email_admin');

		echo '<h3>Mail ved tilmelding</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$to_email_da,
			$from_email,
			'',
			'',
			array(),
			$EM_Booking_da->output(get_option('dbem_bookings_email_confirmed_subject_da'), 'raw'),
			nl2br($EM_Booking_da->output(get_option('dbem_bookings_email_confirmed_body_da'), 'html'))
		);

		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$to_email_en,
			$from_email,
			'',
			'',
			array(),
			$EM_Booking_en->output(get_option('dbem_bookings_email_confirmed_subject_en'), 'raw'),
			nl2br($EM_Booking_en->output(get_option('dbem_bookings_email_confirmed_body_en'), 'html'))
		);

		echo '<br><hr><h3>Mail ved afmelding</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$to_email_da,
			$from_email,
			'',
			'',
			array(),
			$EM_Booking_da->output(get_option('dbem_bookings_email_cancelled_subject_da'), 'raw'),
			nl2br($EM_Booking_da->output(get_option('dbem_bookings_email_cancelled_body_da'), 'html'))
		);

		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$to_email_en,
			$from_email,
			'',
			'',
			array(),
			$EM_Booking_en->output(get_option('dbem_bookings_email_cancelled_subject_en'), 'raw'),
			nl2br($EM_Booking_en->output(get_option('dbem_bookings_email_cancelled_body_en'), 'html'))
		);
	} else {
		echo 'Der er ingen begivenheder der kan bruges til test...';
	}
}

?>