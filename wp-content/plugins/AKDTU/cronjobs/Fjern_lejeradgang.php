<?php

function send_fjern_lejeradgang($debug = false) {
	require_once WP_PLUGIN_DIR . '/AKDTU/definitions.php';

	if (FJERNLEJERADGANG_TO != '' || $debug) {
		require_once WP_PLUGIN_DIR . '/AKDTU/functions/delete_users.php';
		require_once WP_PLUGIN_DIR . '/AKDTU/functions/send_mail.php';

		global $wpdb;

		// Get current time
		$current_time = new DateTime("now", new DateTimeZone('Europe/Copenhagen'));
		$current_time = $current_time->format("Y-m-d H:i:s");

		// Get apartments moving in earlier than now that are not already reset
		if ($debug) {
			$res = array(2);
		} else {
			$res = $wpdb->get_col('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_rentercreation WHERE initial_reset = 0 AND end_time <= "' . $current_time . '"');
		}

		foreach ($res as $apartment_num) {
			$realuser_login = 'lejl' . str_pad($apartment_num, 3, "0", STR_PAD_LEFT);
			$user_login = $realuser_login . '_lejer';
			$user = get_user_by('login', $user_login);
			$user_id = $user->ID;

			// New values
			$new_pass = 'default_pass';
			$new_first_name = 'Midlertidig lejer,';
			$new_last_name = $realuser_login;
			$new_email = $user_login . '@akdtu.dk';

			$archive_user = get_user_by('login', $realuser_login . '_archive');
			$archive_user_id = $archive_user->ID;

			$subject_replaces = array(
				'#APT' => $apartment_num
			);

			$content_replaces = array(
				'#APT' => $apartment_num,
				'#NEWMAIL' => ($new_email == '' ? '(tomt)' : $new_email),
				'#NEWFIRSTNAME' => ($new_first_name == '' ? '(tomt)' : $new_first_name),
				'#NEWLASTNAME' => ($new_last_name == '' ? '(tomt)' : $new_last_name),
				'#OLDMAIL' => $user->user_email,
				'#OLDFIRSTNAME' => $user->user_firstname,
				'#OLDLASTNAME' => $user->user_lastname,
				'#RENTALS' => delete_rentals($user_id, $archive_user_id, $debug),
				'#FUTURE_GARDENDAYS' => find_future_gardendays($user_id, $archive_user_id, $debug),
				'#PREVIOUS_GARDENDAYS' => delete_previous_gardendays($user_id, $archive_user_id, $debug)
			);

			reset_user_info($user_id, $new_pass, $new_first_name, $new_last_name, $new_email, $apartment_num, $debug);

			if (!$debug) {
				$wpdb->update($wpdb->prefix . 'swpm_allowed_rentercreation', array('initial_reset' => 1), array('apartment_number' => $apartment_num));
			}

			send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FJERNLEJERADGANG');
		}
	}
}
