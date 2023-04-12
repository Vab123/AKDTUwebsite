<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'book_fælleshus' && isset($_REQUEST['start_date']) && isset($_REQUEST['end_date']) && isset($_REQUEST['type'])) {
		book_fælleshus();
	}
}

function book_fælleshus_beboer($event_owner, $title) {
	global $wpdb;
	$start_date = new DateTime($_REQUEST['start_date']);
	$end_date = new DateTime($_REQUEST['end_date']);

	if ($end_date <= $start_date) {
		new AKDTU_notice('error','Sluttidspunkt skal være før starttidspunkt.');
		return;
	}

	// Create Wordpress post
	$id = wp_insert_post( array('post_status' => 'publish', 'post_type' => 'event', 'post_title' => $title,'post_content' => '', 'post_date' => current_time("Y-m-d H:i:s"), 'post_author' => $event_owner->ID ) );

	// Protect event
	SwpmProtection::get_instance()->apply(array($id), 'custom_post')->save();
	$query = "SELECT id FROM " . $wpdb->prefix . "swpm_membership_tbl WHERE id !=1 ";
	$level_ids = $wpdb->get_col($query);
	foreach ($level_ids as $level) {
		SwpmPermission::get_instance($level)->apply(array($id), 'custom_post')->save();
	}

	// Set post language
	pll_set_post_language($id,'da_DK');

	// Set event details
	$event = new EM_Event($id,'post_id');

	$event->__set('event_start_date', $start_date->format("Y-m-d")); // Start date of event
	$event->__set('event_end_date', $end_date->format("Y-m-d")); // End date of event
	$event->__set('event_start_time', $start_date->format("H:i:s")); // Start time of event
	$event->__set('event_end_time', $end_date->format("H:i:s")); // End time of event
	$event->__set('event_start', $start_date->format("Y-m-d") . ' ' . $start_date->format("H:i:s")); // Start of event
	$event->__set('event_end', $end_date->format("Y-m-d") . ' ' . $end_date->format("H:i:s")); // End of event
	$event->__set('start_date', $start_date->format("Y-m-d")); // Start date of event
	$event->__set('end_date', $end_date->format("Y-m-d")); // End date of event
	$event->__set('start_time', $start_date->format("H:i:s")); // Start time of event
	$event->__set('end_time', $end_date->format("H:i:s")); // End time of event

	$event->__set('location_id', 0); // Location ID
	$event->__set('event_spaces', NULL); // Total amount of spaces on event
	$event->__set('event_private', 0); // Boolean, is event private
	$event->__set('event_language', 'da_DK'); // Language of event
	$event->__set('blog_id',get_current_blog_id()); // ID of blog, for multi-site installations
	$event->__set('event_owner', $event_owner->ID); // Language of event
	$event->set_status(1, true); // Publish event

	update_post_meta($event->post_id, '_event_approvals_count', 1); // Set approval status

	if ($event->validate() && $event->save_meta() && $event->save()){ // Save event and event metadata
		new AKDTU_notice('success','Fælleshuset er nu reserveret.');
		$success = true;
	} else {
		new AKDTU_notice('error','Fælleshuset kunne ikke reserveres.');
		foreach ($event->errors as $error) {
			new AKDTU_notice('warning',$error);
		}
		$success = false;
	}

	return $success;
}

function book_fælleshus_bestyrelse($event_owner, $title_da, $title_en) {
	global $wpdb;
	$start_date = new DateTime($_REQUEST['start_date']);
	$end_date = new DateTime($_REQUEST['end_date']);

	if ($end_date <= $start_date) {
		new AKDTU_notice('error','Sluttidspunkt skal være før starttidspunkt.');
		return;
	}

	// Create Wordpress posts
	$id_da = wp_insert_post( array('post_status' => 'publish', 'post_type' => 'event', 'post_title' => $title_da,'post_content' => '', 'post_date' => current_time("Y-m-d H:i:s"), 'post_author' => $event_owner->ID ) );
	$id_en = wp_insert_post( array('post_status' => 'publish', 'post_type' => 'event', 'post_title' => $title_en,'post_content' => '', 'post_date' => current_time("Y-m-d H:i:s"), 'post_author' => $event_owner->ID ) );

	// Protect event
	SwpmProtection::get_instance()->apply(array($id_da), 'custom_post')->save();
	SwpmProtection::get_instance()->apply(array($id_en), 'custom_post')->save();
	$query = "SELECT id FROM " . $wpdb->prefix . "swpm_membership_tbl WHERE id !=1 ";
	$level_ids = $wpdb->get_col($query);
	foreach ($level_ids as $level) {
		SwpmPermission::get_instance($level)->apply(array($id_da), 'custom_post')->save();
		SwpmPermission::get_instance($level)->apply(array($id_en), 'custom_post')->save();
	}

	// Set post language
	pll_set_post_language($id_da,'da_DK');
	pll_set_post_language($id_en,'en_US');

	// Set Danish event details
	$event_da = new EM_Event($id_da,'post_id');

	$event_da->__set('event_start_date', $start_date->format("Y-m-d")); // Start date of event
	$event_da->__set('event_end_date', $end_date->format("Y-m-d")); // End date of event
	$event_da->__set('event_start_time', $start_date->format("H:i:s")); // Start time of event
	$event_da->__set('event_end_time', $end_date->format("H:i:s")); // End time of event
	$event_da->__set('event_start', $start_date->format("Y-m-d") . ' ' . $start_date->format("H:i:s")); // Start of event
	$event_da->__set('event_end', $end_date->format("Y-m-d") . ' ' . $end_date->format("H:i:s")); // End of event
	$event_da->__set('start_date', $start_date->format("Y-m-d")); // Start date of event
	$event_da->__set('end_date', $end_date->format("Y-m-d")); // End date of event
	$event_da->__set('start_time', $start_date->format("H:i:s")); // Start time of event
	$event_da->__set('end_time', $end_date->format("H:i:s")); // End time of event

	$event_da->__set('location_id', 0); // Location ID
	$event_da->__set('event_spaces', NULL); // Total amount of spaces on event
	$event_da->__set('event_private', 0); // Boolean, is event private
	$event_da->__set('event_language', 'da_DK'); // Language of event
	$event_da->__set('blog_id',get_current_blog_id()); // ID of blog, for multi-site installations
	$event_da->__set('event_owner', $event_owner->ID); // Language of event
	$event_da->set_status(1, true); // Publish event

	update_post_meta($event_da->post_id, '_event_approvals_count', 1); // Set approval status

	// Set English event details
	$event_en = new EM_Event($id_en,'post_id');

	$event_en->__set('event_start_date', $start_date->format("Y-m-d")); // Start date of event
	$event_en->__set('event_end_date', $end_date->format("Y-m-d")); // End date of event
	$event_en->__set('event_start_time', $start_date->format("H:i:s")); // Start time of event
	$event_en->__set('event_end_time', $end_date->format("H:i:s")); // End time of event
	$event_en->__set('event_start', $start_date->format("Y-m-d") . ' ' . $start_date->format("H:i:s")); // Start of event
	$event_en->__set('event_end', $end_date->format("Y-m-d") . ' ' . $end_date->format("H:i:s")); // End of event
	$event_en->__set('start_date', $start_date->format("Y-m-d")); // Start date of event
	$event_en->__set('end_date', $end_date->format("Y-m-d")); // End date of event
	$event_en->__set('start_time', $start_date->format("H:i:s")); // Start time of event
	$event_en->__set('end_time', $end_date->format("H:i:s")); // End time of event

	$event_en->__set('location_id', 0); // Location ID
	$event_en->__set('event_spaces', NULL); // Total amount of spaces on event
	$event_en->__set('event_private', 0); // Boolean, is event private
	$event_en->__set('event_language', 'en_US'); // Language of event
	$event_en->__set('blog_id',get_current_blog_id()); // ID of blog, for multi-site installations
	$event_en->__set('event_owner', $event_owner->ID); // Language of event
	$event_en->set_status(1, true); // Publish event

	update_post_meta($event_en->post_id, '_event_approvals_count', 1); // Set approval status

	if ($event_da->validate() && $event_da->save_meta() && $event_da->save() && $event_en->validate() && $event_en->save_meta() && $event_en->save()){ // Save event and event metadata
		pll_save_post_translations( array('en'  => $id_en, 'da' => $id_da ) );
		new AKDTU_notice('success','Fælleshuset er nu reserveret.');
		$success = true;
	} else {
		new AKDTU_notice('error','Fælleshuset kunne ikke reserveres.');
		foreach ($event_da->errors as $error) {
			new AKDTU_notice('warning','DA: ' . $error);
		}
		foreach ($event_en->errors as $error) {
			new AKDTU_notice('warning','EN: ' . $error);
		}
		$success = false;
	}

	return $success;
}

function book_fælleshus() {
	global $wpdb;

	if ($_REQUEST['type'] == 'bestyrelse') {
		$event_owner = wp_get_current_user();
		$title_da = $_REQUEST['name_da'];
		$title_en = $_REQUEST['name_en'];

		return book_fælleshus_bestyrelse($event_owner, $title_da, $title_en);
	} elseif ($_REQUEST['type'] == 'beboer') {
		$event_owner = get_user_by('login', 'lejl' . str_pad($_REQUEST['user'],3,"0",STR_PAD_LEFT));
		$title = '#_RENTAL_BEFORE_APARTMENTNUM ' . str_pad($_REQUEST['user'],3,"0",STR_PAD_LEFT) . ' #_RENTAL_AFTER_APARTMENTNUM';

		return book_fælleshus_beboer($event_owner, $title);
	}
}
