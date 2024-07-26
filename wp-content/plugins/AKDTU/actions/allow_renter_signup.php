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
	# Check if there already exists a permit for user creation already exists
	if (renter_creation_permit_exists_where(["apartment_number" => $apartment_number])) {
		# A permit already exists. Output error message and return
		new AKDTU_notice('error', 'Den midlertidige lejer kunne ikke oprettes. Check om der allerede findes en midlertidig lejer i den samme lejlighed.');

		return false;
	} else {
		# A permit does not exist. Create new permit

		# Write success message to admin interface
		if (create_renter_creation_permit($apartment_number, $phone_number, $start_time, $end_time)) {
			new AKDTU_notice('success', 'Den midlertidige lejer blev oprettet.');

			return true;
		}
		
		new AKDTU_notice('error', 'Den midlertidige lejer blev ikke oprettet.');

		return false;
	}
}
