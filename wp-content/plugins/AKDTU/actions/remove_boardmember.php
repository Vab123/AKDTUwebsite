<?php

/**
 * @file Action to remove an old board member from the system
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'remove_boardmember' && isset($_REQUEST['user'])){
		remove_boardmember($_REQUEST['user']);
	}
}

/**
 * Add a new board member to the system
 * 
 * @param int $user_id User id of the old board member
 * 
 * @return bool True if the board member was removed successfully
 */
function remove_boardmember($user_id){
	# Check if the user id is valid
	if ($user_id > 0) {
		# Write success message to admin interface
		if (remove_user_role($user_id, 'boardmember')) {
			new AKDTU_notice('success','Bestyrelsesmedlemmet blev fjernet');

			return true;
		}

		new AKDTU_notice('success','Bestyrelsesmedlemmet blev ikke fjernet');

		return false;
	}

	return false;
}
