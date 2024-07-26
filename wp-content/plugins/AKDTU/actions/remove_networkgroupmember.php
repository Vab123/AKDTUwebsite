<?php

/**
 * @file Action to remove an old networkgroup member from the system
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'remove_networkgroupmember' && isset($_REQUEST['user'])){
		remove_networkgroupmember($_REQUEST['user']);
	}
}

/**
 * Add a new networkgroup member to the system
 * 
 * @param int $user_id User id of the old networkgroup member
 * 
 * @return bool True if the networkgroup member was removed successfully
 */
function remove_networkgroupmember($user_id){
	# Check if the user id is valid
	if ($user_id > 0) {
		# Write success message to admin interface
		if (remove_user_role($user_id, 'networkgroupmember')) {
			new AKDTU_notice('success','Netgruppemedlemmet blev fjernet');

			return true;
		}

		new AKDTU_notice('success','Netgruppemedlemmet blev ikke fjernet');

		return false;
	}

	return false;
}
