<?php

/**
 * @file Action to programmatically create the events for all of the garden days for a season
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'add_havedag' && isset($_REQUEST['danish_name']) && isset($_REQUEST['danish_post_content']) && isset($_REQUEST['english_name']) && isset($_REQUEST['english_post_content']) && isset($_REQUEST['gardenday_dates']) && isset($_REQUEST['latest_signup']) && isset($_REQUEST['spaces']) && isset($_REQUEST['max_spaces']) && isset($_REQUEST['publish_date'])) {
		opret_havedage(
			$_REQUEST['danish_name'],
			$_REQUEST['danish_post_content'],
			$_REQUEST['english_name'],
			$_REQUEST['english_post_content'],
			explode(",",$_REQUEST['gardenday_dates']),
			new DateTime($_REQUEST['latest_signup']),
			$_REQUEST['spaces'],
			$_REQUEST['max_spaces'],
			$_REQUEST['publish_date']
		);
	}
}

/**
 * Collect info for, and generate all events for all garden days in a season
 * 
 * Stores translation info
 * 
 * @param string $danish_name Name for the garden days in Danish
 * @param string $danish_post_content Info to write during signup for the garden days in Danish
 * @param string $english_name Name for the garden days in English
 * @param string $english_post_content Info to write during signup for the garden days in English
 * @param array[string] $gardendays Array of strings of dates for the garden days
 * @param DateTime $latest_signup Date for the latest signup to the garden days
 * @param int $spaces Amount of spaces for signups for each individual garden day (18/24)
 * @param int $max_spaces Amount of spaces for signups for all garden days combined (72)
 * @param string $publish_date Date for the publication of the events. Also the first day where signups are allowed
 */
function opret_havedage($danish_name, $danish_post_content, $english_name, $english_post_content, $gardendays, $latest_signup, $spaces, $max_spaces, $publish_date){
	# Sort gardenday dates
	rsort($gardendays);

	# Prepare object for gardenday structures
	$danish_args = array();
	$english_args = array();

	$rsvp = true;

	# Go through all planned gardendays
	foreach($gardendays as $date){
		# Add a structure to list of Danish garden days
		array_push($danish_args,array(
			'title' => $danish_name, # Title of the event
			'post_content' => $danish_post_content, # Content of the event
			'lang' => 'da', # Language of the post
			'date' => $date, # Day of the garden day
			'rsvp' => array(
				'rsvp' => $rsvp, # If signups are enabled. Only on the last gardenday
				'date' => $latest_signup->format('Y-m-d'), # Date of last day to sign up
				'time' => $latest_signup->format('H:i:s'), # Time of last day to sign up
				'spaces' => $spaces, # Amount of spaces on this specific garden day
				'max_spaces' => $max_spaces # Maximum amount of spaces on all of the garden days
			),
			'event_lang' => 'da_DK', # Language of the event
			'publish_date' => $publish_date # Date from where signup is allowed
		));

		# Add a structure to list of English garden days
		array_push($english_args,array(
			'title' => $english_name, # Title of the event
			'post_content' => $english_post_content, # Content of the event
			'lang' => 'en', # Language of the post
			'date' => $date, # Day of the garden day
			'rsvp' => array(
				'rsvp' => $rsvp, # If signups are enabled. Only on the last gardenday
				'date' => $latest_signup->format('Y-m-d'), # Date of last day to sign up
				'time' => $latest_signup->format('H:i:s'), # Time of last day to sign up
				'spaces' => $spaces, # Amount of spaces on this specific garden day
				'max_spaces' => $max_spaces # Maximum amount of spaces on all of the garden days
			),
			'event_lang' => 'en_US', # Language of the event
			'publish_date' => $publish_date # Date from where signup is allowed
		));

		$rsvp = false;
	}

	# Create garden days
	$danish_events = opret_havedag($danish_args);
	$english_events = opret_havedag($english_args);

	# Save the fact that corresponding events are translations of eachother
	for ($i = 0; $i < count($danish_events); $i++){
		pll_save_post_translations(array(
			'da' => $danish_events[$i]['post_id'],
			'en' => $english_events[$i]['post_id']
		));
	}

	$date_da = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
	$date_da->setPattern('dd. MMMM YYYY HH:mm');
	$publish_date_as_string = $date_da->format(new DateTime($publish_date));

	// Add first Danish event to the correct menu
	$danish_menu = wp_get_nav_menu_object( 'Dansk - logget ind' );
	$danish_menu_root_event_id = 3455;	// ID of "Havedage" page
	if(add_event_to_menu($danish_menu, $danish_menu_root_event_id, $danish_events[0])) {
		new AKDTU_notice('success', "Danske havedage blev oprettet og automatisk tilføjet hjemmesidens menu for beboere. Begivenhederne bliver automatisk offentliggjort " . $publish_date_as_string);
	}
	else {
		new AKDTU_notice('error', "Danish events could not be added to menu automatically. Slug is " . $danish_events[0]['event_slug']);
	}
	
	// Add first English event to the correct menu
	$english_menu = wp_get_nav_menu_object( 'Engelsk - logget ind' );
	$english_menu_root_event_id = 3499;	// ID of "Garden days" page
	if(add_event_to_menu($english_menu, $english_menu_root_event_id, $english_events[0])) {
		new AKDTU_notice('success', "Engelske havedage blev oprettet og automatisk tilføjet hjemmesidens menu for beboere. Begivenhederne bliver automatisk offentliggjort " . $publish_date_as_string);
	}
	else {
		new AKDTU_notice('error', "English events could not be added to menu automatically. Slug is " . $english_events[0]['event_slug']);
	}
}

/**
 * Programmatically add a new menu item
 * 
 * @param WP_Term $menu The menu to add a new item to. Can be created by calling wp_get_nav_menu_object($menu_name).
 * @param int $menu_root_event_id ID of the event that will be the immediate parent of the new menu item.
 * @param array $event_info Dictionary of event data. Must contain the following keys: post_id, post_title, event_slug
 * 
 * @return bool True if the event was added to the menu successfully
 */
function add_event_to_menu($menu, $menu_root_event_id, $event_info) {
	if ( is_nav_menu( $menu ) ) {
		$menu_item_data = array(
			'menu-item-db-id' => 0,								// ID of the menu item. 0 means create new item.
			'menu-item-object' => 'event',						// Type of element, the new menu item points to.
			'menu-item-object-id' => $event_info['post_id'],	// ID of the post, this menu item is for.
			'menu-item-parent-id' => $menu_root_event_id,		// ID of menu item, that is going to be the parent of the new item.
			'menu-item-position' => '',							// Position of the post. Blank means at the end.
			'menu-item-type' => 'post_type',					// Type of menu item. Same as regular posts.
			'menu-item-title' => $event_info['post_title'],		// Title of the menu item. Set to the same as the event/post.
			'menu-item-url' => $event_info['event_slug'],		// Slug of the event.
			'menu-item-description' => '',						// Description of the menu item. Only used if the wordpress theme supports it.
			'menu-item-attr-title' => '',						// Title attribute. Only used if the wordpress theme supports it.
			'menu-item-target' => '',							// Set to '_blank' to open link in a new tab. Set to '' to open link in current tab.
			'menu-item-classes' => '',							// Additional html classes to add to the menu item.
			'menu-item-xfn' => '',								// Unknown.
			'menu-item-status' => 'publish' 					// Immediately publish new menu item (NOT post or event).
		);

		// Create menu item
		$error = wp_update_nav_menu_item(
			$menu->term_id,		// ID of menu to edit.
			0,					// ID of the menu item. 0 means create new item.
			$menu_item_data,	// Menu item data. Created above.
			true				// Do fire after insert hooks.
		);

		// Check if the menu item was created successfully
		if (is_int($error)) {
			// Success
			return true;
		}
		else {
			// Failure
			new AKDTU_notice('error', $error->get_error_message());
			return false;
		}
	}
}

/**
 * Create events for a series of garden days
 * 
 * @param array[string,string] $args Arguments for the created garden days. Keys must include 'title', 'post_content', 'lang', 'date', 'rsvp->rsvp', 'rsvp->date', 'rsvp->time', 'rsvp->spaces', 'rsvp->max_spaces', 'event_lang', and 'publish_date'. Can be prepared using a call to opret_havedage()
 * 
 * @return array[array[string,int|string]] Array of arrays with the id and slug for the created events
 */
function opret_havedag($args){
	global $wpdb;

	# Prepare array for info about created events
	$events_info = array();

	# Go through all garden days
	foreach ($args as $havedag) {
		# Extract garden day information
		$title = $havedag['title'];
		$post_content = $havedag['post_content'];
		$lang = $havedag['lang'];
		$date = $havedag['date'];
		$rsvp = $havedag['rsvp'];
		$event_lang = $havedag['event_lang'];
		$publish_date = $havedag['publish_date'];

		# Check if the event should be published or set to pending
		# The event with signups enabled are always published, so it can be added to menu and hidden after. Other events are published when they are set to be published.
		$is_pending_status = (strtotime($publish_date) > strtotime('now') ? 0 : 1);

		# Create Wordpress post
		$id = wp_insert_post(array('post_status' => ($is_pending_status ? 'publish' : 'pending'), 'post_type' => 'event', 'post_title' => $title,'post_content' => $post_content, 'post_date' => $publish_date));

		# Protect event
        SwpmProtection::get_instance()->apply(array($id), 'custom_post')->save();
		$query = "SELECT id FROM " . $wpdb->prefix . "swpm_membership_tbl WHERE id !=1 ";
		$level_ids = $wpdb->get_col($query);
		foreach ($level_ids as $level) {
			SwpmPermission::get_instance($level)->apply(array($id), 'custom_post')->save();
		}

		# Set post language
		pll_set_post_language($id,$lang);

		# Set event details
		$event = new EM_Event($id,'post_id');

		# Create bookings object for the event
		$bookings = new EM_Bookings($event);
	
		# Set event information
		# Check if there should be signups enabled for this garden day
		if ($rsvp['rsvp']) {
			$event->__set('event_rsvp', $rsvp['rsvp']); # Boolean, if tickets are enabled
			$event->__set('event_rsvp_date', $rsvp['date']); # Latest date to sign up
			$event->__set('event_rsvp_time', $rsvp['time']); # Latest time to sign up
			$event->__set('event_rsvp_spaces', $rsvp['max_spaces']); # Max amount of spaces per signup
		}
		$event->__set('event_start_date', $date); # Start date of event
		$event->__set('event_end_date', $date); # End date of event
		$event->__set('event_start_time', '10:00:00'); # Start time of event
		$event->__set('event_end_time', '15:00:00'); # End time of event
		# $event->__set('event_all_day', 0); # Boolean, is event all day

		$event->__set('location_id', 0); # Location ID
		$event->__set('event_spaces', NULL); # Total amount of spaces on event
		$event->__set('event_private', 0); # Boolean, is event private
		$event->__set('event_language', $event_lang); # Language of event
		$event->__set('blog_id',get_current_blog_id()); # ID of blog, for multi-site installations
		$event->set_status($is_pending_status, true); # Publish event

		# Save event
		$event->save_meta(); # Save event metadata
		$event->save(); # Save event
		
		# Add tickets if wanted
		if ($rsvp['rsvp']){
			# Counter for amount of tickets  used for ordering them
			$ticket_num = 1;

			# Go through all tickets
			foreach ($args as $gardenday_date) {
				# Create new ticket
				$ticket = new EM_Ticket(array('event_id'=>$event->event_id)); # Create ticket object

				# Add ticket to the correct garden day
				$ticket->get_post(array('event_id'=>$event->event_id)); # Update ticket object

				# Set info about the ticket
				$ticket->__set('event_id',$event->event_id); # Link ticket with event
				$ticket->__set('ticket_name',$gardenday_date['date']); # Name of ticket

				$ticket->__set('ticket_spaces',$rsvp['spaces']); # Amount of spaces on ticket
				$ticket->__set('ticket_members',0); # Boolean, only for members
				$ticket->__set('ticket_guests',0); # Boolean, allow guests
				$ticket->__set('ticket_required',0); # Boolean, required
				$ticket->__set('ticket_meta',array('recurrences'=>NULL)); # Set ticket meta

				$ticket->__set('ticket_price',0); # Ticket price

				$ticket->__set('ticket_order',$ticket_num); # Ticket order
				$ticket_num++;

				# Save ticket
				$ticket->save();
			}
		}

		# Add array with info to return object
		$events_info[] = array(
			'post_id' => $id,
			'event_id' => $event->event_id,
			'event_slug' => $event->event_slug,
			'post_title' => $title
		);
	}

	# Return info about all created garden days
	return $events_info;
}
