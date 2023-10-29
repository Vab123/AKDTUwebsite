<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'add_boardmember' && isset($_REQUEST['user'])){
		add_boardmember();
	}
}

function add_boardmember(){
	global $wpdb;
	if ($_REQUEST['user'] > 0) {
		$swpm_user = SwpmMemberUtils::get_user_by_user_name( username_from_apartment_number($_REQUEST['user']) );
		$swpm_user_memberid = $swpm_user->member_id;

		$membership_levels = SwpmMembershipLevelUtils::get_all_membership_levels_in_array();
		$temp_membership_level = array_search("Administrator" , $membership_levels);
		$new_membership_level = array_search("Beboerprofil til bestyrelsesmedlem" , $membership_levels);

		SwpmMemberUtils::update_membership_level( $swpm_user_memberid, $temp_membership_level );
		SwpmMemberUtils::update_membership_level( $swpm_user_memberid, $new_membership_level );

		$wp_user = get_user_by('login', username_from_apartment_number($_REQUEST['user']) );
		$wp_user->set_role('board_member');

		$wpdb->insert($wpdb->prefix . 'AKDTU_boardmembers',array('apartment_number' => $_REQUEST['user'], 'start_datetime' => (new DateTime('now',new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s'), 'end_datetime' => '9999-12-31 23:59:59'));

		new AKDTU_notice('success','Bestyrelsesmedlemmet blev oprettet');
	}
}
