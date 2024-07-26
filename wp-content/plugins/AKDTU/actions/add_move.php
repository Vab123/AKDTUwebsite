<?php

/**
 * @file Action to allow a resident to create a user on the website
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'add_move' && isset($_REQUEST['user']) && isset($_REQUEST['email']) && isset($_REQUEST['takeover_time'])) {
		allow_move($_REQUEST['user'], $_REQUEST['email'], $_REQUEST['takeover_time']);
	}
}

/**
 * Add a permit for a resident to create a user on the website
 * 
 * @param int $apartment_number Apartment number for the permit
 * @param string $email Email adress for the permit
 * @param string $takeover_time Date and time for takeover of the user for the permit
 * 
 * @return bool True if the permit was created successfully
 */
function allow_move($apartment_number, $email_string, $takeover_time) {
	$emails = array_map('trim', explode(",", $email_string));

	foreach ($emails as $email) {
		if (user_creation_permit_exists_where(["email" => $email, "reset" => 0])) {
			new AKDTU_notice('error', "Tilladelsen til brugeroprettelse for {$email} blev ikke oprettet, da der allerede er en tilladelse for denne email.");
		} else {
			if (create_user_creation_permit($apartment_number, $email, $takeover_time)) {
				new AKDTU_notice('success', "Tilladelsen til brugeroprettelse for {$email} blev oprettet.");
			}
			else {
				new AKDTU_notice('error', "Tilladelsen til brugeroprettelse for {$email} blev ikke oprettet.");
			}
		}
	}

	if (add_move($apartment_number, new DateTime($takeover_time, new DateTimeZone('Europe/Copenhagen')))) {
		new AKDTU_notice('success', "Overdragelsen blev oprettet.");
		return true;
	} else {
		new AKDTU_notice('error', "Tilladelsen til brugeroprettelse for {$email} blev ikke oprettet.");
		return false;
	}
}
