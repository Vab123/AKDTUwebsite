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
		global $wpdb;
		global $AKDTU_USER_TYPES;

		# Get all possible membership levels
		$membership_levels = SwpmMembershipLevelUtils::get_all_membership_levels_in_array();
		
		# Find the index of the resident member level
		$new_membership_level = array_search($AKDTU_USER_TYPES['none']['user_level'], $membership_levels);

		# Set the level of the user to the resident member level
		SwpmMemberUtils::update_membership_level( $user_id, $new_membership_level );
		
		# Set the role of the Wordpress user to be a resident member
		$wp_user = get_user_by('id', $user_id );
		$wp_user->set_role($AKDTU_USER_TYPES['none']['user_role']);
		
		# Update old boardmember in the database
		$rows_changed = $wpdb->update($wpdb->prefix . 'AKDTU_boardmembers', array('end_datetime' => (new DateTime('now - 1 minute', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s')), array('apartment_number' => apartment_number_from_id($_REQUEST['user']), 'end_datetime' => '9999-12-31 23:59:59'));

		# Write success message to admin interface
		if ($rows_changed > 0) {
			new AKDTU_notice('success','Bestyrelsesmedlemmet blev fjernet');

			return true;
		}

		new AKDTU_notice('success','Bestyrelsesmedlemmet blev ikke fjernet');

		return false;
	}
}
