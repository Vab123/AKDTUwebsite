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
		global $wpdb;
		global $AKDTU_USER_TYPES;

		$user_type = $AKDTU_USER_TYPES[$user_type]['id'];
		$user_level = $AKDTU_USER_TYPES[$user_type]['user_level'];
		$user_role = $AKDTU_USER_TYPES[$user_type]['user_role'];

		# Get the SWPM member corresponding to the apartment
		$swpm_user = SwpmMemberUtils::get_user_by_user_name( username_from_apartment_number($apartment_number) );

		# Get the SWPM member id corresponding to the apartment
		$swpm_user_memberid = $swpm_user->member_id;

		# Get all possible membership levels
		$membership_levels = SwpmMembershipLevelUtils::get_all_membership_levels_in_array();

		# Find the index of the administrator level, which is used as a temporary level
		$temp_membership_level = array_search("Administrator" , $membership_levels);

		# Find the index of the board member level
		$new_membership_level = array_search($user_level , $membership_levels);

		# Set the level of the apartment user to the temporary level
		SwpmMemberUtils::update_membership_level( $swpm_user_memberid, $temp_membership_level );

		# Set the level of the apartment user to the board member level
		SwpmMemberUtils::update_membership_level( $swpm_user_memberid, $new_membership_level );

		# Get Wordpress user corresponding to the apartment
		$wp_user = get_user_by('login', username_from_apartment_number($apartment_number) );

		# Set the role of the Wordpress user to be a board member
		$wp_user->set_role($user_role);

		# Insert new boardmember into the database
		$inserted = $wpdb->insert($wpdb->prefix . 'AKDTU_boardmembers',array('apartment_number' => $apartment_number, 'start_datetime' => (new DateTime('now',new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s'), 'end_datetime' => '9999-12-31 23:59:59', 'member_type' => $user_type));

		if ($inserted) {
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
