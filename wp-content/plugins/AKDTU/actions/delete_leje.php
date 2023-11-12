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
 * Add a new board member to the system
 * 
 * @param int $event_id Event id of the rental to be deleted
 */
function delete_leje($event_id) {
	# Get event
	$event = new EM_Event($event_id,'event_id');

	# Create post-admin object
	$event_post_admin = new EM_Event_Post_Admin();

	# Delete event
	$event_post_admin->before_delete_post($event->post_id);
	$event_post_admin->trashed_post($event->post_id);

	# Delete post
	$result = wp_trash_post($event->post_id);

	# Write success message to admin interface
	new AKDTU_notice('success','Lejen er nu afvist.');
}
