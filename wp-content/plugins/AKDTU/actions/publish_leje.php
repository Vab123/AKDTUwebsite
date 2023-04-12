<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == "approve_leje" && isset($_REQUEST['leje_event_id'])){
		publish_leje();
	}
}

function publish_leje() {
	$event_id = $_REQUEST['leje_event_id'];
	$event = new EM_Event($event_id,'event_id');
	$event->set_status(1,true);
	$event->save();

	new AKDTU_notice('success','Lejen er nu godkendt.');
}
