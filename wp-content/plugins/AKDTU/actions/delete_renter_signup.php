<?php

/**
 * @file Action to remove a permission for a temporary renter to create a user on the website
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'delete_renter_signup' && isset($_REQUEST['user']) && isset($_REQUEST['phone']) && isset($_REQUEST['start_time']) && isset($_REQUEST['end_time'])) {
		delete_renter_signup($_REQUEST['user'], $_REQUEST['phone'], $_REQUEST['start_time'], $_REQUEST['end_time']);
	}
}

/**
 * Delete a permit for a temporary renter to create a user on the website
 * 
 * @param int $apartment_number Apartment number for the permit
 * @param string $phone_number Phone number for the permit
 * @param string $start_time Start-date and -time for takeover of the user for the permit
 * @param string $end_time End-date and -time for takeover of the user for the permit
 * 
 * @return bool True if the permit was removed successfully
 */
function delete_renter_signup($apartment_number, $phone_number, $start_time, $end_time){
	# Check if deletion was successful
	if (delete_renter_creation_permit($apartment_number, $phone_number, $start_time, $end_time)) {
		# Deletion succeeded. Write success message to admin interface
		new AKDTU_notice('success', 'Den midlertidige lejer blev slettet.');

		return true;
	} else {
		# Deletion failed. Write error message to admin interface
		new AKDTU_notice('error', 'Der blev ikke fundet nogen midlertidig lejer med de givne oplysninger.');

		return false;
	}
}
