<?php

/**
 * @file Cron-job responsible for deleting users and sending information when residents move out
 */

/**
 * Cron-job responsible for deleting users for residents when they move out. Sends or echos information about the actions taken.
 * 
 * @param bool $debug Flag, for whether the users should actually be deleted (false), or if this is a test run to show sample results (true)
 */
function send_fjern_brugeradgang($debug = false) {

	# Get apartments moving in earlier than now that are not already reset
	$res = get_past_moves(['apartment_number'], null, null, 0);
	if ($debug && count($res) == 0) {
		$res = [2];
	}

	# Go through all moved residents
	foreach ($res as $apartment_num) {
		# Get user info
		$user_login = username_from_apartment_number($apartment_num);
		$user = get_user_by('login', $user_login);
		$user_id = $user->ID;

		# New values
		$new_pass = 'default_pass';
		$new_first_name = '';
		$new_last_name = '';
		$new_email = $user_login . '@akdtu.dk';

		# Get archive user info
		$archive_user = get_user_by('login', $user_login . '_archive');
		$archive_user_id = $archive_user->ID;

		# Replacements for email subject
		$subject_replaces = array(
			'#APT' => $apartment_num
		);

		# Replacements for email content
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
		
		# Check if this is an actual run, where the user should be deleted
		if (!$debug) {
			# Reset info for user
			reset_user_info($user_id, $new_pass, $new_first_name, $new_last_name, $new_email, $apartment_num, $debug);

			# Update database to show that the user has been reset
			update_user_permit($apartment_num, ['initial_reset' => 1]);
		}

		# Check if an email should be sent or echoed
		if (FJERNBRUGERADGANG_TO != '' || $debug) {
			# Send or echo email
			send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FJERNBRUGERADGANG');
		}
	}
}
