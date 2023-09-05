<?php
require_once WP_PLUGIN_DIR . '/AKDTU/functions/notice.php';

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'remove_boardmember' && isset($_REQUEST['user'])){
		remove_boardmember();
	}
}

function remove_boardmember(){
	global $wpdb;
	if ($_REQUEST['user'] > 0) {
		$user_id = $_REQUEST['user'];

		$membership_levels = SwpmMembershipLevelUtils::get_all_membership_levels_in_array();
		$new_membership_level = array_search("Beboer" , $membership_levels);

		SwpmMemberUtils::update_membership_level( $user_id, $new_membership_level );
		
		$wp_user = get_user_by('id', $user_id );
		$wp_user->set_role('subscriber');
		
		$wpdb->update($wpdb->prefix . 'AKDTU_boardmembers',array('end_datetime' => (new DateTime('now',new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s')), array('apartment_number' => substr(get_user_by('id',$_REQUEST['user'])->user_login,4,3), 'end_datetime' => '9999-12-31 23:59:59'));

		new AKDTU_notice('success','Bestyrelsesmedlemmet blev fjernet');
	}
}
