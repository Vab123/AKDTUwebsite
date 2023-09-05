<?php
require_once WP_PLUGIN_DIR . '/AKDTU/functions/notice.php';

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'allow_renter_signup' && isset($_REQUEST['user']) && isset($_REQUEST['phone']) && isset($_REQUEST['start_time']) && isset($_REQUEST['end_time'])) {
		allow_renter_signup();
	}
}

function allow_renter_signup() {
	global $wpdb;
	$data = array(
		'apartment_number' => $_REQUEST['user'],
		'phone_number' => $_REQUEST['phone'],
		'start_time' => $_REQUEST['start_time'],
		'end_time' => $_REQUEST['end_time'],
		'initial_reset' => false,
		'initial_takeover' => false
	);

	if ($wpdb->query($wpdb->prepare("SELECT apartment_number FROM " . $wpdb->prefix . "swpm_allowed_rentercreation WHERE apartment_number=%d", $data['apartment_number'])) > 0) {
		new AKDTU_notice('error', 'Den midlertidige lejer kunne ikke oprettes. Check om der allerede findes en midlertidig lejer i den samme lejlighed.');
	} else {
		$inserted = $wpdb->insert($wpdb->prefix . 'swpm_allowed_rentercreation', $data);

		new AKDTU_notice('success', 'Den midlertidige lejer blev oprettet.');
	}
}
