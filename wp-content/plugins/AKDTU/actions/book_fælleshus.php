<?php

/**
 * @file Action to book the common house
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'book_fælleshus' && isset($_REQUEST['start_date']) && isset($_REQUEST['end_date']) && isset($_REQUEST['type'])) {
		# Check if the common house should be booked for the board
		if ($_REQUEST['type'] == 'bestyrelse') {
			# Book the common house for the board
			book_fælleshus_bestyrelse(wp_get_current_user(), $_REQUEST['name_da'], $_REQUEST['name_en'], new DateTime($_REQUEST['start_date']), new DateTime($_REQUEST['end_date']), isset($_REQUEST['all_day']));
		} elseif ($_REQUEST['type'] == 'beboer') {
			# Book the common house for a resident
			book_fælleshus_beboer(get_user_by('login', username_from_apartment_number($_REQUEST['user'])), common_house_rental_name($_REQUEST['user']), new DateTime($_REQUEST['start_date']), new DateTime($_REQUEST['end_date']));
		}
	}
}

/**
 * Books the common house for an apartment
 * 
 * @param WP_User $event_owner Owner of the rental
 * @param string $title Title of the rental
 * @param DateTime $start_date Start-date and -time of the rental
 * @param DateTime $end_date End-date and -time of the rental
 * 
 * @return bool True if the booking was successfully created
 */
function book_fælleshus_beboer($event_owner, $title, $start_date, $end_date) {
	# Check if end-date is before or the same as the start-date
	if ($end_date <= $start_date) {
		new AKDTU_notice('error','Sluttidspunkt skal være før starttidspunkt.');
		return false;
	}

	$params = array(
		'title' => $title,
		'event_owner_id' => $event_owner->ID,
		'start_date' => $start_date,
		'end_date' => $end_date,
		'all_day' => false,
		'event_language' => 'da_DK',
	);

	$new_event_id = book_common_house($params);

	if (is_numeric($new_event_id)) {
		new AKDTU_notice('success', 'Fælleshuset er nu reserveret.');

		return true;
	} else {
		# Saving failed. Write error message to the admin interface
		new AKDTU_notice('error', 'Fælleshuset kunne ikke reserveres.');

		return false;
	}
}

/**
 * Books the common house for the board
 * 
 * @param WP_User $event_owner Owner of the rental
 * @param string $title_da Danish title of the rental
 * @param string $title_en English title of the rental
 * @param DateTime $start_date Start-date and -time of the rental
 * @param DateTime $end_date End-date and -time of the rental
 * @param bool $all_day True if the event is an all-day event
 * 
 * @return bool True if the booking was successfully created
 */
function book_fælleshus_bestyrelse($event_owner, $title_da, $title_en, $start_date, $end_date, $all_day) {
	if ($end_date <= $start_date) {
		new AKDTU_notice('error','Sluttidspunkt skal være før starttidspunkt.');
		return false;
	}

	$params_da = array(
		'title' => $title_da,
		'event_owner_id' => $event_owner->ID,
		'start_date' => $start_date,
		'end_date' => $end_date,
		'all_day' => $all_day,
		'event_language' => 'da_DK',
	);
	
	$event_da_id = book_common_house($params_da);

	$params_en = array(
		'title' => $title_en,
		'event_owner_id' => $event_owner->ID,
		'start_date' => $start_date,
		'end_date' => $end_date,
		'all_day' => $all_day,
		'event_language' => 'en_US',
	);
	
	$event_en_id = book_common_house($params_en);

	if (is_numeric($event_da_id) && is_numeric($event_en_id)){ # Save event and event metadata
		pll_save_post_translations( array('en' => $event_en_id, 'da' => $event_da_id ) );
		new AKDTU_notice('success', 'Fælleshuset er nu reserveret.');

		return true;
	} else {
		new AKDTU_notice('error', 'Fælleshuset kunne ikke reserveres. Dansk begivenhed blev ' . (is_numeric($event_da_id) ? '' : 'ikke ') . 'oprettet. Engelsk begivenhed blev ' . (is_numeric($event_en_id) ? '' : 'ikke ') . 'oprettet.');
		
		return false;
	}
}
