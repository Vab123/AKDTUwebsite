<?php

save_settings($AKDTU_OPTIONS['leje-af-fælleshus-bestyrelse-besluttet.php']);

# Write settings interface
function AKDTU_leje_af_fælleshus_bestyrelse_besluttet_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['leje-af-fælleshus-bestyrelse-besluttet.php']);
}

function test_leje_af_fælleshus_bestyrelse_besluttet_mail() {
	$events = array_filter(EM_Events::get(array('limit' => 100, 'offset' => 0, 'order' => 'DESC', 'orderby' => 'event_date_modified', 'bookings' => false, 'owner' => false, 'pagination' => 0)), function ($event) {
		return substr($event->event_name, 0, 28) == "#_RENTAL_BEFORE_APARTMENTNUM";
	});

	$danish_events = array_filter($events, function ($EM_Event) {
		return pll_get_post_language($EM_Event->post_id, 'locale') == "da_DK";
	});

	$english_events = array_filter($events, function ($EM_Event) {
		return pll_get_post_language($EM_Event->post_id, 'locale') == "en_US";
	});

	if (count($danish_events) > 0 && count($english_events) > 0) {
		$EM_Event_da = $danish_events[array_keys($danish_events)[0]];
		$EM_Event_en = $english_events[array_keys($english_events)[0]];

		$admin_email = get_option('dbem_event_submitted_email_admin');

		echo '<h3>Mail ved godkendt leje</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$admin_email,
			$admin_email,
			'',
			'',
			prepend_attachments_string(get_option('dbem_event_approved_confirmation_email_attachments_da')),
			$EM_Event_da->output(get_option('dbem_event_approved_confirmation_email_subject_da'), 'raw'),
			nl2br($EM_Event_da->output(get_option('dbem_event_approved_confirmation_email_body_da'), 'html'))
		);
		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$admin_email,
			$admin_email,
			'',
			'',
			prepend_attachments_string(get_option('dbem_event_approved_confirmation_email_attachments_en')),
			$EM_Event_en->output(get_option('dbem_event_approved_confirmation_email_subject_en'), 'raw'),
			nl2br($EM_Event_en->output(get_option('dbem_event_approved_confirmation_email_body_en'), 'html'))
		);

		echo '<br><hr><h3>Mail ved godkendt ændring af leje</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$admin_email,
			$admin_email,
			'',
			'',
			prepend_attachments_string(get_option('dbem_event_reapproved_confirmation_email_attachments_da')),
			$EM_Event_da->output(get_option('dbem_event_reapproved_confirmation_email_subject_da'), 'raw'),
			nl2br($EM_Event_da->output(get_option('dbem_event_reapproved_confirmation_email_body_da'), 'html'))
		);
		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$admin_email,
			$admin_email,
			'',
			'',
			prepend_attachments_string(get_option('dbem_event_reapproved_confirmation_email_attachments_en')),
			$EM_Event_en->output(get_option('dbem_event_reapproved_confirmation_email_subject_en'), 'raw'),
			nl2br($EM_Event_en->output(get_option('dbem_event_reapproved_confirmation_email_body_en'), 'html'))
		);

		echo '<br><hr><h3>Mail ved afvist leje</h3><h4>Dansk:</h4>';
		echo_AKDTU_email_as_table(
			$admin_email,
			$admin_email,
			'',
			'',
			prepend_attachments_string(get_option('dbem_event_rejected_confirmation_email_attachments_da')),
			$EM_Event_da->output(get_option('dbem_event_rejected_confirmation_email_subject_da'), 'raw'),
			nl2br($EM_Event_da->output(get_option('dbem_event_rejected_confirmation_email_body_da'), 'html'))
		);
		echo '<h4>Engelsk:</h4>';
		echo_AKDTU_email_as_table(
			$admin_email,
			$admin_email,
			'',
			'',
			prepend_attachments_string(get_option('dbem_event_rejected_confirmation_email_attachments_en')),
			$EM_Event_en->output(get_option('dbem_event_rejected_confirmation_email_subject_en'), 'raw'),
			nl2br($EM_Event_en->output(get_option('dbem_event_rejected_confirmation_email_body_en'), 'html'))
		);
	} else {
		echo 'Der er ingen begivenheder der kan bruges til test...';
	}
}

?>
