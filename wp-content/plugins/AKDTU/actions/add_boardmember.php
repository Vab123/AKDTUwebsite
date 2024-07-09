<?php

/**
 * @file Action to add a new board member to the system
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'add_boardmember' && isset($_REQUEST['user'])){
		add_boardmember($_REQUEST['user'], $_REQUEST['user-type']);
	}
}

/**
 * Add a new board member to the system
 * 
 * @param int $apartment_number Apartment number of the new board member
 * @param string $user_type User type to set the user as
 * 
 * @return bool True if the board member was created successfully
 */
function add_boardmember($apartment_number, $user_type){
	# Check if the apartment number is valid
	if ($apartment_number > 0) {
		if (add_user_role(id_from_apartment_number($apartment_number), 'boardmember', $user_type)) {
			# Write success message to admin interface
			new AKDTU_notice('success','Bestyrelsesmedlemmet blev oprettet');

			return true;
		}
		else {
			# Write error message to admin interface
			new AKDTU_notice('error','Bestyrelsesmedlemmet blev ikke oprettet');

			return true;
		}
	}
}
