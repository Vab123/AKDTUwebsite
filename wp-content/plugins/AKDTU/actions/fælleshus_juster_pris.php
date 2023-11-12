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
 */
function fælleshus_juster_pris($username, $is_archive, $price_change){
	global $wpdb;

	# Data for adjustment
	$data = array(
		'apartment' => $username . ($is_archive ? '_archive' : ''),
		'price_change' => intval($price_change),
		'change_date' => (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d')
	);

	# Insert adjustment into database
	$inserted = $wpdb->insert($wpdb->prefix . 'em_lejepris_ændringer', $data);

	# Check if insertion was successful
	if ($inserted == 0) {
		# Insertion failed. Write error message to admin interface
		new AKDTU_notice('error',$wpdb->last_error);
	}else{
		# Insertion succeeded. Write success message to admin interface
		new AKDTU_notice('success','Ændringen i opkrævning af leje blev gemt.');
	}
}
