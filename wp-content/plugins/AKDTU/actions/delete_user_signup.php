<?php

/**
 * @file Action to remove a permission for a resident to create a user on the website
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'delete_user_signup' && isset($_REQUEST['user']) && isset($_REQUEST['phone']) && isset($_REQUEST['takeover_time'])) {
		delete_user_signup($_REQUEST['user'], $_REQUEST['phone'], $_REQUEST['takeover_time']);
	}
}

/**
 * Remove a permit for a resident to create a user on the website
 * 
 * @param int $apartment_number Apartment number for the permit
 * @param string $phone_number Phone number for the permit
 * @param string $takeover_time Date and time for takeover of the user for the permit
 * 
 * @return bool True if the permit was removed successfully
 */
function delete_user_signup($apartment_number, $phone_number, $takeover_time){
	# Check if deletion was successful
	if (delete_user_creation_permit($apartment_number, $phone_number, $takeover_time)) {
		# Deletion succeeded. Write success message to admin interface
		new AKDTU_notice('success','Tilladelsen til brugeroprettelse blev slettet.');

		return true;
	} else {
		# Deletion failed. Write error message to admin interface
		new AKDTU_notice('error','Der blev ikke fundet nogen bruger med de givne oplysninger.');

		return false;
	}
}
