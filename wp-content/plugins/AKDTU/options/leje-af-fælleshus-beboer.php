<?php

save_settings($AKDTU_OPTIONS['leje-af-fælleshus-beboer.php']);

# Write settings interface
function AKDTU_leje_af_fælleshus_beboer_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['leje-af-fælleshus-beboer.php']);
}

function test_leje_af_fælleshus_beboer_mail() {
	$events = array_filter(EM_Events::get(array('scope' => 'future', 'limit' => 10, 'offset' => 0, 'order' => 'ASC', 'orderby' => 'event_start', 'bookings' => false, 'owner' => false, 'pagination' => 0)), function ($event) {
		return is_common_house_rental($event);
	});

	if (count($events) > 0) {
		$EM_Event = $events[array_keys($events)[0]];

		$admin_email = get_option('dbem_event_submitted_email_admin');
		$owner_email = get_userdata($EM_Event->event_owner)->user_email;

		echo '<h3>Mail ved godkendt leje</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$owner_email,
			$admin_email,
			$admin_email,
			'',
			prepend_attachments_string(get_option('dbem_event_approved_email_attachments_da')),
			$EM_Event->output(get_option('dbem_event_approved_email_subject_da'), 'raw'),
			nl2br($EM_Event->output(get_option('dbem_event_approved_email_body_da'), 'html'))
		);

		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$owner_email,
			$admin_email,
			$admin_email,
			'',
			prepend_attachments_string(get_option('dbem_event_approved_email_attachments_en')),
			$EM_Event->output(get_option('dbem_event_approved_email_subject_en'), 'raw'),
			nl2br($EM_Event->output(get_option('dbem_event_approved_email_body_en'), 'html'))
		);

		echo '<br><hr><h3>Mail ved godkendt ændring af leje</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$owner_email,
			$admin_email,
			$admin_email,
			'',
			prepend_attachments_string(get_option('dbem_event_reapproved_email_attachments_da')),
			$EM_Event->output(get_option('dbem_event_reapproved_email_subject_da'), 'raw'),
			nl2br($EM_Event->output(get_option('dbem_event_reapproved_email_body_da'), 'html'))
		);

		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$owner_email,
			$admin_email,
			$admin_email,
			'',
			prepend_attachments_string(get_option('dbem_event_reapproved_email_attachments_en')),
			$EM_Event->output(get_option('dbem_event_reapproved_email_subject_en'), 'raw'),
			nl2br($EM_Event->output(get_option('dbem_event_reapproved_email_body_en'), 'html'))
		);

		echo '<br><hr><h3>Mail ved afvist leje</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$owner_email,
			$admin_email,
			$admin_email,
			'',
			prepend_attachments_string(get_option('dbem_event_rejected_email_attachments_da')),
			$EM_Event->output(get_option('dbem_event_rejected_email_subject_da'), 'raw'),
			nl2br($EM_Event->output(get_option('dbem_event_rejected_email_body_da'), 'html'))
		);

		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$owner_email,
			$admin_email,
			$admin_email,
			'',
			prepend_attachments_string(get_option('dbem_event_rejected_email_attachments_en')),
			$EM_Event->output(get_option('dbem_event_rejected_email_subject_en'), 'raw'),
			nl2br($EM_Event->output(get_option('dbem_event_rejected_email_body_en'), 'html'))
		);
	} else {
		echo 'Der er ingen begivenheder der kan bruges til test...';
	}
}

?>