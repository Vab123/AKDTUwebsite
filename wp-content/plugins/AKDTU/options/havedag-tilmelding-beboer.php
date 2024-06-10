<?php

save_settings($AKDTU_OPTIONS['havedag-tilmelding-beboer.php']);

# Write settings interface
function AKDTU_havedag_tilmelding_beboer_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['havedag-tilmelding-beboer.php']);
}

function test_havedag_tilmelding_beboer_mail() {
	$events = any_gardenday('all');

	if (is_null($events)) {
		echo 'Der er ingen begivenheder der kan bruges til test...';
		return;
	}

	$EM_Event_da = $events[0]["da"];
	$bookings_da = $EM_Event_da->get_bookings()->bookings;
	usort($bookings_da, function($a, $b) {return ((new DateTime($a->date()->format()))->getTimestamp() > (new DateTime($b->date()->format()))->getTimestamp() ? -1 : 1);});
	$EM_Booking_da = $bookings_da[array_keys($bookings_da)[0]];
	$to_email_da = $EM_Booking_da->get_person()->data->user_email;
	
	$EM_Event_en = $events[0]["en"];
	$bookings_en = $EM_Event_en->get_bookings()->bookings;
	usort($bookings_en, function($a, $b) {return ((new DateTime($a->date()->format()))->getTimestamp() > (new DateTime($b->date()->format()))->getTimestamp() ? -1 : 1);});
	$EM_Booking_en = $bookings_en[array_keys($bookings_en)[0]];
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
}

?>