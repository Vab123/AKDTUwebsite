<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'allow_user_signup' && isset($_REQUEST['user']) && isset($_REQUEST['phone']) && isset($_REQUEST['takeover_time'])) {
		allow_user_signup();
	}
}

function allow_user_signup() {
	global $wpdb;
	$data = array(
		'apartment_number' => $_REQUEST['user'],
		'phone_number' => $_REQUEST['phone'],
		'allow_creation_date' => $_REQUEST['takeover_time'],
		'initial_reset' => false,
		'initial_takeover' => false
	);

	if ($wpdb->query($wpdb->prepare("SELECT apartment_number FROM " . $wpdb->prefix . "swpm_allowed_membercreation WHERE apartment_number=%d", $data['apartment_number'])) > 0) {
		new AKDTU_notice('error', 'Tilladelsen til brugeroprettelse kunne ikke oprettes. Check om der allerede findes en tilladelse til brugeren.');
	} else {
		$inserted = $wpdb->insert($wpdb->prefix . 'swpm_allowed_membercreation', $data);

		new AKDTU_notice('success', 'Tilladelsen til brugeroprettelse blev oprettet.');
	}
}
