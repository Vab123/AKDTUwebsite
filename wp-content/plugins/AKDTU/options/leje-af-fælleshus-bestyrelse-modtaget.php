<?php

save_settings($AKDTU_OPTIONS['leje-af-fælleshus-bestyrelse-modtaget.php']);

# Write settings interface
function AKDTU_leje_af_fælleshus_bestyrelse_modtaget_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['leje-af-fælleshus-bestyrelse-modtaget.php']);
}

function test_leje_af_fælleshus_bestyrelse_modtaget_mail() {
	$events = array_filter(EM_Events::get(array('limit' => 10, 'offset' => 0, 'order' => 'DESC', 'orderby' => 'event_date_modified', 'bookings' => false, 'owner' => false, 'pagination' => 0)), function ($event) {
		return substr($event->event_name, 0, 28) == "#_RENTAL_BEFORE_APARTMENTNUM";
	});

	if (count($events) > 0) {
		$EM_Event = $events[array_keys($events)[0]];

		$admin_email = get_option('dbem_event_submitted_email_admin');

		echo '<h3>Mail ved ny ansøgning</h3>';
		echo_AKDTU_email_as_table(
			$admin_email,
			$admin_email,
			$admin_email,
			'',
			prepend_attachments_string(get_option('dbem_event_submitted_email_attachments')),
			$EM_Event->output(get_option('dbem_event_submitted_email_subject'), 'raw'),
			nl2br($EM_Event->output(get_option('dbem_event_submitted_email_body'), 'html'))
		);

		echo '<br><hr><h3>Mail ved ændret ansøgning</h3>';
		echo_AKDTU_email_as_table(
			$admin_email,
			$admin_email,
			$admin_email,
			'',
			prepend_attachments_string(get_option('dbem_event_resubmitted_email_attachments')),
			$EM_Event->output(get_option('dbem_event_resubmitted_email_subject'), 'raw'),
			nl2br($EM_Event->output(get_option('dbem_event_resubmitted_email_body'), 'html'))
		);

		echo '<br><hr><h3>Mail ved godkendt ændring af leje</h3>';
		echo_AKDTU_email_as_table(
			$admin_email,
			$admin_email,
			$admin_email,
			'',
			prepend_attachments_string(get_option('dbem_event_deleted_email_attachments')),
			$EM_Event->output(get_option('dbem_event_deleted_email_subject'), 'raw'),
			nl2br($EM_Event->output(get_option('dbem_event_deleted_email_body'), 'html'))
		);
	} else {
		echo 'Der er ingen begivenheder der kan bruges til test...';
	}
}

?>