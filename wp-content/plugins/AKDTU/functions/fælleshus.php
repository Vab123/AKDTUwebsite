<?php

/**
 * @file Functionality related to the billing of residents for booking the common house
 */

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
					if (enddate.getMonth() + 1 < 4 || enddate.getFullYear < 2024) {
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
	if (!is_apartment_from_id($owner_id) || was_boardmember_from_id($owner_id, $enddatetime)){
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
	
	if (intval($enddatetime->format('m')) < 4 || intval($enddatetime->format('Y')) < 2024) {	# Price was changed 1/4/2024. These are the prices from before this date.
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
	$events = array_map(function($id){return em_get_event($id,'event_id');},$event_ids);

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
