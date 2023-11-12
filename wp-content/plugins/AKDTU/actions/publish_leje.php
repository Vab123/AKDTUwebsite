<?php

/**
 * @file Action to approve a request to rent the common house
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == "approve_leje" && isset($_REQUEST['leje_event_id'])){
		publish_leje($_REQUEST['leje_event_id']);
	}
}

/**
 * Approve a rental request
 * 
 * @param int $event_id Event id of the rental to be approved
 */
function publish_leje($event_id) {
	# Find the correct event
	$event = new EM_Event($event_id,'event_id');
	
	# Publish event
	$event->set_status(1, true);

	# Save changes
	$event->save();

	# Success. Write success message to admin interface
	new AKDTU_notice('success','Lejen er nu godkendt.');
}
