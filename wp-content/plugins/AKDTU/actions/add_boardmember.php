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
 * @param int $user_id User id of the new board member
 * @param string $user_type User type to set the user as
 * 
 * @return bool True if the board member was created successfully
 */
function add_boardmember($user_id, $user_type){
	# Check if the apartment number is valid
	if (is_apartment_from_id($user_id) && add_user_role($user_id, 'boardmember', $user_type)) {
		# Write success message to admin interface
		new AKDTU_notice('success','Bestyrelsesmedlemmet blev oprettet');

		return true;
	}

	# Write error message to admin interface
	new AKDTU_notice('error','Bestyrelsesmedlemmet blev ikke oprettet');

	return false;
}
