<?php

/**
 * @file Action to allow a temporary renter to create a user on the website
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'allow_renter_signup' && isset($_REQUEST['user']) && isset($_REQUEST['phone']) && isset($_REQUEST['start_time']) && isset($_REQUEST['end_time'])) {
		allow_renter_signup($_REQUEST['user'], $_REQUEST['phone'], $_REQUEST['start_time'], $_REQUEST['end_time']);
	}
}

/**
 * Add a permit for a temporary renter to create a user on the website
 * 
 * @param int $apartment_number Apartment number for the permit
 * @param string $phone_number Phone number for the permit
 * @param string $start_time Start-date and -time for takeover of the user for the permit
 * @param string $end_time End-date and -time for takeover of the user for the permit
 * 
 * @return bool True if the permit was created successfully
 */
function allow_renter_signup($apartment_number, $phone_number, $start_time, $end_time) {
	global $wpdb;

	# Check if there already exists a permit for user creation already exists
	if ($wpdb->query('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_rentercreation WHERE apartment_number="'. $apartment_number . '" AND allow_creation_date >= "' . (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format("Y-m-d H:i:s") . '"') > 0) {
		# A permit already exists. Output error message and return
		new AKDTU_notice('error', 'Den midlertidige lejer kunne ikke oprettes. Check om der allerede findes en midlertidig lejer i den samme lejlighed.');

		return false;
	} else {
		# A permit does not exist. Create new permit
		
		# Data for new user
		$data = array(
			'apartment_number' => $apartment_number,
			'phone_number' => $phone_number,
			'start_time' => $start_time,
			'end_time' => $end_time,
			'initial_reset' => false,
			'initial_takeover' => false
		);

		# Insert permit into database
		$inserted = $wpdb->insert($wpdb->prefix . 'swpm_allowed_rentercreation', $data);

		# Write success message to admin interface
		if ($inserted) {
			new AKDTU_notice('success', 'Den midlertidige lejer blev oprettet.');

			return true;
		}
		
		new AKDTU_notice('error', 'Den midlertidige lejer blev ikke oprettet.');

		return false;
	}
}
