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
function gardenday_price_not_signed_up($apartment_number, $garden_day_id) {
	if (em_get_event($garden_day_id, 'event_id')->rsvp_date >= "2022-09-18") {
		// Remove this check when the price changes the first time. This is only to show how this can be done in the future.
		return 750;
	}
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
function gardenday_price_not_showed_up($apartment_number, $garden_day_id) {
	if (em_get_event($garden_day_id, 'event_id')->rsvp_date >= "2022-09-18") {
		// Remove this check when the price changes the first time. This is only to show how this can be done in the future.
		return 750;
	}
}

/**
 * Finds the next garden day events.
 * 
 * @param string $language Slug of the language for the events found. 'all' for all languages. (Default: 'all')
 * @param int $amount Number of garden days to find. (Default: 1)
 * 
 * @return EM_Event[]|EM_Event[string]|null Null if no garden days were found. Otherwise, if $language is 'all', an array of key-value-arrays, with keys equal to the language slugs of the garden days and the EM_Events as values. Otherwise, an array of EM_Events.
 */
function next_gardenday($language = 'all', $amount = 1) {
	# Settings for lookup for garden days
	$scope = 'future';
	$search_limit = 20;
	$offset = 0;
	$order = 'ASC';
	$owner = false;

	# Get future garden days
	$events = EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0));

	# Check which language the caller walts
	if ($language == 'all') {
		# The user wants all languages. First find only Danish languages, to not have the same garden day multiple days when triggering on Danish and English events
		# Then find all translations of the Danish garden days and create an appropriate array
		$events = get_all_translations(remove_duplicate_events($events, false, "da"));
	}
	else {
		# Filter only the events with the required language
		$events = remove_duplicate_events($events, false, $language);
	}

	# Return events if any were found. Otherwise, return null
	return (count($events) > 0 ? array_slice($events, 0, $amount) : null);
}

/**
 * Finds the previous garden day events.
 * 
 * @param string $language Slug of the language for the events found. 'all' for all languages. (Default: 'all')
 * @param int $amount Number of garden days to find. (Default: 1)
 * 
 * @return EM_Event[]|EM_Event[string]|null Null if no garden days were found. Otherwise, if $language is 'all', an array of key-value-arrays, with keys equal to the language slugs of the garden days and the EM_Events as values. Otherwise, an array of EM_Events.
 */
function previous_gardenday($language = 'all', $amount = 1) {
	# Settings for lookup for garden days
	$scope = 'past';
	$search_limit = 20;
	$offset = 0;
	$order = 'DESC';
	$owner = false;

	# Get past garden days
	$events = EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0));

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
	}
	else {
		# Filter only the events with the required language
		$events = remove_duplicate_events($events, false, $language);
	}
		
	# Return events if any were found. Otherwise, return null
	return (count($events) > 0 ? array_slice($events, 0, $amount) : null);
}

/**
 * Finds any garden day event.
 * 
 * @param string $language Slug of the language for the events found. 'all' for all languages. (Default: 'all')
 * 
 * @return EM_Event[]|EM_Event|null If any future garden days exist, one of them is returned. Otherwise, if any past garden days exist, one of them is returned. Otherwise null.
 */
function any_gardenday($language = 'all') {
	$events = next_gardenday($language, 1);

	return (is_null($events) ? previous_gardenday($language, 1) : $events);
}

/**
 * Adds an apartment to a gardenday
 * 
 * @param int $apartment_number Apartment number to add to the gardenday
 * @param int $gardenday_event_id Id of the gardenday event to add the apartment to
 * @param int $gardenday_date Specific date of the garden day to add the apartment to
 * 
 * @return int -2 if the apartment number is not valid, -1 if gardenday date does not exist, 0 if user could not be signed up for the gardenday, 1 if user was added to the gardenday successfully
 */
function add_apartment_to_gardenday($apartment_number, $gardenday_event_id, $gardenday_date) {
	global $wpdb;

	if (!is_valid_apartment_number($apartment_number)) {
		return -2;
	}

	# Get the id of the user corresponding to the apartment
	$user_id = id_from_apartment_number($apartment_number);

	# Check if the apartment is already signed up to any of the garden days
	if ($wpdb->get_var("SELECT COUNT(*) FROM " . EM_BOOKINGS_TABLE . " WHERE event_id=" . $gardenday_event_id . " AND person_id=" . $user_id) > 0 . " AND status=1") {
		# Apartment is already signed up. Write warning message to admin interface
		new AKDTU_notice('warning','Lejlighed ' . $apartment_number . ' var allerede tilmeldt havedagen. Tilmelder igen.');
	}

	# Add booking to user
	$result = $wpdb->insert(EM_BOOKINGS_TABLE, array('event_id' => $gardenday_event_id, 'person_id' => $user_id, 'booking_spaces' => 1, 'booking_comment' => '', 'booking_date' => gmdate('Y-m-d H:i:s'), 'booking_status' => 1, 'booking_price' => 0, 'booking_tax_rate' => 0, 'booking_meta' => 'a:0:{}' ));

	# Add tickets to booking
	$result2 = $wpdb->insert(EM_TICKETS_BOOKINGS_TABLE, array( 'booking_id' => $wpdb->insert_id, 'ticket_id' => $gardenday_date, 'ticket_booking_spaces' => 1, 'ticket_booking_price' => 0 ) );
	
	# Get all tickets to all bookings for the garden day event
	$tickets = em_get_event($gardenday_event_id,'event_id')->get_bookings()->get_tickets()->tickets;

	# Check if new booking was successfully added
	if ($result === 1 && $result2 === 1 && isset($tickets[$gardenday_date])) {
		return 1;
	}else{
		# Booking was not added

		# Check if the garden day exists
		if (isset($tickets[$gardenday_date])){
			# Garden day exists, but user could not be signed up. Write error message to admin interface
			return 0;
		} else {
			# Garden day does not exist, and user could not be signed up. Write error message to admin interface
			return -1;
		}

		return false;
	}
}

/**
 * Removes an apartment from a gardenday
 * 
 * @param int $apartment_number Apartment number to remove from the gardenday
 * @param int $gardenday_event_id Id of the gardenday event to remove the apartment from
 * @param int $gardenday_date Specific date of the garden day to remove the apartment from
 * 
 * @return int -2 if the apartment number is not valid, -1 if gardenday date does not exist, 0 if user could not be removed from the gardenday, 1 if user was removed from the gardenday successfully
 */
function remove_apartment_from_gardenday($apartment_number, $gardenday_event_id, $gardenday_date) {
	global $wpdb;

	if (!is_valid_apartment_number($apartment_number)) {
		return -2;
	}

	# Get the id of the user corresponding to the apartment
	$user_id = id_from_apartment_number($apartment_number);

	# Get the garden day event
	$event = em_get_event($gardenday_event_id,'event_id');
	
	# Get all bookings for the garden day
	$bookings = $event->get_bookings();

	# Get all tickets for the garden day
	$tickets = $bookings->get_tickets()->tickets;

	# Get the bookings of the user to the garden day
	$booking_ids = $wpdb->get_col("SELECT booking_id FROM " . EM_BOOKINGS_TABLE . " WHERE event_id=" . $gardenday_event_id . " AND person_id=" . $user_id);

	# Check if the garden day is valid, and the user is currently signed up
	if (isset($tickets[$gardenday_date]) && count($booking_ids) > 0){
		# Remove tickets from the booking
		$result2 = $wpdb->delete(EM_TICKETS_BOOKINGS_TABLE, array( 'booking_id' => $booking_ids[0], 'ticket_id' => $gardenday_date ) );

		# Remove the booking from the user
		$result = $wpdb->delete(EM_BOOKINGS_TABLE, array('event_id' => $gardenday_event_id, 'person_id' => $user_id ));

		# Write success message to admin interface
		if ($result && $result2) {
			return 1;
		}
		else {
			return 0;
		}
	} else {
		# Something went wrong. Output an error message
		return -1;
	}
}

?>