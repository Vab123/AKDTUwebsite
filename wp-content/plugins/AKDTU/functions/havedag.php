<?php

/**
 * @file Functionality related to garden days
 */

/**
 * Calculates the price for an apartment not having signed up to a garden day.
 * 
 * The function must reflect all previous changes in prices. The price has previously been changed on:
 * - Never
 * 
 * @param int $apartment_number Apartment number of the apartment not having signed up.
 * @param int $garden_day_id Id of the garden day event
 * 
 * @return int Price of the apartment not having signed up.
 */
function gardenday_price_not_signed_up($apartment_number, $garden_day_id)
{
	if (em_get_event($garden_day_id, 'event_id')->rsvp_date >= "2022-09-18") {
		// Remove this check when the price changes the first time. This is only to show how this can be done in the future.
		return 750;
	}

	return 750;
}

/**
 * Calculates the price for an apartment not attending a garden day they have signed up to.
 * 
 * The function must reflect all previous changes in prices. The price has previously been changed on:
 * - Never
 * 
 * @param int $apartment_number Apartment number of the apartment not attending a garden day they have signed up to.
 * @param int $garden_day_id Id of the garden day event
 * 
 * @return int Price of the apartment not attending a garden day they have signed up to.
 */
function gardenday_price_not_showed_up($apartment_number, $garden_day_id)
{
	if (em_get_event($garden_day_id, 'event_id')->rsvp_date >= "2022-09-18") {
		// Remove this check when the price changes the first time. This is only to show how this can be done in the future.
		return 750;
	}

	return 750;
}

/**
 * Finds the next garden day events.
 * 
 * @param string $language Slug of the language for the events found. 'all' for all languages. (Default: 'all')
 * @param int $amount Number of garden days to find. (Default: 1)
 * 
 * @return EM_Event[]|EM_Event[string]|null Null if no garden days were found. Otherwise, if $language is 'all', an array of key-value-arrays, with keys equal to the language slugs of the garden days and the EM_Events as values. Otherwise, an array of EM_Events.
 */
function next_gardenday($language = 'all', $amount = 1)
{
	# Settings for lookup for garden days
	$scope = 'future';
	$search_limit = 20;
	$offset = 0;
	$order = 'ASC';
	$owner = false;

	# Get future garden days
	$events = EM_Events::get(['scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0]);

	# Check which language the caller walts
	$events = $language == 'all' ? get_all_translations(remove_duplicate_events($events, false, "da")) : remove_duplicate_events($events, false, $language);

	# Return events if any were found. Otherwise, return null
	return count($events) > 0 ? array_slice($events, 0, $amount) : null;
}

/**
 * Finds the previous garden day events.
 * 
 * @param string $language Slug of the language for the events found. 'all' for all languages. (Default: 'all')
 * @param int $amount Number of garden days to find. (Default: 1)
 * 
 * @return EM_Event[]|EM_Event[string]|null Null if no garden days were found. Otherwise, if $language is 'all', an array of key-value-arrays, with keys equal to the language slugs of the garden days and the EM_Events as values. Otherwise, an array of EM_Events.
 */
function previous_gardenday($language = 'all', $amount = 1)
{
	# Settings for lookup for garden days
	$scope = 'past';
	$search_limit = 20;
	$offset = 0;
	$order = 'DESC';
	$owner = false;

	# Get past garden days
	$events = EM_Events::get(['scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0]);

	# Check which language the caller walts
	if ($language == 'all') {
		# The user wants all languages. First find only Danish languages, to not have the same garden day multiple days when triggering on Danish and English events
		$events = remove_duplicate_events($events, false, "da");

		# Find all translations of the Danish garden days and create an appropriate array
		$events = array_map(function ($event) {
			# Find all translations
			$translations = pll_get_post_translations($event->post_id);

			# Create array mapping language slugs to event for this garden day
			return array_combine(array_map(function ($post_id) {
				return pll_get_post_language($post_id, 'slug');
			}, $translations), array_map(function ($post_id) {
				return em_get_event($post_id, 'post_id');
			}, $translations));
		}, $events);
	} else {
		# Filter only the events with the required language
		$events = remove_duplicate_events($events, false, $language);
	}

	# Return events if any were found. Otherwise, return null
	return count($events) > 0 ? array_slice($events, 0, $amount) : null;
}

/**
 * Finds any garden day event.
 * 
 * @param string $language Slug of the language for the events found. 'all' for all languages. (Default: 'all')
 * 
 * @return EM_Event[]|EM_Event|null If any future garden days exist, one of them is returned. Otherwise, if any past garden days exist, one of them is returned. Otherwise null.
 */
function any_gardenday($language = 'all')
{
	$events = next_gardenday($language, 1);

	return is_null($events) ? previous_gardenday($language, 1) : $events;
}

/**
 * Adds an apartment to a gardenday
 * 
 * @param int $user_id User id to add to the gardenday
 * @param int $gardenday_event_id Id of the gardenday event to add the apartment to
 * @param int $gardenday_date Specific date of the garden day to add the apartment to
 * 
 * @return int -2 if the apartment number is not valid, -1 if gardenday date does not exist, 0 if user could not be signed up for the gardenday, 1 if user was added to the gardenday successfully
 */
function add_apartment_to_gardenday($user_id, $gardenday_event_id, $gardenday_date)
{
	global $wpdb;

	if (get_userdata( $user_id ) === false) {
		return -2;
	}

	# Check if the apartment is already signed up to any of the garden days
	if ($wpdb->get_var("SELECT COUNT(*) FROM {EM_BOOKINGS_TABLE} WHERE event_id = {$gardenday_event_id} AND person_id = {$user_id} > 0 AND status = 1")) {
		# Apartment is already signed up. Write warning message to admin interface
		new AKDTU_notice('warning', "Bruger " . name_from_id($user_id) . " var allerede tilmeldt havedagen. Tilmelder igen.");
	}

	# Add booking to user
	$result = $wpdb->insert(EM_BOOKINGS_TABLE, ['event_id' => $gardenday_event_id, 'person_id' => $user_id, 'booking_spaces' => 1, 'booking_comment' => '', 'booking_date' => gmdate('Y-m-d H:i:s'), 'booking_status' => 1, 'booking_price' => 0, 'booking_tax_rate' => 0, 'booking_meta' => 'a:0:{}']);

	# Add tickets to booking
	$result2 = $wpdb->insert(EM_TICKETS_BOOKINGS_TABLE, ['booking_id' => $wpdb->insert_id, 'ticket_id' => $gardenday_date, 'ticket_booking_spaces' => 1, 'ticket_booking_price' => 0]);

	# Get all tickets to all bookings for the garden day event
	$tickets = em_get_event($gardenday_event_id, 'event_id')->get_bookings()->get_tickets()->tickets;

	# Check if new booking was successfully added
	if ($result === 1 && $result2 === 1 && isset($tickets[$gardenday_date])) {
		return 1;
	} else {
		# Booking was not added

		# Check if the garden day exists
		if (isset($tickets[$gardenday_date])) {
			# Garden day exists, but user could not be signed up. Write error message to admin interface
			return 0;
		} else {
			# Garden day does not exist, and user could not be signed up. Write error message to admin interface
			return -1;
		}
	}
}

/**
 * Removes an apartment from a gardenday
 * 
 * @param int $user_id User id to remove from the gardenday
 * @param int $gardenday_event_id Id of the gardenday event to remove the apartment from
 * @param int $gardenday_date Specific date of the garden day to remove the apartment from
 * 
 * @return int -2 if the apartment number is not valid, -1 if gardenday date does not exist, 0 if user could not be removed from the gardenday, 1 if user was removed from the gardenday successfully
 */
function remove_apartment_from_gardenday($user_id, $gardenday_event_id, $gardenday_date)
{
	global $wpdb;

	if (get_userdata( $user_id ) === false) {
		return -2;
	}

	# Get the garden day event
	$event = em_get_event($gardenday_event_id, 'event_id');

	# Get all bookings for the garden day
	$bookings = $event->get_bookings();

	# Get all tickets for the garden day
	$tickets = $bookings->get_tickets()->tickets;

	# Get the bookings of the user to the garden day
	$booking_ids = $wpdb->get_col("SELECT booking_id FROM {EM_BOOKINGS_TABLE} WHERE event_id = \"{$gardenday_event_id}\" AND person_id = \"{$user_id}\"");

	# Check if the garden day is valid, and the user is currently signed up
	if (isset($tickets[$gardenday_date]) && count($booking_ids) > 0) {
		# Remove tickets from the booking
		$result2 = $wpdb->delete(EM_TICKETS_BOOKINGS_TABLE, ['booking_id' => $booking_ids[0], 'ticket_id' => $gardenday_date]);

		# Remove the booking from the user
		$result = $wpdb->delete(EM_BOOKINGS_TABLE, ['event_id' => $gardenday_event_id, 'person_id' => $user_id]);

		# Write success message to admin interface
		if ($result && $result2) {
			return 1;
		} else {
			return 0;
		}
	} else {
		# Something went wrong. Output an error message
		return -1;
	}
}

/**
 * Gets status for apartments showing up to a gardenday
 * 
 * @param string $event_id Id of the gardenday event
 * 
 * @return bool[] Associative array of apartment status. Key is apartment number, value is true if the apartment showed up and false otherwise.
 */
function get_shown_up_status_for_gardenday($event_id)
{
	global $wpdb;

	$res = $wpdb->get_col('SELECT showed_up FROM wp_em_tilmeldinger WHERE event_id = ' . $event_id);
	$res = array_map(function ($a) {
		return json_decode($a);
	}, $res);

	# Go through each possible garden day
	foreach ($res as $arr) {
		# Go through the status of each user signed up to that garden day
		foreach ($arr as $user_id => $stat) {
			# If it is an apartment user
			if (is_apartment_from_id($user_id)) {
				# Store if the apartment has showed up or not
				$status[apartment_number_from_id($user_id)] = $stat || $status[apartment_number_from_id($user_id)];
			}
		}
	}

	return $status;
}

/**
 * Programmatically add a gardenday event to a menu
 * 
 * @param WP_Term $menu The menu to add a new item to. Can be created by calling wp_get_nav_menu_object($menu_name).
 * @param int $menu_root_event_id ID of the event that will be the immediate parent of the new menu item.
 * @param array $event_info Dictionary of event data. Must contain the following keys: post_id, post_title, event_slug
 * 
 * @return bool True if the event was added to the menu successfully
 */
function add_event_to_menu($menu, $menu_root_event_id, $event_info)
{
	if (is_nav_menu($menu)) {
		$menu_item_data = array(
			'menu-item-db-id' => 0,								# ID of the menu item. 0 means create new item.
			'menu-item-object' => 'event',						# Type of element, the new menu item points to.
			'menu-item-object-id' => $event_info['post_id'],	# ID of the post, this menu item is for.
			'menu-item-parent-id' => $menu_root_event_id,		# ID of menu item, that is going to be the parent of the new item.
			'menu-item-position' => '',							# Position of the post. Blank means at the end.
			'menu-item-type' => 'post_type',					# Type of menu item. Same as regular posts.
			'menu-item-title' => $event_info['post_title'],		# Title of the menu item. Set to the same as the event/post.
			'menu-item-url' => $event_info['event_slug'],		# Slug of the event.
			'menu-item-description' => '',						# Description of the menu item. Only used if the wordpress theme supports it.
			'menu-item-attr-title' => '',						# Title attribute. Only used if the wordpress theme supports it.
			'menu-item-target' => '',							# Set to '_blank' to open link in a new tab. Set to '' to open link in current tab.
			'menu-item-classes' => '',							# Additional html classes to add to the menu item.
			'menu-item-xfn' => '',								# Unknown.
			'menu-item-status' => 'publish' 					# Immediately publish new menu item (NOT post or event).
		);

		# Create menu item
		$error = wp_update_nav_menu_item(
			$menu->term_id,		# ID of menu to edit.
			0,					# ID of the menu item. 0 means create new item.
			$menu_item_data,	# Menu item data. Created above.
			true				# Do fire after insert hooks.
		);

		# Check if the menu item was created successfully
		if (is_int($error)) {
			# Success
			return true;
		} else {
			# Failure
			new AKDTU_notice('error', $error->get_error_message());
			return false;
		}
	}

	return false;
}

/**
 * Collect info for, and generate all events for all gardendays in a season
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
 * @param string $start_time Time for the start of the garden days
 * @param string $end_time Time for the end of the garden days
 * 
 * @return bool True if all events was saved correctly
 */
function create_gardendays($danish_name, $danish_post_content, $english_name, $english_post_content, $gardendays, $latest_signup, $spaces, $max_spaces, $publish_date, $start_time, $end_time)
{
	# Sort gardenday dates
	rsort($gardendays);

	# Prepare object for gardenday structures
	$danish_args = [];
	$english_args = [];

	$rsvp = true;

	# Go through all planned gardendays
	foreach ($gardendays as $date) {
		# Add a structure to list of Danish garden days
		array_push($danish_args, [
			'title' => $danish_name, 						# Title of the event
			'post_content' => $danish_post_content, 		# Content of the event
			'lang' => 'da', 								# Language of the post
			'date' => $date, 								# Day of the garden day
			'start_time' => $start_time,					# Start time of the garden day
			'end_time' => $end_time,						# End time of the garden day
			'rsvp' => [										#
				'rsvp' => $rsvp, 							# If signups are enabled. Only on the last gardenday
				'date' => $latest_signup->format('Y-m-d'), 	# Date of last day to sign up
				'time' => $latest_signup->format('H:i:s'), 	# Time of last day to sign up
				'spaces' => $spaces, 						# Amount of spaces on this specific garden day
				'max_spaces' => $max_spaces 				# Maximum amount of spaces on all of the garden days
			],												#
			'event_lang' => 'da_DK', 						# Language of the event
			'publish_date' => $publish_date 				# Date from where signup is allowed
		]);

		# Add a structure to list of English garden days
		array_push($english_args, [
			'title' => $english_name, 						# Title of the event
			'post_content' => $english_post_content, 		# Content of the event
			'lang' => 'en', 								# Language of the post
			'date' => $date, 								# Day of the garden day
			'start_time' => $start_time,					# Start time of the garden day
			'end_time' => $end_time,						# End time of the garden day
			'rsvp' => [										#
				'rsvp' => $rsvp, 							# If signups are enabled. Only on the last gardenday
				'date' => $latest_signup->format('Y-m-d'), 	# Date of last day to sign up
				'time' => $latest_signup->format('H:i:s'), 	# Time of last day to sign up
				'spaces' => $spaces, 						# Amount of spaces on this specific garden day
				'max_spaces' => $max_spaces 				# Maximum amount of spaces on all of the garden days
			],												#
			'event_lang' => 'en_US', 						# Language of the event
			'publish_date' => $publish_date 				# Date from where signup is allowed
		]);

		$rsvp = false;
	}

	# Create garden days
	$danish_events = create_gardenday_events($danish_args);
	$english_events = create_gardenday_events($english_args);

	# Save the fact that corresponding events are translations of eachother
	for ($i = 0; $i < count($danish_events); $i++) {
		pll_save_post_translations([
			'da' => $danish_events[$i]['post_id'],
			'en' => $english_events[$i]['post_id']
		]);
	}

	$date_da = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen', null, 'dd. MMMM YYYY HH:mm');
	$publish_date_as_string = $date_da->format(new DateTime($publish_date, new DateTimeZone('Europe/Copenhagen')));

	# Add first Danish event to the correct menu
	$danish_menu = wp_get_nav_menu_object('Dansk - logget ind');
	$danish_menu_root_event_id = 3455;	# ID of "Havedage" page
	if (add_event_to_menu($danish_menu, $danish_menu_root_event_id, $danish_events[0])) {
		new AKDTU_notice('success', "Danske havedage blev oprettet og automatisk tilføjet hjemmesidens menu for beboere. Begivenhederne bliver automatisk offentliggjort {$publish_date_as_string}. Tilmelding sker på https://akdtu.dk/events/{$danish_events[0]['event_slug']}/");
	} else {
		new AKDTU_notice('error', "Danish events could not be added to menu automatically. Slug is {$danish_events[0]['event_slug']}");

		return false;
	}

	# Add first English event to the correct menu
	$english_menu = wp_get_nav_menu_object('Engelsk - logget ind');
	$english_menu_root_event_id = 3499;	# ID of "Garden days" page
	if (add_event_to_menu($english_menu, $english_menu_root_event_id, $english_events[0])) {
		new AKDTU_notice('success', "Engelske havedage blev oprettet og automatisk tilføjet hjemmesidens menu for beboere. Begivenhederne bliver automatisk offentliggjort {$publish_date_as_string}. Tilmelding sker på https://akdtu.dk/en/events/{$english_events[0]['event_slug']}/");
	} else {
		new AKDTU_notice('error', "English events could not be added to menu automatically. Slug is {$english_events[0]['event_slug']}");

		return false;
	}

	return true;
}

/**
 * Creates gardenday events for a season at a time
 * 
 * @param string[] $args Arguments for the created garden days. Keys must include 'title', 'post_content', 'lang', 'date', 'rsvp->rsvp', 'rsvp->date', 'rsvp->time', 'rsvp->spaces', 'rsvp->max_spaces', 'event_lang', 'publish_date', 'start_time', 'end_time'. Can be prepared using a call to prepare_gardendays()
 * @return array[] Array of arrays with post_id, event_id, event_slug, and post_title for the created gardenday events
 */
function create_gardenday_events($args)
{
	global $wpdb;

	# Prepare array for info about created events
	$events_info = [];

	# Go through all garden days
	foreach ($args as $havedag) {
		# Extract garden day information

		$title = $havedag['title'];
		$post_content = $havedag['post_content'];
		$lang = $havedag['lang'];
		$date = $havedag['date'];
		$start_time = (new DateTime($havedag['start_time']))->format("H:i:s");
		$end_time = (new DateTime($havedag['end_time']))->format("H:i:s");
		$rsvp = $havedag['rsvp'];
		$event_lang = $havedag['event_lang'];
		$publish_date = $havedag['publish_date'];

		# Check if the event should be published or set to pending
		# The event with signups enabled are always published, so it can be added to menu and hidden after. Other events are published when they are set to be published.
		$is_pending_status = strtotime($publish_date) > strtotime('now') ? 0 : 1;

		# Create Wordpress post
		$id = wp_insert_post(['post_status' => ($is_pending_status ? 'publish' : 'pending'), 'post_type' => 'event', 'post_title' => $title, 'post_content' => $post_content, 'post_date' => $publish_date]);

		# Protect event
		SwpmProtection::get_instance()->apply([$id], 'custom_post')->save();
		$level_ids = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}swpm_membership_tbl WHERE id != 1");
		foreach ($level_ids as $level) {
			SwpmPermission::get_instance($level)->apply([$id], 'custom_post')->save();
		}

		# Set post language
		pll_set_post_language($id, $lang);

		# Set event details
		$event = new EM_Event($id, 'post_id');

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
		$event->__set('event_start_time', $start_time); # Start time of event
		$event->__set('event_end_time', $end_time); # End time of event
		# $event->__set('event_all_day', 0); # Boolean, is event all day

		$event->__set('location_id', 0); # Location ID
		$event->__set('event_spaces', NULL); # Total amount of spaces on event
		$event->__set('event_private', 0); # Boolean, is event private
		$event->__set('event_language', $event_lang); # Language of event
		$event->__set('blog_id', get_current_blog_id()); # ID of blog, for multi-site installations
		$event->set_status($is_pending_status, true); # Publish event

		# Save event
		$event->save_meta(); # Save event metadata
		$event->save(); # Save event

		# Add tickets if wanted
		if ($rsvp['rsvp']) {
			# Counter for amount of tickets  used for ordering them
			$ticket_num = 1;

			# Go through all tickets
			foreach (array_reverse($args) as $gardenday_date) {
				# Create new ticket
				$ticket = new EM_Ticket(['event_id' => $event->event_id]); # Create ticket object

				# Add ticket to the correct garden day
				$ticket->get_post(['event_id' => $event->event_id]); # Update ticket object

				# Set info about the ticket
				$ticket->__set('event_id', $event->event_id); # Link ticket with event
				$ticket->__set('ticket_name', $gardenday_date['date']); # Name of ticket

				$ticket->__set('ticket_spaces', $rsvp['spaces']); # Amount of spaces on ticket
				$ticket->__set('ticket_members', 0); # Boolean, only for members
				$ticket->__set('ticket_guests', 0); # Boolean, allow guests
				$ticket->__set('ticket_required', 0); # Boolean, required
				$ticket->__set('ticket_meta', ['recurrences' => NULL]); # Set ticket meta

				$ticket->__set('ticket_price', 0); # Ticket price

				$ticket->__set('ticket_order', $ticket_num); # Ticket order
				$ticket_num++;

				# Save ticket
				$ticket->save();
			}
		}

		# Add array with info to return object
		$events_info[] = [
			'post_id' => $event->post_id,
			'event_id' => $event->event_id,
			'event_slug' => $event->event_slug,
			'post_title' => $title
		];
	}

	# Return info about all created garden days
	return $events_info;
}
