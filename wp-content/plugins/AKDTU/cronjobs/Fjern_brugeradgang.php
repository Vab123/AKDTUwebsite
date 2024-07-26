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
	global $wpdb;

	$res = $debug ? [ [ 'apartment_number' => 2, 'move_date' => (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s'), 'reset' => 0 ] ] : get_moves_to_reset();

	# Go through all moved residents
	foreach ($res as $move_info) {
		$apartment_num = $move_info["apartment_number"];

		# Get user info
		$user_logins = usernames_from_apartment_number($apartment_num);

		# Get archive user info
		$archive_user = get_user_by('login', archive_username_from_apartment_number($apartment_num));
		$archive_user_id = $archive_user->ID;

		foreach ($user_logins as $user_login) {
			$user = get_user_by('login', $user_login);
			$user_id = $user->ID;

			# Replacements for email subject
			$subject_replaces = array(
				'#APT' => $apartment_num
			);

			# Replacements for email content
			$content_replaces = array(
				'#APT' => $apartment_num,
				'#OLDMAIL' => $user->user_email,
				'#OLDFIRSTNAME' => $user->user_firstname,
				'#OLDLASTNAME' => $user->user_lastname,
				'#RENTALS' => delete_rentals($user_id, $archive_user_id, $debug),
				'#FUTURE_GARDENDAYS' => find_future_gardendays($user_id, $archive_user_id, $debug),
				'#PREVIOUS_GARDENDAYS' => delete_previous_gardendays($user_id, $archive_user_id, $debug)
			);
			
			# Check if this is an actual run, where the user should be deleted
			if (!$debug) {
				$swpm_user = SwpmMemberUtils::get_user_by_user_name( username_from_id($user_id) );
				wp_delete_user( $user_id, $archive_user_id );; //Deletes the WP User record

				$wpdb->query( 'DELETE FROM ' . $wpdb->prefix . "swpm_members_tbl WHERE member_id = {$swpm_user->member_id}" );
			}

			# Check if an email should be sent or echoed
			if (FJERNBRUGERADGANG_TO != '' || $debug) {
				# Send or echo email
				send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FJERNBRUGERADGANG');
			}
		}

		if (!$debug) {
			mark_move_as_done($apartment_num, new DateTime($move_info['move_date']));
		}
	}
}
