<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == "delete_leje" && isset($_REQUEST['leje_event_id'])){
		delete_leje();
	}
}

function delete_leje() {
	$event_id = $_REQUEST['leje_event_id'];
	$event = new EM_Event($event_id,'event_id');
	$event_post_admin = new EM_Event_Post_Admin();
	$event_post_admin->before_delete_post($event->post_id);
	$event_post_admin->trashed_post($event->post_id);
	$result = wp_trash_post($event->post_id);

	new AKDTU_notice('success','Lejen er nu afvist.');
}
