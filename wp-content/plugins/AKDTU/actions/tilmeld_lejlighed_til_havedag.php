<?php

/**
 * @file Action to add a registration for an apartment to a garden day
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'tilmeld_havedag' && isset($_REQUEST['havedag_event_id']) && isset($_REQUEST['havedag_dato'])){
		add_signup_to_gardenday_for_apartment($_REQUEST['user'], $_REQUEST['havedag_event_id'], $_REQUEST['havedag_dato']);
	}
}

/**
 * Add a registration for an apartment to a garden day
 * 
 * @param int $apartment_number Apartment number
 * @param int $havedag_event_id Event id of the garden day
 * @param string $gardenday_date Date of the garden day, where the apartment should be signed up
 * 
 * @return bool True if apartment was added to the garden day successfully
 */
function add_signup_to_gardenday_for_apartment($apartment_number, $gardenday_event_id, $gardenday_date){
	# Check if the apartment number is valid
	if ($apartment_number > 0) {
		switch (add_apartment_to_gardenday($apartment_number, $gardenday_event_id, $gardenday_date)) {
			case -2:
				new AKDTU_notice('error', 'Lejlighed ' . $apartment_number . ' findes ikke.');
				return false;
			case -1:
				new AKDTU_notice('error','Lejlighed ' . $apartment_number . ' kunne ikke tilmeldes havedagen, da havedagen ikke findes.');
				return false;
			case 0:
				new AKDTU_notice('error', 'Lejlighed ' . $apartment_number . ' kunne ikke tilmeldes havedagen.');
				return false;
			case 1:
				new AKDTU_notice('success','Lejlighed ' . $apartment_number . ' er nu tilmeldt havedagen.');
				return true;
		}
	}
}
