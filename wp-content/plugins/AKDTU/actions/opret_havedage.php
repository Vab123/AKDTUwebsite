<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'add_havedag' && isset($_REQUEST['danish_name']) && isset($_REQUEST['danish_post_content']) && isset($_REQUEST['english_name']) && isset($_REQUEST['english_post_content']) && isset($_REQUEST['gardenday_dates']) && isset($_REQUEST['latest_signup']) && isset($_REQUEST['spaces']) && isset($_REQUEST['max_spaces']) && isset($_REQUEST['publish_date'])) {
		opret_havedage();
	}
}

function opret_havedage(){
	if ( $_REQUEST['danish_name'] != NULL && $_REQUEST['danish_post_content'] != NULL && $_REQUEST['english_name'] != NULL && $_REQUEST['english_post_content'] != NULL && $_REQUEST['gardenday_dates'] != NULL && $_REQUEST['latest_signup'] != NULL && $_REQUEST['spaces'] != NULL && $_REQUEST['max_spaces'] != NULL && $_REQUEST['publish_date'] != NULL) {

		$latest_signup = new DateTime($_REQUEST['latest_signup']);

		$gardendays = explode(",",$_REQUEST['gardenday_dates']);
		rsort($gardendays);

		$danish_args = array();
		$english_args = array();

		foreach($gardendays as $date){
			array_push($danish_args,array(
				'title' => $_REQUEST['danish_name'],
				'post_content' => $_REQUEST['danish_post_content'],
				'lang' => 'da',
				'date' => $date,
				'rsvp' => array(
					'rsvp' => $date == max($gardendays),
					'date' => $latest_signup->format('Y-m-d'),
					'time' => $latest_signup->format('H:i:s'),
					'spaces' => $_REQUEST['spaces'],
					'max_spaces' => $_REQUEST['max_spaces']
				),
				'event_lang' => 'da_DK',
				'publish_date' => $_REQUEST['publish_date']
			));

			array_push($english_args,array(
				'title' => $_REQUEST['english_name'],
				'post_content' => $_REQUEST['english_post_content'],
				'lang' => 'en',
				'date' => $date,
				'rsvp' => array(
					'rsvp' => $date == max($gardendays),
					'date' => $latest_signup->format('Y-m-d'),
					'time' => $latest_signup->format('H:i:s'),
					'spaces' => $_REQUEST['spaces'],
					'max_spaces' => $_REQUEST['max_spaces']
				),
				'event_lang' => 'en_US',
				'publish_date' => $_REQUEST['publish_date']
			));
		}

		$danish_events = opret_havedag($danish_args);
		$english_events = opret_havedag($english_args);

		for ($i = 0; $i < count($danish_events); $i++){
			pll_save_post_translations(array(
				'da' => $danish_events[$i]['id'],
				'en' => $english_events[$i]['id']
			));
		}

		$notice = new AKDTU_notice('success','Danske havedage oprettet. Tilføj begivenhed med slug ' . $danish_events[0]['slug'] . ' til menu og ændr eventuelt offentliggørelsesdato.');
		$notice->render();

		$notice = new AKDTU_notice('success','Engelske havedage oprettet. Tilføj begivenhed med slug ' . $english_events[0]['slug'] . ' til menu og ændr eventuelt offentliggørelsesdato.');
		$notice->render();
	} else {
		$notice = new AKDTU_notice('error','Noget data var ikke korrekt. Havedagene blev ikke oprettet.');
		$notice->render();
	}
}

function opret_havedag($args){
	global $wpdb;
	$events_info = array();
	foreach ($args as $havedag) {
		$title = $havedag['title'];
		$post_content = $havedag['post_content'];
		$lang = $havedag['lang'];
		$date = $havedag['date'];
		$rsvp = $havedag['rsvp'];
		$event_lang = $havedag['event_lang'];
		$publish_date = $havedag['publish_date'];

		$is_pending_status = ($rsvp['rsvp'] ? 1 : (strtotime($publish_date) > strtotime('now') ? 0 : 1)); // Always publish the event with signups enabled, so it can be added to menu and hidden after. Other events are published when they are set to be published.

		// Create Wordpress post
		$id = wp_insert_post(array('post_status' => ($is_pending_status ? 'publish' : 'pending'), 'post_type' => 'event', 'post_title' => $title,'post_content' => $post_content, 'post_date' => ($rsvp['rsvp'] ? current_time("Y-m-d H:i:s") : $publish_date)));

		// Protect event
        SwpmProtection::get_instance()->apply(array($id), 'custom_post')->save();
		$query = "SELECT id FROM " . $wpdb->prefix . "swpm_membership_tbl WHERE id !=1 ";
		$level_ids = $wpdb->get_col($query);
		foreach ($level_ids as $level) {
			SwpmPermission::get_instance($level)->apply(array($id), 'custom_post')->save();
		}

		// Set post language
		pll_set_post_language($id,$lang);

		// Set event details
		$event = new EM_Event($id,'post_id');

		$bookings = new EM_Bookings($event);
	
		if ($rsvp['rsvp']) {
			$event->__set('event_rsvp', $rsvp['rsvp']); // Boolean, if tickets are enabled
			$event->__set('event_rsvp_date', $rsvp['date']); // Latest date to sign up
			$event->__set('event_rsvp_time', $rsvp['time']); // Latest time to sign up
			$event->__set('event_rsvp_spaces', $rsvp['max_spaces']); // Max amount of spaces per signup
		}
		$event->__set('event_start_date', $date); // Start date of event
		$event->__set('event_end_date', $date); // End date of event
		$event->__set('event_start_time', '10:00:00'); // Start time of event
		$event->__set('event_end_time', '15:00:00'); // End time of event
		// $event->__set('event_all_day', 0); // Boolean, is event all day

		$event->__set('location_id', 0); // Location ID
		$event->__set('event_spaces', NULL); // Total amount of spaces on event
		$event->__set('event_private', 0); // Boolean, is event private
		$event->__set('event_language', $event_lang); // Language of event
		$event->__set('blog_id',get_current_blog_id()); // ID of blog, for multi-site installations
		$event->set_status($is_pending_status, true); // Publish event

		$event->save_meta(); // Save event metadata
		$event->save(); // Save event
		
		// Add tickets if wanted
		if ($rsvp['rsvp']){
			$ticket_num = 1;
			foreach ($args as $gardenday_date) {
				$ticket = new EM_Ticket(array('event_id'=>$event->event_id)); // Create ticket object
				$ticket->get_post(array('event_id'=>$event->event_id)); // Update ticket object

				$ticket->__set('event_id',$event->event_id); // Link ticket with event
				$ticket->__set('ticket_name',$gardenday_date['date']); // Name of ticket

				$ticket->__set('ticket_spaces',$rsvp['spaces']); // Amount of spaces on ticket
				$ticket->__set('ticket_members',0); // Boolean, only for members
				$ticket->__set('ticket_guests',0); // Boolean, allow guests
				$ticket->__set('ticket_required',0); // Boolean, required
				$ticket->__set('ticket_meta',array('recurrences'=>NULL)); // Set ticket meta

				$ticket->__set('ticket_price',0); // Ticket price

				$ticket->__set('ticket_order',$ticket_num); // Ticket order
				$ticket_num++;

				$ticket->save(); // Save ticket
			}
		}

		$events_info[] = array('id'=>$id,'slug'=>$event->event_slug);
	}

	return $events_info;
}
