<?php

require_once WP_PLUGIN_DIR . '/AKDTU/functions/users.php';

function calc_rental_cost($startdatetime, $enddatetime, $owner_id) {
	global $wpdb;
	## Returns how much it costs in DKK to rent the common house from $startdatetime to $enddatetime.
	## $startdatetime is a PHP DateTime object
	## $enddatetime is a PHP DateTime object
	##

	## Check if event-owner is board-member
	if (
		!in_array(SwpmMembershipLevelUtils::get_membership_level_name_of_a_member(SwpmMemberUtils::get_user_by_user_name( get_user_by('id',$owner_id)->user_login )->member_id), array('Beboer','Midlertidig lejer','Archive bruger')) ||
		$wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'AKDTU_boardmembers WHERE apartment_number = "' . substr(get_user_by('id',$owner_id)->user_login,4,3) . '" AND start_datetime <= "' . $startdatetime->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $enddatetime->format('Y-m-d H:i:s') . '"')) > 0
	){
		## Event owner is/was board member, and does not have to pay
		return 0;
	}
	
	if (
		in_array(SwpmMembershipLevelUtils::get_membership_level_name_of_a_member(SwpmMemberUtils::get_user_by_user_name( get_user_by('id',$owner_id)->user_login )->member_id), array('Vicevært'))
	){
		## Event owner is vicevært, and does not have to pay
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
	global $wpdb;

	$event_ids = $wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_end_date >= "' . $month_ini->format('Y-m-d') . '" AND event_end_date <= "' . $month_end->format('Y-m-d') . '" AND event_status = 1');

	// Get array of events ending in this month
	$events = array_map(function($id){return em_get_event($id,'event_id');},$event_ids);

	$price_to_pay = array();

	foreach ($events as $event) {
		$owner = apartment_number_from_id($event->owner);
		
		$event_start = new DateTime($event->event_start_date . " " . $event->event_start_time);
		$event_end = new DateTime($event->event_end_date . " " . $event->event_end_time);

		if (isset( $price_to_pay[$owner] )) {
			$price_to_pay[$owner] += calc_rental_cost($event_start, $event_end, $event->owner);
		} else {	
			$price_to_pay[$owner] = calc_rental_cost($event_start, $event_end, $event->owner);
		}
	}

	ksort($price_to_pay);

	$price_to_pay = array_filter($price_to_pay,function($val){return $val > 0;});

	return $price_to_pay;
}

function get_price_adjustments($month_ini, $month_end) {
	global $wpdb;

	$changes = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'em_lejepris_ændringer WHERE change_date >= "' . $month_ini->format('Y-m-d') . '" AND change_date <= "' . $month_end->format('Y-m-d') . '"');

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
	$final_price = array();

	foreach ($price_to_pay as $apartment => $price) {
		$final_price[$apartment] = $price + (isset($price_adjustments[$apartment]) ? $price_adjustments[$apartment] : 0);
	}

	ksort($final_price);
	
	return $final_price;
}
