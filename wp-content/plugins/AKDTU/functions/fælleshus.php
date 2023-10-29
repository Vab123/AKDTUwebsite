<?php

function calc_rental_cost($startdatetime, $enddatetime, $owner_id) {
	global $wpdb;
	## Returns how much it costs in DKK to rent the common house from $startdatetime to $enddatetime.
	## $startdatetime is a PHP DateTime object
	## $enddatetime is a PHP DateTime object
	##

	## Check if event-owner is not a regular apartment user, or a board member
	if (
		!is_apartment_from_id($owner_id) || was_boardmember_from_id($owner_id, $enddatetime)
	){
		## Event owner is not a regular apartment user
		return 0;
	}

	## Round start date to appropriate 12:00
	if ($startdatetime->format('H:i:s') != '12:00:00') {
		## Round to 12:00
		if ($startdatetime->format('H:i:s') < '12:00:00') {
			## Subtract 1 day
			$startdatetime->sub(new DateInterval('P1D'));
		}
		## Set time to 12:00
		$startdatetime->setTime(12,0,0,0);
	}

	## Round end date to appropriate 12:00
	if ($enddatetime->format('H:i:s') != '12:00:00') {
		## Round to 12:00

		if ($enddatetime->format('H:i:s') > '12:00:00') {
			## Add 1 day
			$enddatetime->add(new DateInterval('P1D'));
		}
		## Set time to 12:00
		$enddatetime->setTime(12,0,0,0);
	}

	## Calculate difference in seconds
	$diff_in_seconds = $enddatetime->getTimestamp() - $startdatetime->getTimestamp();
	
	## Get difference in days
	$diff_in_days = ceil( $diff_in_seconds / (60 * 60 * 24));
	
	## Return price
	return 100 * $diff_in_days + 100;
}

function get_price_to_pay($month_ini, $month_end) {
	## username -> price
	global $wpdb;

	$event_ids = $wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_end_date >= "' . $month_ini->format('Y-m-d') . '" AND event_end_date <= "' . $month_end->format('Y-m-d') . '" AND event_status = 1');

	// Get array of events ending in this month
	$events = array_map(function($id){return em_get_event($id,'event_id');},$event_ids);

	$price_to_pay = array();

	foreach ($events as $event) {
		$username = username_from_id($event->owner);
		
		$event_start = new DateTime($event->event_start_date . " " . $event->event_start_time);
		$event_end = new DateTime($event->event_end_date . " " . $event->event_end_time);

		if (isset( $price_to_pay[$username] )) {
			$price_to_pay[$username] += calc_rental_cost($event_start, $event_end, $event->owner);
		} else {	
			$price_to_pay[$username] = calc_rental_cost($event_start, $event_end, $event->owner);
		}
	}

	ksort($price_to_pay);

	$price_to_pay = array_filter($price_to_pay,function($val){return $val > 0;});

	return $price_to_pay;
}

function get_price_adjustments($time_start, $time_end) {
	## Returns the price adjustments for rental of the common house, from $time_start to $time_end
	## apartment number -> price change
	global $wpdb;

	$changes = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'em_lejepris_ændringer WHERE change_date >= "' . $time_start->format('Y-m-d') . '" AND change_date <= "' . $time_end->format('Y-m-d') . '"');

	$price_adjustments = array();

	foreach ($changes as $change) {
		if (isset( $price_adjustments[$change->apartment] )) {
			$price_adjustments[$change->apartment] += $change->price_change;
		} else {	
			$price_adjustments[$change->apartment] = $change->price_change;
		}
	}

	ksort($price_adjustments);

	return $price_adjustments;
}

function get_final_price($price_to_pay, $price_adjustments) {
	## username -> final price
	$final_price = array();

	foreach ($price_to_pay as $username => $price) {
		$final_price[$username] = $price + (isset($price_adjustments[apartment_number_from_username($username)]) ? $price_adjustments[apartment_number_from_username($username)] : 0);
	}

	ksort($final_price);
	
	return $final_price;
}
