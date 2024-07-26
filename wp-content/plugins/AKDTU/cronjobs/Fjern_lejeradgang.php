<?php

/**
 * @file Cron-job responsible for deleting users and sending information when temporary renters move out
 */

/**
 * Cron-job responsible for deleting users for temporary renters when they move out. Sends or echos information about the actions taken.
 * 
 * @param bool $debug Flag, for whether the users should actually be deleted (false), or if this is a test run to show sample results (true)
 */
function send_fjern_lejeradgang($debug = false) {
	global $wpdb;

	# Get current time
	$current_time = new DateTime("now", new DateTimeZone('Europe/Copenhagen'));
	$current_time = $current_time->format("Y-m-d H:i:s");

	# Get apartments moving in earlier than now that are not already reset
	$res = ($debug ? [ [ "apartment_number" => 2, "start_time" => (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s'), "end_time" => (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s'), "initial_reset" => 0, "initial_takeover" => 0, ] ] : get_expired_renters(['apartment_number']));

	# Go through all moved renters
	foreach ($res as $renter_info) {
		$apartment_num = $renter_info["apartment_number"];
		
		# Get user info
		$user_login = "lejl" . padded_apartment_number_from_apartment_number($apartment_num) . "_lejer";
		$user = get_user_by('login', $user_login);
		$user_id = $user->ID;

		# Get archive user info
		$archive_user = get_user_by("user_login", archive_username_from_apartment_number($apartment_num));
		$archive_user_id = $archive_user->ID;

		# Replacements for email subject
		$subject_replaces = [
			'#APT' => $apartment_num
		];

		# Replacements for email content
		$content_replaces = [
			'#APT' => $apartment_num,
			'#OLDMAIL' => $user->user_email,
			'#OLDFIRSTNAME' => $user->user_firstname,
			'#OLDLASTNAME' => $user->user_lastname,
			'#RENTALS' => delete_rentals($user_id, $archive_user_id, $debug),
			'#FUTURE_GARDENDAYS' => find_future_gardendays($user_id, $archive_user_id, $debug),
			'#PREVIOUS_GARDENDAYS' => delete_previous_gardendays($user_id, $archive_user_id, $debug)
		];

		# Check if this is an actual run, where the user should be deleted
		if (!$debug) {
			# Update database to show that the user has been reset
			$swpm_user = SwpmMemberUtils::get_user_by_user_name( username_from_id($user_id) );
			wp_delete_user( $user_id, $archive_user_id );; //Deletes the WP User record

			$wpdb->query( 'DELETE FROM ' . $wpdb->prefix . "swpm_members_tbl WHERE member_id = {$swpm_user->member_id}" );
		}

		# Check if an email should be sent or echoed
		if (FJERNLEJERADGANG_TO != '' || $debug) {
			# Send or echo email
			send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FJERNLEJERADGANG');
		}
	}
}
