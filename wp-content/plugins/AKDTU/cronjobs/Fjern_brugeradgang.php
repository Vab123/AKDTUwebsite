<?php

function send_fjern_brugeradgang($debug = false) {
	global $wpdb;

	if (FJERNBRUGERADGANG_TO != '' || $debug) {

		// Get current time
		$current_time = new DateTime("now", new DateTimeZone('Europe/Copenhagen'));
		$current_time = $current_time->format("Y-m-d H:i:s");

		// Get apartments moving in earlier than now that are not already reset
		$res = $wpdb->get_col('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_membercreation WHERE initial_reset = 0 AND allow_creation_date <= "' . $current_time . '"');
		if ($debug && count($res) == 0) {
			$res = array(2);
		}

		foreach ($res as $apartment_num) {
			$user_login = username_from_apartment_number($apartment_num);
			$user = get_user_by('login', $user_login);
			$user_id = $user->ID;

			// New values
			$new_pass = 'default_pass';
			$new_first_name = '';
			$new_last_name = '';
			$new_email = $user_login . '@akdtu.dk';

			$archive_user = get_user_by('login', $user_login . '_archive');
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
				$wpdb->update($wpdb->prefix . 'swpm_allowed_membercreation', array('initial_reset' => 1), array('apartment_number' => $apartment_num));
			}

			send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FJERNBRUGERADGANG');
		}
	}
}
