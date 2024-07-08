<?php

/**
 * @file Action to delete a rental of the common house
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == "delete_leje" && isset($_REQUEST['leje_event_id'])){
		delete_leje($_REQUEST['leje_event_id']);
	}
}

/**
 * Deletes a rental request
 * 
 * @param int $event_id Event id of the rental to be deleted
 * 
 * @return bool True if the rental was deleted successfully
 */
function delete_leje($event_id) {
	# Delete post
	if (delete_common_house_booking($event_id)) {
		# Write success message to admin interface
		new AKDTU_notice('success', 'Lejen er nu afvist.');

		return true;
	}

	new AKDTU_notice('error', 'Lejen blev ikke afvist.');

	return false;
}
