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
		global $wpdb;

		# Update old networkgroupmember in the database
		$rows_changed = $wpdb->update($wpdb->prefix . 'AKDTU_networkgroupmembers', array('end_datetime' => (new DateTime('now - 1 minute', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s')), array('apartment_number' => apartment_number_from_id($_REQUEST['user']), 'end_datetime' => '9999-12-31 23:59:59'));

		# Write success message to admin interface
		if ($rows_changed > 0) {
			new AKDTU_notice('success','Netgruppemedlemmet blev fjernet');

			return true;
		}

		new AKDTU_notice('success','Netgruppemedlemmet blev ikke fjernet');

		return false;
	}
}
