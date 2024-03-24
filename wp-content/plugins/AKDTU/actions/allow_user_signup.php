<?php

/**
 * @file Action to allow a resident to create a user on the website
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'allow_user_signup' && isset($_REQUEST['user']) && isset($_REQUEST['phone']) && isset($_REQUEST['takeover_time'])) {
		allow_user_signup($_REQUEST['user'], $_REQUEST['phone'], $_REQUEST['takeover_time']);
	}
}

/**
 * Add a permit for a resident to create a user on the website
 * 
 * @param int $apartment_number Apartment number for the permit
 * @param string $phone_number Phone number for the permit
 * @param string $takeover_time Date and time for takeover of the user for the permit
 * 
 * @return bool True if the permit was created successfully
 */
function allow_user_signup($apartment_number, $phone_number, $takeover_time) {
	global $wpdb;

	# Check if there already exists a permit for user creation already exists
	if ($wpdb->query('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_membercreation WHERE apartment_number="'. $apartment_number . '" AND allow_creation_date >= "' . (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format("Y-m-d H:i:s") . '"') > 0) {
		# A permit already exists. Output error message and return
		new AKDTU_notice('error', 'Tilladelsen til brugeroprettelse kunne ikke oprettes. Check om der allerede findes en tilladelse til brugeren.');

		return false;
	} else {
		# A permit does not exist. Create new permit
		
		# Data for new user
		$data = array(
			'apartment_number' => $apartment_number,
			'phone_number' => $phone_number,
			'allow_creation_date' => $takeover_time,
			'initial_reset' => false,
			'initial_takeover' => false
		);

		# Insert permit into database
		$inserted = $wpdb->insert($wpdb->prefix . 'swpm_allowed_membercreation', $data);

		# Write success message to admin interface
		if ($inserted) {
			new AKDTU_notice('success', 'Tilladelsen til brugeroprettelse blev oprettet.');

			return true;
		}
		
		new AKDTU_notice('error', 'Tilladelsen til brugeroprettelse blev ikke oprettet.');

		return false;
	}
}
