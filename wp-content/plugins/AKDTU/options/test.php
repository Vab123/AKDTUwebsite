<?php 

/**
 * @file Test page, currently unused
 */

function test_page() {
	test();
}

function test() {
	foreach (all_apartments() as $apartment) {
		$user = get_user_by("ID", id_from_apartment_number($apartment));

		$new_user_settings = array(
			'display_name' => (strlen($user->first_name) > 0 && strlen($user->last_name) > 0 ? $user->first_name . ' ' . $user->last_name : $user->user_login),
			'ID' => $user->ID,
		);

		// print_r($new_user_settings);
		// echo '<br><br>';
		// wp_update_user($new_user_settings);
	}
}

?>