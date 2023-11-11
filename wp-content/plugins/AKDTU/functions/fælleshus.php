<?php

/**
 * @file Functionality related to the billing of residents for booking the common house
 */

/**
 * Calculates the rental cost for renting the common house for a period.
 * 
 * Can handle starting- and ending-times not being exactly 12:00
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
	
	# Return price: 200 for first day, and 100 for subsequent days
	return 100 * $diff_in_days + 100;
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
