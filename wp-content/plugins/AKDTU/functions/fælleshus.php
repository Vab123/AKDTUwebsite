<?php

/**
 * @file Functionality related to the billing of residents for booking the common house
 */

/**
 * Filters out duplicate events, such as when the board has a booking in Danish and in English.
 * 
 * @param EM_Event[] $events Array of events to remove duplicates from
 * 
 * @return EM_Event[] Array of filtered events, without duplicates
 */
function remove_duplicate_events($events, $keep_single_events = true, $paired_event_language_slug = "da") {
	if ($keep_single_events) {
		// Filter events to remove duplicates
		return array_filter($events, function ($event) use($paired_event_language_slug) {
			return count(pll_get_post_translations($event->post_id)) == 1 || pll_get_post_language($event->post_id, "slug") == $paired_event_language_slug;
		});
	}
	else {
		// Filter events to remove duplicates
		return array_filter($events, function ($event) use($paired_event_language_slug) {
			return pll_get_post_language($event->post_id, "slug") == $paired_event_language_slug;
		});
	}
}

/**
 * Gets all translations of a series of events. Assumes no duplicate events are provided
 * 
 * @param EM_Event[] $events Array of events to get all translations of
 * 
 * @return EM_Event[][string] Array of arrays with all translations of the provided events. Keys are language slugs, values are events
 */
function get_all_translations($events) {
	return array_map(function ($event) {
		# Find all translations
		$translations = pll_get_post_translations($event->post_id);

		# Create array mapping language slugs to event for this garden day
		return array_combine(array_map(function ($post_id) {
			return pll_get_post_language($post_id, 'slug');
		}, $translations), array_map(function ($post_id) {
			return em_get_event($post_id, 'post_id');
		}, $translations));
	}, $events);
}

/**
 * Gets ids of events that have booked the common house at a specified time
 * 
 * @param DateTime|null $starttime_before Timestamp of when the events has to start before. Treated as time in timezone 'Europe/Copenhagen'
 * @param DateTime|null $starttime_after Timestamp of when the events has to start after. Treated as time in timezone 'Europe/Copenhagen'
 * @param DateTime|null $endtime_before Timestamp of when the events has to end before. Treated as time in timezone 'Europe/Copenhagen'
 * @param DateTime|null $endtime_after Timestamp of when the events has to end after. Treated as time in timezone 'Europe/Copenhagen'
 * @param int|null $limit Limit on how many event ids to return, or null to return all found event ids. Default: null
 * @param int|null $status Required status code of the events found, or null to include any status codes. Default: 1
 * @param string $orderby Column (and optionally direction) to sort the event ids by
 * @param string[] $columns Array of which columns to retrieve from the database
 * 
 * @return string|string[]|object|object[] If $limit = 1 and only one column is selected, returns the value found as a string. If $limit != 1 and only one column is selected, returns an array of the values found as strings. If $limit = 1 and more than one column is selected, returns an object (->column = value) with the values found as strings. If $limit != 1 and more than one column is selected, returns an array of one object per row (->column = value) with the values found as strings
 */
function get_common_house_events($starttime_before = null, $starttime_after = null, $endtime_before = null, $endtime_after = null, $limit = null, $status = 1, $orderby = null, $columns = array('event_id')) {
	global $wpdb;

	$sql_options = array();

	if (!is_null($starttime_before)) {
		$starttime_before_days = $starttime_before->format('Y-m-d');
		$starttime_before_hours = $starttime_before->format('H:i:s');

		$sql_options[] = '(event_start_date < "' . $starttime_before_days . '" OR (event_start_date = "' . $starttime_before_days . '" AND event_start_time <= "' . $starttime_before_hours . '"))';
	}

	if (!is_null($starttime_after)) {
		$starttime_after_days = $starttime_after->format('Y-m-d');
		$starttime_after_hours = $starttime_after->format('H:i:s');

		$sql_options[] = '(event_start_date > "' . $starttime_after_days . '" OR (event_start_date = "' . $starttime_after_days . '" AND event_start_time >= "' . $starttime_after_hours . '"))';
	}

	if (!is_null($endtime_before)) {
		$endtime_before_days = $endtime_before->format('Y-m-d');
		$endtime_before_hours = $endtime_before->format('H:i:s');

		$sql_options[] = '(event_end_date < "' . $endtime_before_days . '" OR (event_end_date = "' . $endtime_before_days . '" AND event_end_time <= "' . $endtime_before_hours . '"))';
	}

	if (!is_null($endtime_after)) {
		$endtime_after_days = $endtime_after->format('Y-m-d');
		$endtime_after_hours = $endtime_after->format('H:i:s');

		$sql_options[] = '(event_end_date > "' . $endtime_after_days . '" OR (event_end_date = "' . $endtime_after_days . '" AND event_end_time >= "' . $endtime_after_hours . '"))';
	}

	if (!is_null($status)) {
		$sql_options[] = 'event_status = ' . strval($status);
	}

	if (!is_null($limit)) {
		$sql_options[] = ' LIMIT ' . strval($limit);
	}

	if (!is_null($orderby)) {
		$sql_options[] = ' ORDER BY ' . $orderby;
	}

	if ($limit == 1 && count($columns) == 1) {
		return $wpdb->get_var('SELECT ' . $columns[0] . ' FROM ' . EM_EVENTS_TABLE . (count($sql_options) > 0 ? join(' AND ', $sql_options) : ''));
	} else if ($limit != 1 && count($columns) == 1) {
		return $wpdb->get_col('SELECT ' . $columns[0] . ' FROM ' . EM_EVENTS_TABLE . (count($sql_options) > 0 ? join(' AND ', $sql_options) : ''));
	} else if ($limit == 1 && count($columns) > 1) {
		return $wpdb->get_row('SELECT ' . join(',', $columns) . ' FROM ' . EM_EVENTS_TABLE . (count($sql_options) > 0 ? join(' AND ', $sql_options) : ''));
	} else {
		return $wpdb->get_results('SELECT ' . join(',', $columns) . ' FROM ' . EM_EVENTS_TABLE . (count($sql_options) > 0 ? join(' AND ', $sql_options) : ''));
	}
}

/**
 * Gets ids of the events that have booked the common house most recently
 * 
 * @param int|null $limit Limit on how many event ids to return, or null to return all found event ids. Default: null
 * @param int|null $status Required status code of the events found, or null to include any status codes. Default: 1
 * 
 * @return string|string[] If $limit = 1, returns the id of the event found. If $limit != 1, returns an array of event ids for the events found
 */
function get_recent_common_house_event_ids($limit = null, $status = 1) {
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return get_common_house_events($now, null, null, null, $limit, $status, 'event_end DESC', array('event_id'));
}

/**
 * Gets ids of next events that have booked the common house
 * 
 * @param int|null $limit Limit on how many event ids to return, or null to return all found event ids. Default: null
 * @param int|null $status Required status code of the events found, or null to include any status codes. Default: 1
 * 
 * @return string|string[] If $limit = 1, returns the id of the event found. If $limit != 1, returns an array of event ids for the events found
 */
function get_next_common_house_event_ids($limit = null, $status = 1) {
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return get_common_house_events(null, $now, null, null, $limit, $status, 'event_end ASC', array('event_id'));
}

/**
 * Gets ids of current events that have booked the common house now
 * 
 * @param int|null $limit Limit on how many event ids to return, or null to return all found event ids. Default: null
 * @param int|null $status Required status code of the events found, or null to include any status codes. Default: 1
 * 
 * @return string|string[] If $limit = 1, returns the id of the event found. If $limit != 1, returns an array of event ids for the events found
 */
function get_current_common_house_event_ids($limit = null, $status = 1) {
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return get_common_house_events($now, null, null, $now, $limit, $status, null, array('event_id'));
}

/**
 * Returns a formatted string for the owners of a set of events. Filters out duplicate events, such as when the board has a booking in Danish and in English.
 * 
 * @param int[] $event_ids Ids of the events to find the owners of
 * @param bool $use_padded_apartment_numbers True if padded apartment numbers should be used. Otherwise non-padded apartment numbers are used. Default: true
 * @param string $apartment_text Text to write in front of the apartment numbers when the owner of an event is an apartment user. Default: 'lejlighed '
 * @param string $board_text Text to write in front of the apartment numbers when the owner of an event is not an apartment user. Default: 'bestyrelsen'
 * @param string $nobody_text Text to write when the common house is not currently rented by anyone. Default: 'ingen'
 * @param string $and_text Text to write before the last owner when more than one event is supplied. Default: ' og '
 * 
 * @return string[string] Array containing the following keys:
 * 		'renters' [string]:  Formatted string containing information about the owners of the events
 * 		'rented' [bool]:     True if the common house is rented. False otherwise
 */
function get_common_house_renters($event_ids, $use_padded_apartment_numbers = true, $apartment_text = 'lejlighed ', $board_text = 'bestyrelsen', $nobody_text = 'ingen', $and_text = ' og ') {
	// Get the actual events
	$events = array_map(function ($event_id) {
		return em_get_event($event_id, 'event_id');
	}, $event_ids);

	// Filter events to remove duplicates
	$events = remove_duplicate_events($events);

	// Check how many events actually exist
	if (count($events) > 0) {
		// There exists at least one event.
		// Find owners of all the remaining events
		$event_owners = array_map(function ($event) use($apartment_text, $board_text, $use_padded_apartment_numbers) {
			return (is_apartment_from_id($event->owner) ? $apartment_text . ($use_padded_apartment_numbers ? padded_apartment_number_from_id($event->owner) : apartment_number_from_id($event->owner)) : $board_text);
		}, $events);
		
		// Check if more than one event exists
		if (count($events) > 1) {
			// More than one event exists
			// Format the owners correctly
			$last = array_pop($event_owners);
			$event_owners = implode(', ', $event_owners) . $and_text . $last;
		}
		else {
			// Only one event exists
			$event_owners = $event_owners[0];
		}

		// The common house is rented
		$rented = true;
	} else {
		// There does not exist any events.
		$event_owners = $nobody_text;

		// The common house is not rented
		$rented = false;
	}

	// Return information
	return array(
		'renters' => $event_owners,
		'rented' => $rented,
	);
}

/**
 * Returns a formatted string for the current renters of the common house. Filters out duplicate events, such as when the board has a booking in Danish and in English.
 * 
 * @param bool $use_padded_apartment_numbers True if padded apartment numbers should be used. Otherwise non-padded apartment numbers are used. Default: true
 * @param string $apartment_text Text to write in front of the apartment numbers when the owner of an event is an apartment user. Default: 'lejlighed '
 * @param string $board_text Text to write in front of the apartment numbers when the owner of an event is not an apartment user. Default: 'bestyrelsen'
 * @param string $nobody_text Text to write when the common house is not currently rented by anyone. Default: 'ingen'
 * @param string $and_text Text to write before the last owner when more than one event is supplied. Default: ' og '
 * 
 * @return string[string] Array containing the following keys:
 * 		'renters' [string]:  Formatted string containing information about the owners of the events
 * 		'rented' [bool]:     True if the common house is rented. False otherwise
 */
function get_current_common_house_renters($use_padded_apartment_numbers = true, $apartment_text = 'lejlighed ', $board_text = 'bestyrelsen', $nobody_text = 'ingen', $and_text = ' og ') {
	return get_common_house_renters(get_current_common_house_event_ids(), $use_padded_apartment_numbers, $apartment_text, $board_text, $nobody_text, $and_text);
}

/**
 * Returns the machine-readable name of an event corresponding to a rental of the common house for an apartment.
 * 
 * @param int $apartment_num Apartment number of the user creating the rental.
 * 
 * @return string Machine-readable name of the event corresponding to the rental
 */
function common_house_rental_name($apartment_num) {
	return '#_RENTAL_BEFORE_APARTMENTNUM' . padded_apartment_number_from_apartment_number($apartment_num) . '#_RENTAL_AFTER_APARTMENTNUM';
}

/**
 * Checks if an event is a rental of the common house by an apartment
 * 
 * @param EM_Event $event The event to be checked
 * 
 * @return bool True if the event is a rental of the common house by an apartment
 */
function is_common_house_rental($event) {
	return substr($event->event_name, 0, 28) == "#_RENTAL_BEFORE_APARTMENTNUM";
}

/**
 * Returns the formatted version of the names of events corresponding to rentals of the common house.
 * The name of the event should be as defined in common_house_rental_name() above.
 * 
 * Performs the appropriate replaces in the name, defined by us.
 * 
 * @param EM_Event $EM_Event The event to output the name of.
 * @param string $calendar_language Locale of the language to write the name of the event in. (Default: "da_DK").
 * @param bool $is_export True if the name of the event is to be used when exporting the calendar to ics-format.
 * 
 * @return string Formatted version of the name of the event.
 */
function format_common_house_rental_name($EM_Event, $calendar_language = "da_DK", $is_export = false) {
	$event_name_replaces = array(
		'#_RENTAL_BEFORE_APARTMENTNUM' => pll_translate_string(($is_export ? 'EXPORT_' : '') . ($EM_Event->event_status == 0 ? 'RENTAL_BEFORE_APARTMENTNUM_NOTAPPROVED' : 'RENTAL_BEFORE_APARTMENTNUM_APPROVED'), $calendar_language),
		'#_RENTAL_AFTER_APARTMENTNUM' => pll_translate_string(($is_export ? 'EXPORT_' : '') . ($EM_Event->event_status == 0 ? 'RENTAL_AFTER_APARTMENTNUM_NOTAPPROVED' : 'RENTAL_AFTER_APARTMENTNUM_APPROVED'), $calendar_language),
		'&nbsp;' => ' ',
	);

	return str_replace(array_keys($event_name_replaces), $event_name_replaces, $EM_Event->event_name);
}

/**
 * Returns the formatted version of the names of events corresponding to rentals of the common house.
 * The name of the event should be as defined in common_house_rental_name() above.
 * 
 * Performs the appropriate replaces in the name, defined by us.
 * 
 * @param EM_Event $EM_Event The event to output the name of.
 * @param string $calendar_language Locale of the language to write the name of the event in. (Default: "da_DK").
 * @param bool $is_export True if the name of the event is to be used when exporting the calendar to ics-format.
 * 
 * @return string Formatted version of the name of the event.
 */
function format_common_house_rental_description($EM_Event, $calendar_language = "da_DK", $is_export = false) {
	$event_name_replaces = array(
		'#APT' => padded_apartment_number_from_id($EM_Event->owner),
		'#_RENTAL_BEFORE_APARTMENTNUM' => pll_translate_string(($is_export ? 'EXPORT_' : '') . ($EM_Event->event_status == 0 ? 'RENTAL_BEFORE_APARTMENTNUM_NOTAPPROVED' : 'RENTAL_BEFORE_APARTMENTNUM_APPROVED'), $calendar_language),
		'#_RENTAL_AFTER_APARTMENTNUM' => pll_translate_string(($is_export ? 'EXPORT_' : '') . ($EM_Event->event_status == 0 ? 'RENTAL_AFTER_APARTMENTNUM_NOTAPPROVED' : 'RENTAL_AFTER_APARTMENTNUM_APPROVED'), $calendar_language),
		'&nbsp;' => ' ',
	);

	return str_replace(array_keys($event_name_replaces), $event_name_replaces, pll_translate_string(($is_export ? 'EXPORT_' : '') . ($EM_Event->event_status == 0 ? 'RENTAL_DESCRIPTION_NOTAPPROVED' : 'RENTAL_DESCRIPTION_APPROVED'), $calendar_language));
}

/**
 * Returns a javascript function for calculating the price for renting the common house in a determined period.
 * 
 * The name of the function must be update_price.
 * 
 * The function is called in "wp-content\plugins\events-manager\includes\js\events-manager.js"
 * 
 * The function must take two arguments, start_date and end_date, representing the start and end dates of the rental period respectively. Both arguments are passed as strings of dates formatted as DD/MM/YYYY. Any required convertions must therefore also be made by this function.
 * 
 * The function should also reflect all previous changes in rental prices. The price has previously been changed on:
 * - April 1st 2024: From (100 + num_days * 100) to (200 + num_days * 100)
 * 
 * The function must write correct text to two html elements:
 * - document.getElementById("event-form-price"): Element to write the price of the rental, along with wrapping text, as defined below in AKDTU_price_mark_pre and AKDTU_price_mark_post.
 * - document.getElementById("event-form-price-month"): Element to write the month in which the payment of the rental is expected to be processed.
 * 
 * Other available global javascript variables, defined in "wp-content\plugins\events-manager\templates\forms\event-editor.php", are:
 * - var AKDTU_price_mark_pre: Text to write immediately before the price of the rental, translated into the language of the current page. E.g. "The price of the rental is: ". This value is set in the Polylang plugin, by editing the translation of "Common house rental price, pre".
 * - var AKDTU_price_mark_post: Text to write immediately after the price of the rental, translated into the language of the current page. E.g. " DKK". This value is set in the Polylang plugin, by editing the translation of "Common house rental price, post".
 * - var AKDTU_price_mark_invalid: Text to write into price_element when the dates selected are invalid, e.g. if end_date is before start_date. E.g. "(Invalid dates selected)". This value is set in the Polylang plugin, by editing the translation of "Common house rental price, invalid".
 * - var AKDTU_months: Array of names of the months of the year as strings, translated into the language of the current page. This value is set in the Polylang plugin, by editing the translation of "Month_array".
 * - var AKDTU_price_mark_invalid_month: Text to write into month_element when the dates selected are invalid, e.g. if end_date is before start_date. E.g. "(Invalid dates selected)". This value is set in the Polylang plugin, by editing the translation of "Common house rental price month, invalid".
 */
function js_calc_rental_cost_script() {
	return 'function update_price(start_date,end_date){
		// Give dates in DD/MM/YYYY. Function converts

		var enddate = new Date(end_date.substring(3,5) + "/"  + end_date.substring(0,2) + "/" + end_date.substring(6,10));
		var startdate = new Date(start_date.substring(3,5) + "/"  + start_date.substring(0,2) + "/" + start_date.substring(6,10));

		if (start_date.toString() != "Invalid Date" && end_date.toString() != "Invalid Date") {
			var diff = enddate - startdate;
			var daysDiff = Math.round(diff / (1000 * 60 * 60 * 24));	// Use round to account for edge-cases when starting and ending summer-time

			var price_element = document.getElementById("event-form-price");
			var month_element = document.getElementById("event-form-price-month");

			if (price_element != null && month_element != null){
				if (AKDTU_months === undefined) {
					AKDTU_months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
				}
				if (daysDiff > 0) {
					// Price was changed 1/4/2024. These are the prices from before this date.
					// How to get the timestamp: var d = new Date("04/01/2024 00:00"); d.getTime();
					if (enddate.getTime() < 1711922400000) {
						var price = 100 + daysDiff*100;		// Price before 1/4/2024
					}
					else {
						var price = 200 + daysDiff*100;		// Price after 1/4/2024
					}
					price_element.innerHTML = (AKDTU_price_mark_pre === undefined ? "" : AKDTU_price_mark_pre + " ") + price + (AKDTU_price_mark_post === undefined ? " DKK" : " " + AKDTU_price_mark_post);

					month_element.innerHTML = AKDTU_months[enddate.getMonth()+1];
				} else {
					price_element.innerHTML = (AKDTU_price_mark_invalid === undefined ? "INVALID DATES" : AKDTU_price_mark_invalid);
					month_element.innerHTML = (AKDTU_price_mark_invalid_month === undefined ? "INVALID DATES" : AKDTU_price_mark_invalid_month);
				}
			}
		}
	}';
}

/**
 * Calculates the rental cost for renting the common house for a period.
 * 
 * Can handle starting- and ending-times not being exactly 12:00
 * 
 * The function must reflect all previous changes in rental prices. The price has previously been changed on:
 * - April 1st 2024: From (100 + num_days * 100) to (200 + num_days * 100)
 * 
 * @param datetime $startdatetime PHP DateTime object, corresponding to the start of the rental period
 * @param datetime $enddatetime PHP DateTime object, corresponding to the end of the rental period
 * @param int $owner_id Id of the renter of the common house
 * 
 * @return int how much it costs in DKK to rent the common house from $startdatetime to $enddatetime
 */
function calc_rental_cost($startdatetime, $enddatetime, $owner_id) {
	# Check if event-owner is not a regular apartment user, or a board member
	if (!had_to_pay_rental_cost_from_id($owner_id, $enddatetime)){
		# Event owner is not a regular apartment user, and should not pay
		return 0;
	}

	# Round start date to appropriate 12:00
	if ($startdatetime->format('H:i:s') != '12:00:00') {
		# Round to 12:00
		if ($startdatetime->format('H:i:s') < '12:00:00') {
			# Subtract 1 day
			$startdatetime->sub(new DateInterval('P1D'));
		}
		# Set time to 12:00
		$startdatetime->setTime(12,0,0,0);
	}

	# Round end date to appropriate 12:00
	if ($enddatetime->format('H:i:s') != '12:00:00') {
		# Round to 12:00
		if ($enddatetime->format('H:i:s') > '12:00:00') {
			# Add 1 day
			$enddatetime->add(new DateInterval('P1D'));
		}
		# Set time to 12:00
		$enddatetime->setTime(12,0,0,0);
	}

	# Calculate difference in seconds
	$diff_in_seconds = $enddatetime->getTimestamp() - $startdatetime->getTimestamp();
	
	# Get difference in days
	$diff_in_days = ceil( $diff_in_seconds / (60 * 60 * 24));
	
	# Price was changed 1/4/2024. These are the prices from before this date.
	# How to get the timestamp: (new DateTime("04/01/2024 00:00:00", new DateTimeZone("Europe/Copenhagen")))->getTimestamp()
	if ($enddatetime->getTimestamp() < 1711922400) {
		# Return price: 200 for first day, and 100 for subsequent days
		return 100 + 100 * $diff_in_days;
	}
	else {
		# Return price: 300 for first day, and 100 for subsequent days
		return 200 + 100 * $diff_in_days;
	}
}

/**
 * Calculates the rental costs for all rentals of the common house in a specific period.
 * 
 * Returns a struct with the usernames and the total price they should pay over the period
 * 
 * @param datetime $period_start PHP DateTime object, corresponding to the start of the period to check
 * @param datetime $period_end PHP DateTime object, corresponding to the end of the period to check
 * 
 * @return array[string,int] Key-value array, with keys being usernames and values being the price. Usernames can be checked to see if the user is a current or past resident
 */
function get_price_to_pay($period_start, $period_end) {
	# username -> price
	global $wpdb;

	# Get all rentals in the period
	$event_ids = $wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_end_date >= "' . $period_start->format('Y-m-d') . '" AND event_end_date <= "' . $period_end->format('Y-m-d') . '" AND event_status = 1');

	# Get array of events ending in this month
	$events = array_map(function($id){
		return em_get_event($id,'event_id');
	}, $event_ids);

	# Prepare array for rental information
	$price_to_pay = array();

	# Go through all rentals
	foreach ($events as $event) {
		# Get username of renter
		$username = username_from_id($event->owner);
		
		# Get start- and end-time of this specific rental
		$event_start = new DateTime($event->event_start_date . " " . $event->event_start_time);
		$event_end = new DateTime($event->event_end_date . " " . $event->event_end_time);

		# Add price to array, checking if the user has already rented before in this period
		if (isset( $price_to_pay[$username] )) {
			$price_to_pay[$username] += calc_rental_cost($event_start, $event_end, $event->owner);
		} else {	
			$price_to_pay[$username] = calc_rental_cost($event_start, $event_end, $event->owner);
		}
	}

	# Sort array by key (username)
	ksort($price_to_pay);

	# Remove keys where the price is zero (board members, vicevært, etc.)
	$price_to_pay = array_filter($price_to_pay,function($val){return $val > 0;});

	# Return array of payment information
	return $price_to_pay;
}

/**
 * Calculates the adjustments rental costs for all rentals of the common house in a specific period.
 * 
 * Returns a struct with the apartment numbers and the total price they should pay over the period
 * 
 * @param datetime $period_start PHP DateTime object, corresponding to the start of the period to check
 * @param datetime $period_end PHP DateTime object, corresponding to the end of the period to check
 * 
 * @return array[int,int] Key-value array, with keys being apartment numbers and values being the price. This does NOT show if the user is a current or past resident
 */
function get_price_adjustments($period_start, $period_end) {
	# Returns the price adjustments for rental of the common house, from $period_start to $period_end
	# apartment number -> price change
	global $wpdb;

	# Get all price-changes in the period
	$changes = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'em_lejepris_ændringer WHERE change_date >= "' . $period_start->format('Y-m-d') . '" AND change_date <= "' . $period_end->format('Y-m-d') . '"');

	# Prepare array for price information
	$price_adjustments = array();

	# Go through all price changes
	foreach ($changes as $change) {
		# Add price change to array, checking if the user has already rented before in this period
		if (isset( $price_adjustments[$change->apartment] )) {
			$price_adjustments[$change->apartment] += $change->price_change;
		} else {	
			$price_adjustments[$change->apartment] = $change->price_change;
		}
	}

	# Sort array by key (apartment number)
	ksort($price_adjustments);

	# Return array of payment information
	return $price_adjustments;
}

/**
 * Calculates the final rental costs for all rentals of the common house in a specific period, including price adjustments
 * 
 * Returns a struct with the usernames and the total price they should pay over the period, after price adjustments
 * 
 * @param array[string,int] $price_to_pay Key-value pair prepared by a call to get_price_to_pay()
 * @param array[int,int] $price_adjustments Key-value pair prepared by a call to get_price_adjustments()
 * 
 * @return array[string,int] Key-value array, with keys being usernames and values being the price. Usernames can be checked to see if the user is a current or past resident
 */
function get_final_price($price_to_pay, $price_adjustments) {
	# Prepare array for price information
	$final_price = array();

	# Go through all apartments which should pay, and add up their total price
	foreach ($price_to_pay as $username => $price) {
		/**
		 * @todo Check what happens if both current and past resident has a rental in the period, and there is a price adjustment. Do both users get the discount?
		*/
		$final_price[$username] = $price + (isset($price_adjustments[apartment_number_from_username($username)]) ? $price_adjustments[apartment_number_from_username($username)] : 0);
	}

	# Sort array by key (username)
	ksort($final_price);
	
	# Return price information
	return $final_price;
}

/**
 * Creates a new booking of the common house
 * 
 * @param array $params Array of arrays of options as key-value pairs. Keys are two-character language codes of the post. Values are key-value arrays with the followiung valid keys:
 * 		'title' 			string   - Title of the event. Default: ''
 *		'event_owner_id'	int      - Owner of the booking. Default: get_current_user_id()
 *		'start_date'		DateTime - Start-date of the event. Default: new DateTime('now', new DateTimeZone('Europe/Copenhagen'))
 *		'end_date'			DateTime - End-date of the event. Default: new DateTime('now', new DateTimeZone('Europe/Copenhagen'))
 *		'all_day'			bool     - All-day status of the event. Default: false
 *		'event_language'	string   - Language code for the event. Default: 'da_DK'
 *
 * @return array[int]|bool Key-value array. Keys are keys from $params. Values are IDs of the Wordpress-post of each created event if successful, and false if event could not be created.
 */
function add_common_house_booking($all_params = array()) {
	$posts_info = array_combine(
		array_keys($all_params), 
		array_map(
			function ($params_per_language) {
				global $wpdb;

				# Default values
				$default = array(
					'title' => '',
					'event_owner_id' => get_current_user_id(),
					'start_date' => new DateTime('now', new DateTimeZone('Europe/Copenhagen')),
					'end_date' => new DateTime('now', new DateTimeZone('Europe/Copenhagen')),
					'all_day' => false,
					'event_language' => 'da_DK',
				);

				# Combine default values and provided settings
				$params = shortcode_atts($default, $params_per_language);

				# Create Wordpress post
				$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => 'event',
					'post_title' => $params['title'],
					'post_content' => '',
					'post_date' => current_time("Y-m-d H:i:s"),
					'post_author' => $params['event_owner_id']
				) );

				# Protect event
				SwpmProtection::get_instance()->apply(array($post_id), 'custom_post')->save();
				$query = "SELECT id FROM " . $wpdb->prefix . "swpm_membership_tbl WHERE id !=1 ";
				$level_ids = $wpdb->get_col($query);
				foreach ($level_ids as $level) {
					SwpmPermission::get_instance($level)->apply(array($post_id), 'custom_post')->save();
				}

				# Set post language
				pll_set_post_language($post_id, $params['event_language']);

				# Set event details
				$event = new EM_Event($post_id, 'post_id');

				$event->__set('event_start_date', $params['start_date']->format("Y-m-d")); # Start date of event
				$event->__set('event_end_date', $params['end_date']->format("Y-m-d")); # End date of event
				$event->__set('event_start_time', ($params['all_day'] ? "00:00:00" : $params['start_date']->format("H:i:s"))); # Start time of event
				$event->__set('event_end_time', ($params['all_day'] ? "23:59:59" : $params['end_date']->format("H:i:s"))); # End time of event
				$event->__set('event_start', $params['start_date']->format("Y-m-d") . ' ' . $params['start_date']->format("H:i:s")); # Start of event
				$event->__set('event_end', $params['end_date']->format("Y-m-d") . ' ' . $params['end_date']->format("H:i:s")); # End of event
				$event->__set('start_date', $params['start_date']->format("Y-m-d")); # Start date of event
				$event->__set('end_date', $params['end_date']->format("Y-m-d")); # End date of event
				$event->__set('start_time', ($params['all_day'] ? "00:00:00" : $params['start_date']->format("H:i:s"))); # Start time of event
				$event->__set('end_time', ($params['all_day'] ? "23:59:59" : $params['end_date']->format("H:i:s"))); # End time of event
				$event->__set('event_all_day', $params['all_day']); # If event is an all-day event

				$event->__set('location_id', 0); # Location ID
				$event->__set('event_spaces', NULL); # Total amount of spaces on event
				$event->__set('event_private', 0); # Boolean, is event private
				$event->__set('event_language', $params['event_language']); # Language of event
				$event->__set('blog_id', get_current_blog_id()); # ID of blog, for multi-site installations
				$event->__set('event_owner', $params['event_owner_id']); # Language of event
				$event->set_status(1, true); # Publish event

				update_post_meta($event->post_id, '_event_approvals_count', 1); # Set approval status

				if ($event->validate() && $event->save_meta() && $event->save()) {
					return $post_id;
				} else {
					return false;
				}
			},
			array_values($all_params)
		)
	);

	if (array_product(array_values($posts_info)) > 0) {
		pll_save_post_translations( $posts_info );
	}

	return $posts_info;
}

/**
 * Deletes an existing booking of the common house
 * 
 * @param int $event_id ID of the common house booking to be deleted
 *
 * @return bool True if the event was deleted successfully
 */
function delete_common_house_booking($event_id) {
	# Get event
	$event = new EM_Event($event_id,'event_id');

	# Create post-admin object
	$event_post_admin = new EM_Event_Post_Admin();

	# Delete event
	$event_post_admin->before_delete_post($event->post_id);
	$event_post_admin->trashed_post($event->post_id);

	return wp_trash_post($event->post_id);
}

/**
 * Approves a common house booking
 * 
 * @param int $event_id ID of the common house booking to be approved
 *
 * @return bool True if the event was approved successfully
 */
function approve_common_house_booking($event_id) {
	# Find the correct event
	$event = new EM_Event($event_id,'event_id');
	
	# Publish event
	$event->set_status(1, true);

	return $event->save();
}

/**
 * Adds a price adjustment to a user
 * 
 * @param string $username Username to adjust price for
 * @param bool $is_archive True if the user is an archive user
 * @param int $price_change Amount to change the price by
 *
 * @return bool True if the price adjustment was added
 */
function add_common_house_booking_priceadjustment($username, $is_archive, $price_change) {
	global $wpdb;

	# Data for adjustment
	$data = array(
		'apartment' => $username . ($is_archive ? '_archive' : ''),
		'price_change' => intval($price_change),
		'change_date' => (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d')
	);

	# Insert adjustment into database
	return $wpdb->insert($wpdb->prefix . 'em_lejepris_ændringer', $data) > 0;
}