<?php

/**
 * @file Action to adjust the price of a rental of the common house
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_juster_pris' && isset($_REQUEST['user']) && isset($_REQUEST['price_change'])) {
		fælleshus_juster_pris($_REQUEST['user'],isset($_REQUEST['archive']),$_REQUEST['price_change']);
	}
}

/**
 * Add a price-adjustment to a rental of the common house
 * 
 * @param string $username Username for the user recieving the price adjustment
 * @param bool $is_archive Flag, true if the user is an archive user
 * @param int $price_change Amount to adjust the price by
 * 
 * @return bool True if the price-adjustment was successfully saved
 */
function fælleshus_juster_pris($username, $is_archive, $price_change){
	# Check if insertion was successful
	if (add_common_house_booking_priceadjustment($username, $is_archive, $price_change)) {
		# Insertion succeeded. Write success message to admin interface
		new AKDTU_notice('success', 'Ændringen i opkrævning af leje blev gemt.');

		return true;
	} else {
		# Insertion failed. Write error message to admin interface
		new AKDTU_notice('error', "Ændringen i leje blev ikke gemt");

		return false;
	}
}
