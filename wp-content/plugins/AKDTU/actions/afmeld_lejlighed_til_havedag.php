<?php

/**
 * @file Action to delete a registration for an apartment to a garden day
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'afmeld_havedag' && isset($_REQUEST['havedag_event_id']) && isset($_REQUEST['havedag_dato']) && isset($_REQUEST['user'])){
		remove_signup_to_gardenday_for_apartment($_REQUEST['user'], $_REQUEST['havedag_event_id'], $_REQUEST['havedag_dato']);
	}
}

/**
 * Delete a registration for an apartment to a garden day
 * 
 * @param int $apartment_number Apartment number
 * @param int $havedag_event_id Event id of the garden day
 * @param string $havedag_dato Date of the garden day, where the apartment should no longer be signed up
 * 
 * @return bool True if the registration was deleted successfully
 */
function remove_signup_to_gardenday_for_apartment($apartment_number, $gardenday_event_id, $gardenday_date){
	# Check if the apartment number is valid
	if ($apartment_number > 0) {
		switch (remove_apartment_from_gardenday($apartment_number, $gardenday_event_id, $gardenday_date)) {
			case -2:
				new AKDTU_notice('error', 'Lejlighed ' . $apartment_number . ' findes ikke.');
				return false;
			case -1:
				new AKDTU_notice('error', 'Lejlighed ' . $apartment_number . ' var i forvejen ikke tilmeldt havedagen.');
				return false;
			case 0:
				new AKDTU_notice('error', 'Lejlighed ' . $apartment_number . ' kunne ikke fjernes fra havedagen.');
				return false;
			case 1:
				new AKDTU_notice('success', 'Lejlighed ' . $apartment_number . ' er nu ikke længere tilmeldt havedagen.');
				return true;
		}
	}
}
