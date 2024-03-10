<?php

save_settings($AKDTU_OPTIONS['havedag-tilmelding-beboer.php']);

# Write settings interface
function AKDTU_havedag_tilmelding_beboer_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['havedag-tilmelding-beboer.php']);
}

function test_havedag_tilmelding_beboer_mail() {
	$events = EM_Events::get(array('limit' => 4, 'offset' => 0, 'order' => 'DESC', 'orderby' => 'event_start', 'bookings' => true, 'owner' => false, 'pagination' => 0));

	$events = array_filter($events, function($EM_Event) {return count($EM_Event->get_bookings()->bookings) > 0;});

	if (count($events) > 0) {
		$EM_Event = $events[array_keys($events)[0]];

		$bookings = $EM_Event->get_bookings()->bookings;

		usort($bookings, function($a, $b) {return ((new DateTime($a->date()->format()))->getTimestamp() > (new DateTime($b->date()->format()))->getTimestamp() ? -1 : 1);});

		$EM_Booking = $bookings[array_keys($bookings)[0]];

		$to_email = $EM_Booking->get_person()->data->user_email;
		$from_email = get_option('dbem_event_submitted_email_admin');

		echo '<h3>Mail ved tilmelding</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$to_email,
			$from_email,
			'',
			'',
			array(),
			$EM_Booking->output(get_option('dbem_bookings_email_confirmed_subject_da'), 'raw'),
			nl2br($EM_Booking->output(get_option('dbem_bookings_email_confirmed_body_da'), 'html'))
		);

		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$to_email,
			$from_email,
			'',
			'',
			array(),
			$EM_Booking->output(get_option('dbem_bookings_email_confirmed_subject_en'), 'raw'),
			nl2br($EM_Booking->output(get_option('dbem_bookings_email_confirmed_body_en'), 'html'))
		);

		echo '<br><hr><h3>Mail ved afmelding</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$to_email,
			$from_email,
			'',
			'',
			array(),
			$EM_Booking->output(get_option('dbem_bookings_email_cancelled_subject_da'), 'raw'),
			nl2br($EM_Booking->output(get_option('dbem_bookings_email_cancelled_body_da'), 'html'))
		);

		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$to_email,
			$from_email,
			'',
			'',
			array(),
			$EM_Booking->output(get_option('dbem_bookings_email_cancelled_subject_en'), 'raw'),
			nl2br($EM_Booking->output(get_option('dbem_bookings_email_cancelled_body_en'), 'html'))
		);
	} else {
		echo 'Der er ingen begivenheder der kan bruges til test...';
	}
}

?>