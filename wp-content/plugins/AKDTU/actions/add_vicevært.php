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
	global $wpdb;

	# Check if a user already exists with that email
	if (email_exists( $email ) == false) {
		# Name of SWPM level for vicevært
		$vicevært_level_name = 'Vicevært';
		
		# Get SWPM role for new user
		$all_membership_levels = SwpmMembershipLevelUtils::get_all_membership_levels_in_array();
		$vicevært_level = array_search($vicevært_level_name , $all_membership_levels);

		# SWPM user info
		$member_info = SwpmTransfer::$default_fields;
		$member_info['first_name'] = $first_name;
		$member_info['last_name'] = $last_name;
		$member_info['user_name'] = $username;
		$member_info['email'] = $email;
		$member_info['membership_level'] = $vicevært_level;
		$member_info['plain_password'] = 'default_password';

		# Create SWPM user
		if (!$wpdb->insert($wpdb->prefix . 'swpm_members_tbl', $member_info)) {
			new AKDTU_notice('error', 'Viceværten kunne ikke oprettes.');

			return false;
		}

		# Get wordpress role for new user
		$user_role = $wpdb->get_var('SELECT role FROM ' . $wpdb->prefix . 'swpm_membership_tbl WHERE id = ' . $member_info['membership_level']);
		
		# Wordpress user info
		$wp_user_info = array(
			'user_nicename' => implode('-', explode(' ', $member_info['user_name'])),
			'display_name' => $member_info['user_name'],
			'user_email' => $member_info['email'],
			'nickname' => $member_info['user_name'],
			'first_name' => $member_info['first_name'],
			'last_name' => $member_info['last_name'],
			'user_login' => $member_info['user_name'],
			'password' => $member_info['plain_password'],
			'role' => $user_role,
			'user_registered' => date('Y-m-d H:i:s'),
		);

		# Create wordpress user
		$new_user_wp_id = SwpmUtils::create_wp_user($wp_user_info);
		

		
		# Get user member-id of newly created user
		$query = 'SELECT member_id from ' . $wpdb->prefix . 'swpm_members_tbl WHERE user_name = "' . $member_info['user_name'] . '" LIMIT 1';
		$swpm_user_memberid = $wpdb->get_var($query);

		# Set the level of the apartment user to the vicevært level
		SwpmMemberUtils::update_membership_level( $swpm_user_memberid, $vicevært_level );

		if ($new_user_wp_id != false) {
			# Success. Write success message to admin interface
			new AKDTU_notice('success','Vicevært med brugernavn ' . $member_info['user_name'] . ' oprettet');

			return true;
		}

		new AKDTU_notice('error', 'Viceværten kunne ikke oprettes.');

		return false;
	}
}

?>