<?php

/**
 * @file Action to allow a person to create a user on the website
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'allow_user_signup' && isset($_REQUEST['user']) && isset($_REQUEST['email']) && isset($_REQUEST['takeover_time'])) {
		allow_user_signup($_REQUEST['user'], $_REQUEST['email'], $_REQUEST['takeover_time']);
	}
}

/**
 * Add a permit for a temporary renter to create a user on the website
 * 
 * @param int $apartment_number Apartment number for the permit
 * @param string $email Email adress for the permit
 * @param string $takeover_time Timestamp of when it is permitted to create a user
 * 
 * @return bool True if the permit was created successfully
 */
function allow_user_signup($apartment_number, $email, $takeover_time) {
	# Check if there already exists a permit for user creation already exists
	if (user_creation_permit_exists_where(['email' => $email, 'reset' => 0])) {
		# A permit already exists. Output error message and return
		new AKDTU_notice('error', 'Tilladelsen kunne ikke oprettes. Check om der allerede findes en tilladelse i den samme lejlighed.');

		return false;
	} else {
		# A permit does not exist. Create new permit

		# Write success message to admin interface
		if (create_user_creation_permit($apartment_number, $email, (new DateTime($takeover_time, new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s'))) {
			new AKDTU_notice('success', 'Tilladelsen blev oprettet.');

			return true;
		}
		
		new AKDTU_notice('error', 'Tilladelsen blev ikke oprettet.');

		return false;
	}
}
