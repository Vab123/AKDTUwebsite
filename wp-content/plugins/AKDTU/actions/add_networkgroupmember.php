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
 * @param int $apartment_number Apartment number of the new networkgroup member
 * @param string $user_type User type to set the user as
 * 
 * @return bool True if the networkgroup member was created successfully
 */
function add_networkgroupmember($apartment_number, $user_type){
	# Check if the apartment number is valid
	if ($apartment_number > 0) {
		global $wpdb;
		global $KNET_USER_TYPES;

		$user_type = $KNET_USER_TYPES[$user_type]['id'];

		# Insert new networkgroupmember into the database
		$inserted = $wpdb->insert($wpdb->prefix . 'AKDTU_networkgroupmembers',array('apartment_number' => $apartment_number, 'start_datetime' => (new DateTime('now',new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s'), 'end_datetime' => '9999-12-31 23:59:59', 'member_type' => $user_type));

		if ($inserted) {
			# Write success message to admin interface
			new AKDTU_notice('success','Netgruppemedlemmet blev oprettet');

			return true;
		}
		else {
			# Write error message to admin interface
			new AKDTU_notice('error','Netgruppemedlemmet blev ikke oprettet');

			return true;
		}
	}
}
