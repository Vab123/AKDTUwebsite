<?php

/**
 * @file Action to remove an old vicevært from the system
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'remove_vicevært' && isset($_REQUEST['user'])){
		remove_vicevært($_REQUEST['user']);
	}
}

/**
 * Remove an vicevært from the system
 * 
 * @param int $user_id User id of the old vicevært
 */
function remove_vicevært($user_id){
	# Role name for vicevært
	$vicevært_swmp_role = 'Vicevært';

	# Check if the user id is valid
	if ($user_id > 0) {
		# Get the SWPM user connected to user
		$swpm_user = SwpmMemberUtils::get_user_by_user_name( username_from_id( $user_id ) );

		$user_role = SwpmMembershipLevelUtils::get_membership_level_name_of_a_member($swpm_user->member_id);

		if ($user_role == $vicevært_swmp_role) {
			global $wpdb;
			
			# Delete SWPM user-profile
			$wpdb->query( 'DELETE FROM ' . $wpdb->prefix . "swpm_members_tbl WHERE member_id = " . $swpm_user->member_id );

			# Delete Wordpress user
			require_once dirname( WP_CONTENT_DIR ) . "/wp-admin/includes/user.php";
			wp_delete_user( $user_id, 0 );

			# Write success message to admin interface
			new AKDTU_notice('success','Viceværten blev fjernet');
		} else {
			# Write error message to admin interface
			new AKDTU_notice('error','Den valgte bruger er ikke vicevært! I stedet er denne ' . $user_role);
		}
	}
}
