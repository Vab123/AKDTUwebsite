<?php

/**
 * @file Functionality related to the manipulation of user-info, such as apartment numbers, ids, usernames, as well as getting info about the types of users
 */

############################################################
#
# User-creation permit
#
/**
 * Checks if a user-creation permit already exists for a specific apartment
 * 
 * @param int $apartment_number Apartment number to check
 * 
 * @return bool True if a user-creation permit already exists for a specific apartment
 */
function user_creation_permit_exists($apartment_number) {
	global $wpdb;

	return $wpdb->query('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_membercreation WHERE apartment_number="'. $apartment_number . '" AND allow_creation_date >= "' . (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format("Y-m-d H:i:s") . '"') > 0;
}
#
/**
 * Creates a new user-creation permit for an apartment
 * 
 * @param int $apartment_number Apartment number of the apartment to create a new permit for
 * @param string $phone_number Phone number of the apartment as written in the transfer agreement
 * @param string $takeover_time Time of the apartment takeover, from the transfer agreement
 * 
 * @return int The number of permits created (0 on failure, 1 on success)
 */
function create_user_creation_permit($apartment_number, $phone_number, $takeover_time) {
	global $wpdb;

	# Data for new user
	$data = array(
		'apartment_number' => $apartment_number,
		'phone_number' => $phone_number,
		'allow_creation_date' => $takeover_time,
		'initial_reset' => false,
		'initial_takeover' => false
	);

	# Insert permit into database
	return $wpdb->insert($wpdb->prefix . 'swpm_allowed_membercreation', $data);
}
#
/**
 * Deletes an existing user-creation permit for an apartment, if one exists
 * 
 * @param int $apartment_number Apartment number of the apartment to create a new permit for
 * @param string $phone_number Phone number of the apartment as written in the transfer agreement
 * @param string $takeover_time Time of the apartment takeover, from the transfer agreement
 * 
 * @return int The number of permits deleted (0 on failure, 1 on success)
 */
function delete_user_creation_permit($apartment_number, $phone_number, $takeover_time) {
	global $wpdb;

	# Data for user
	$data = array(
		'apartment_number' => $apartment_number,
		'phone_number' => $phone_number,
		'allow_creation_date' => $takeover_time,
	);

	# Delete renter permission
	return $wpdb->delete($wpdb->prefix . 'swpm_allowed_membercreation',$data);
}
############################################################

############################################################
#
# Renter-creation permit
#
/**
 * Checks if a renter-creation permit already exists for a specific apartment
 * 
 * @param int $apartment_number Apartment number to check
 * 
 * @return bool True if a renter-creation permit already exists for a specific apartment
 */
function renter_creation_permit_exists($apartment_number) {
	global $wpdb;

	return $wpdb->query('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_rentercreation WHERE apartment_number="'. $apartment_number . '" AND allow_creation_date >= "' . (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format("Y-m-d H:i:s") . '"') > 0;
}
#
/**
 * Creates a new renter-creation permit for an apartment
 * 
 * @param int $apartment_number Apartment number of the apartment to create a new permit for
 * @param string $phone_number Phone number of the apartment as written in the transfer agreement
 * @param string $takeover_time Time of the apartment takeover, from the transfer agreement
 * 
 * @return int The number of permits created (0 on failure, 1 on success)
 */
function create_renter_creation_permit($apartment_number, $phone_number, $start_time, $end_time) {
	global $wpdb;

	# Data for user
	$data = array(
		'apartment_number' => $apartment_number,
		'phone_number' => $phone_number,
		'start_time' => $start_time,
		'end_time' => $end_time,
		'initial_reset' => false,
		'initial_takeover' => false
	);

	# Insert permit into database
	return $wpdb->insert($wpdb->prefix . 'swpm_allowed_rentercreation', $data);
}
#
/**
 * Deletes an existing renter-creation permit for an apartment, if one exists
 * 
 * @param int $apartment_number Apartment number of the apartment to create a new permit for
 * @param string $phone_number Phone number of the apartment as written in the transfer agreement
 * @param string $takeover_time Time of the apartment takeover, from the transfer agreement
 * 
 * @return int The number of permits deleted (0 on failure, 1 on success)
 */
function delete_renter_creation_permit($apartment_number, $phone_number, $start_time, $end_time) {
	global $wpdb;

	# Data for user
	$data = array(
		'apartment_number' => $apartment_number,
		'phone_number' => $phone_number,
		'start_time' => $start_time,
		'end_time' => $end_time,
	);

	# Delete renter permission
	return $wpdb->delete($wpdb->prefix . 'swpm_allowed_rentercreation', $data);
}
############################################################