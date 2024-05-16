<?php

/**
 * @file Action to add a new vicevært to the system
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'add_vicevært' && isset($_REQUEST['first_name']) && isset($_REQUEST['last_name']) && isset($_REQUEST['username']) && isset($_REQUEST['email'])){
		add_vicevært($_REQUEST['first_name'], $_REQUEST['last_name'], $_REQUEST['username'], $_REQUEST['email']);
	}
}

/**
 * Add a new vicevært to the system
 * 
 * @param string $first_name First name of the new vicevært
 * @param string $last_name Last name of the new vicevært
 * @param string $username Username of the new vicevært
 * @param string $email Email address of the new vicevært
 * 
 * @return bool True if the vicevært was created successfully
 */
function add_vicevært($first_name, $last_name, $username, $email){
	global $AKDTU_USER_TYPES;

	# Check if a user already exists with that email
	if (email_exists( $email ) == false) {
		# Name of SWPM level for vicevært
		$vicevært_level_name = $AKDTU_USER_TYPES['vicevært']['user_level'];
		$vicevært_role = $AKDTU_USER_TYPES['vicevært']['user_role'];

		$default_password = 'default_password';
		
		# Get SWPM role for new user
		$all_membership_levels = SwpmMembershipLevelUtils::get_all_membership_levels_in_array();
		$vicevært_level = array_search($vicevært_level_name , $all_membership_levels);

		# Check if the user level was actually found
		if ($vicevært_level === false) {
			new AKDTU_notice('error', 'Viceværternes rolle blev ikke fundet. Viceværten er ikke oprettet.');

			return false;
		}
		
		# Wordpress user info
		$wp_user_info = array(
			'user_nicename' => implode('-', explode(' ', $username)),
			'display_name' => $username,
			'user_email' => $email,
			'nickname' => $username,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'user_login' => $username,
			'password' => $default_password,
			'user_registered' => date('Y-m-d H:i:s'),
		);

		# Create wordpress user
		$new_user_wp_id = SwpmUtils::create_wp_user($wp_user_info);
		$wp_user = get_user_by("ID", $new_user_wp_id);

		# Set the role of the new wordpress user
		$wp_user->set_role($vicevært_role);

		// # SWPM user info
		$member_info = SwpmTransfer::$default_fields;
		$member_info['first_name'] = $first_name;
		$member_info['last_name'] = $last_name;
		$member_info['user_name'] = $username;
		$member_info['email'] = $email;
		$member_info['membership_level'] = $vicevært_level;
		$member_info['password'] = get_user_by('ID', $new_user_wp_id)->user_pass;
		$member_info['last_accessed'] = date('Y-m-d H:i:s');

		# Get member id of the new user
		$swpm_user_memberid = SwpmMemberUtils::get_user_by_email($email)->member_id;

		# Set the level of the apartment user to the vicevært level
		SwpmMemberUtils::update_membership_level( $swpm_user_memberid, $vicevært_level );

		if ($new_user_wp_id != false) {
			# Success. Write success message to admin interface
			new AKDTU_notice('success','Vicevært med brugernavn ' . $member_info['user_name'] . ' oprettet.');

			return true;
		}

		new AKDTU_notice('error', 'Viceværten kunne ikke oprettes.');

		return false;
	}
}

?>