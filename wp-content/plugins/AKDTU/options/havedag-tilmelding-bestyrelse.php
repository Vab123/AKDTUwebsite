<?php

save_settings($AKDTU_OPTIONS['havedag-tilmelding-bestyrelse.php']);

# Write settings interface
function AKDTU_havedag_tilmelding_bestyrelse_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['havedag-tilmelding-bestyrelse.php']);
};

function test_havedag_tilmelding_bestyrelse_mail() {
	$events = any_gardenday('da');

	if (is_null($events)) {
		echo 'Der er ingen begivenheder der kan bruges til test...';
		return;
	}

	$EM_Event = $events[0];
	$bookings = $EM_Event->get_bookings()->bookings;
	usort($bookings, function($a, $b) {return ((new DateTime($a->date()->format()))->getTimestamp() > (new DateTime($b->date()->format()))->getTimestamp() ? -1 : 1);});
	$EM_Booking = $bookings[array_keys($bookings)[0]];

	$to_email = get_option('dbem_bookings_notify_admin');
	$from_email = get_option('dbem_event_submitted_email_admin');

	echo '<h3>Mail ved tilmelding</h3>';
	echo_AKDTU_email_as_table(
		$to_email,
		$from_email,
		'',
		'',
		array(),
		$EM_Booking->output(get_option('dbem_bookings_contact_email_confirmed_subject'), 'raw'),
		nl2br($EM_Booking->output(get_option('dbem_bookings_contact_email_confirmed_body'), 'html'))
	);

	echo '<br><hr><h3>Mail ved afmelding</h3>';
	echo_AKDTU_email_as_table(
		$to_email,
		$from_email,
		'',
		'',
		array(),
		$EM_Booking->output(get_option('dbem_bookings_contact_email_cancelled_subject'), 'raw'),
		nl2br($EM_Booking->output(get_option('dbem_bookings_contact_email_cancelled_body'), 'html'))
	);
}

?>