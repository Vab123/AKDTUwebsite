<?php 
	require_once WP_PLUGIN_DIR . '/AKDTU/definitions.php';

	function test() {
		
		// global $wpdb;

		// for ($floor = 0; $floor <= 2; $floor++) {
		// 	for ($apartment = 1; $apartment <= 24; $apartment++) {
		// 		if ($floor == 1 && $apartment == 15) {
		// 			continue;
		// 		}

		// 		$apartment_num = $floor * 100 + $apartment;

		// 		$userdata = array(
		// 			'user_pass' => 'default_pass',
		// 			'user_login' => 'lejl' . str_pad($apartment_num,3,"0",STR_PAD_LEFT) . '_lejer',
		// 			'user_nicename' => 'lejl' . str_pad($apartment_num,3,"0",STR_PAD_LEFT) . '_lejer',
		// 			'user_email' => 'lejl' . str_pad($apartment_num,3,"0",STR_PAD_LEFT) . '_lejer@akdtu.dk',
		// 			'first_name' => 'Midlertidig lejer,',
		// 			'last_name' => 'lejl' . str_pad($apartment_num,3,"0",STR_PAD_LEFT),
		// 			'role' => 'subscriber'
		// 		);

		// 		wp_insert_user($userdata);

		// 		$all_memberships = $wpdb->get_results( $wpdb->prepare('SELECT id,alias FROM wp_swpm_membership_tbl') );
		// 		$membership_level_id = array_filter($all_memberships,function($a){return $a->alias == "Midlertidig lejer";});
		// 		$membership_level_id = $membership_level_id[array_keys($membership_level_id)[0]]->id;

		// 		$member_data = array(
		// 			'user_name' => $userdata->user_login,
		// 			'first_name' => $userdata->first_name,
		// 			'last_name' => $userdata->last_name,
		// 			'password' => SwpmUtils::encrypt_password(trim($userdata->user_pass)),
		// 			'membership_level' => $membership_level_id,
		// 			'account_state' => 'active',
		// 			'email' => $userdata->user_email,
		// 			'gender' => 'not specified',
		// 			'flags' => '1'
		// 		);
		// 		$wpdb->insert('wp_swpm_members_tbl', $member_data);
		// 	}
		// }

		// echo $wpdb->query($wpdb->prepare('UPDATE wp_swpm_members_tbl SET membership_level=%d WHERE first_name="%s"',$membership_level_id,'Midlertidig lejer,'));

		

		// for ($floor = 0; $floor <= 2; $floor++) {
		// 	for ($apartment = 1; $apartment <= 24; $apartment++) {

		// 		$apartment_num = $floor * 100 + $apartment;

		// 		$userdata = array(
		// 			'user_pass' => 'default_pass',
		// 			'user_login' => 'lejl' . str_pad($apartment_num,3,"0",STR_PAD_LEFT) . '_archive',
		// 			'user_nicename' => 'lejl' . str_pad($apartment_num,3,"0",STR_PAD_LEFT) . '_archive',
		// 			'user_email' => 'lejl' . str_pad($apartment_num,3,"0",STR_PAD_LEFT) . '_archive@akdtu.dk',
		// 			'first_name' => 'Tidligere beboer,',
		// 			'last_name' => 'lejl' . str_pad($apartment_num,3,"0",STR_PAD_LEFT),
		// 			'role' => 'subscriber'
		// 		);

		// 		wp_insert_user($userdata);

		// 		$all_memberships = $wpdb->get_results( $wpdb->prepare('SELECT id,alias FROM wp_swpm_membership_tbl') );
		// 		$membership_level_id = array_filter($all_memberships,function($a){return $a->alias == "Archive bruger";});
		// 		$membership_level_id = $membership_level_id[array_keys($membership_level_id)[0]]->id;

		// 		$member_data = array(
		// 			'user_name' => $userdata->user_login,
		// 			'first_name' => $userdata->first_name,
		// 			'last_name' => $userdata->last_name,
		// 			'password' => SwpmUtils::encrypt_password(trim($userdata->user_pass)),
		// 			'membership_level' => $membership_level_id,
		// 			'account_state' => 'active',
		// 			'email' => $userdata->user_email,
		// 			'gender' => 'not specified',
		// 			'flags' => '1'
		// 		);
		// 		$wpdb->insert('wp_swpm_members_tbl', $member_data);
		// 	}
		// }
		// echo $wpdb->query($wpdb->prepare('UPDATE wp_swpm_members_tbl SET membership_level=%d WHERE first_name="%s"',$membership_level_id,'Tidligere beboer,'));

		require_once WP_PLUGIN_DIR . '/AKDTU/functions/fælleshus_vlan_set.php';
		print_r(get_fælleshus_vlan());
	}
