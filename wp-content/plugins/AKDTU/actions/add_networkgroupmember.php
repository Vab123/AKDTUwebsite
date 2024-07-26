<?php

/**
 * @file Action to add a new networkgroup member to the system
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'add_networkgroupmember' && isset($_REQUEST['user'])){
		add_networkgroupmember($_REQUEST['user'], $_REQUEST['user-type']);
	}
}

/**
 * Add a new networkgroup member to the system
 * 
 * @param int $user_id User id of the new networkgroup member
 * @param string $user_type User type to set the user as
 * 
 * @return bool True if the networkgroup member was created successfully
 */
function add_networkgroupmember($user_id, $user_type){
	# Check if the apartment number is valid
	if (is_apartment_from_id($user_id) && add_user_role($user_id, 'networkgroupmember', $user_type)) {
		# Write success message to admin interface
		new AKDTU_notice('success','Netgruppemedlemmet blev oprettet');

		return true;
	}
	# Write error message to admin interface
	new AKDTU_notice('error','Netgruppemedlemmet blev ikke oprettet');

	return false;
}
