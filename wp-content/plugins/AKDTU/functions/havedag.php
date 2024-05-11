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
 * @return EM_Event[]|EM_Event[string]|null Garden day events.
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
 * @return EM_Event[]|EM_Event[string]|null Garden day events.
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

?>