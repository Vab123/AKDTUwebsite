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
function user_creation_permit_exists($apartment_number)
{
	global $wpdb;

	return $wpdb->query("SELECT apartment_number FROM {$wpdb->prefix}swpm_allowed_membercreation WHERE apartment_number = \"{$apartment_number}\" AND allow_creation_date >= \"{(new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s')}\"") > 0;
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
function create_user_creation_permit($apartment_number, $phone_number, $takeover_time)
{
	global $wpdb;

	# Data for new user
	$data = [
		'apartment_number' => $apartment_number,
		'phone_number' => $phone_number,
		'allow_creation_date' => $takeover_time,
		'initial_reset' => false,
		'initial_takeover' => false
	];

	# Insert permit into database
	return $wpdb->insert("{$wpdb->prefix}swpm_allowed_membercreation", $data);
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
function delete_user_creation_permit($apartment_number, $phone_number, $takeover_time)
{
	global $wpdb;

	# Data for user
	$data = [
		'apartment_number' => $apartment_number,
		'phone_number' => $phone_number,
		'allow_creation_date' => $takeover_time,
	];

	# Delete renter permission
	return $wpdb->delete("{$wpdb->prefix}swpm_allowed_membercreation", $data);
}
#
/**
 * Get information about moves
 * 
 * @param string[] $columns Columns to fetch for the moves
 * @param DateTime|null $date_before Date to get moves prior to, or null to get any moves
 * @param DateTime|null $date_after Date to get moves after, or null to get any moves
 * @param int|null $limit Maximum amount of moves to get, or null to get all found
 * @param bool|null $taken_over Taken over status for the moves to get, or null to get all found
 * @param int|null $reset_state Reset state for the moves to get, or null to get all found
 * @param string|null $orderby Order by string, or null to not sort
 * 
 * @return array|object|null Selected columns for moves
 */
function get_moves($columns = ['*'], $date_before = null, $date_after = null, $limit = null, $taken_over = null, $reset_state = 1, $orderby = "allow_creation_date DESC, apartment_number ASC") {
	global $wpdb;

	$wheres = [];

	if (!is_null($date_before)) {
		$wheres[] = "allow_creation_date <= \"{$date_before->format("Y-m-d H:i:s")}\"";
	}
	if (!is_null($date_after)) {
		$wheres[] = "allow_creation_date >= \"{$date_after->format("Y-m-d H:i:s")}\"";
	}
	if (!is_null($taken_over)) {
		$wheres[] = "initial_takeover = " .  intval($taken_over);
	}
	if (!is_null($reset_state)) {
		$wheres[] = "initial_reset = " .  intval($reset_state);
	}

	$query = "SELECT " . join(",", $columns) . " FROM {$wpdb->prefix}swpm_allowed_membercreation" . (count($wheres) > 0 ? " WHERE " . join(' AND ', $wheres) : "") . (!is_null($orderby) ? " ORDER BY {$orderby}" : "") . (!is_null($limit) ? " LIMIT {$limit}" : "");

	if (count($columns) > 1) {
		return $wpdb->get_results($query);
	}
	else {
		return $wpdb->get_col($query);
	}
}
#
/**
 * Get information about past moves
 * 
 * @param string[] $columns Columns to fetch for the past moves
 * @param int|null $limit Maximum amount of past moves to get, or null to get all found
 * @param bool|null $taken_over Taken over status for the past moves to get, or null to get all found
 * @param int|null $reset_state Reset state for the past moves to get, or null to get all found
 * @param string|null $orderby Order by string, or null to not sort
 * 
 * @return array|object|null Selected columns for past moves
 */
function get_past_moves($columns = ['*'], $limit = null, $taken_over = null, $reset_state = 1, $orderby = "allow_creation_date DESC, apartment_number ASC") {
	return get_moves($columns, new DateTime('now', new DateTimeZone('Europe/Copenhagen')), null, $limit, $taken_over, $reset_state, $orderby);
}
#
/**
 * Get information about future moves
 * 
 * @param string[] $columns Columns to fetch for the future moves
 * @param int|null $limit Maximum amount of future moves to get, or null to get all found
 * @param bool|null $taken_over Taken over status for the future moves to get, or null to get all found
 * @param int|null $reset_state Reset state for the future moves to get, or null to get all found
 * @param string|null $orderby Order by string, or null to not sort
 * 
 * @return array|object|null Selected columns for future moves
 */
function get_future_moves($columns = ['*'], $limit = null, $taken_over = null, $reset_state = null, $orderby = "allow_creation_date ASC, apartment_number ASC") {
	return get_moves($columns, null, new DateTime('now', new DateTimeZone('Europe/Copenhagen')), $limit, $taken_over, $reset_state, $orderby);
}
#
/**
 * Update an existing user creation permit with new values
 * 
 * @param mixed $apartment_num Apartment number to update
 * @param mixed $values Associate array of values to update
 * 
 * @return bool Number of updated permits, or false on error
 */
function update_user_permit($apartment_num, $values) {
	global $wpdb;

	return $wpdb->update($wpdb->prefix . 'swpm_allowed_membercreation', $values, ['apartment_number' => $apartment_num]);
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
function renter_creation_permit_exists($apartment_number)
{
	global $wpdb;

	return $wpdb->query("SELECT apartment_number FROM {$wpdb->prefix}swpm_allowed_rentercreation WHERE apartment_number = \"{$apartment_number}\" AND allow_creation_date >= \"{(new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s')}\"") > 0;
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
function create_renter_creation_permit($apartment_number, $phone_number, $start_time, $end_time)
{
	global $wpdb;

	# Data for user
	$data = [
		'apartment_number' => $apartment_number,
		'phone_number' => $phone_number,
		'start_time' => $start_time,
		'end_time' => $end_time,
		'initial_reset' => false,
		'initial_takeover' => false
	];

	# Insert permit into database
	return $wpdb->insert("{$wpdb->prefix}swpm_allowed_rentercreation", $data);
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
function delete_renter_creation_permit($apartment_number, $phone_number, $start_time, $end_time)
{
	global $wpdb;

	# Data for user
	$data = [
		'apartment_number' => $apartment_number,
		'phone_number' => $phone_number,
		'start_time' => $start_time,
		'end_time' => $end_time,
	];

	# Delete renter permission
	return $wpdb->delete("{$wpdb->prefix}swpm_allowed_rentercreation", $data);
}
#
/**
 * Get info about current renters
 * @param string[] $columns Columns to fetch for the renters
 * @param int|null $limit Maximum amount of renters to get, or null to get all found
 * 
 * @return array|object|null Selected columns for current renters
 */
function get_current_renters($columns = ['*'], $limit = null) {
	global $wpdb;

	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	$query = "SELECT " . join(",", $columns) . " FROM {$wpdb->prefix}swpm_allowed_rentercreation WHERE end_time >= \"{$now->format('Y-m-d H:i:s')}\" ORDER BY start_time ASC, apartment_number ASC" . (!is_null($limit) ? " LIMIT {$limit}" : "");

	if (count($columns) > 1) {
		return $wpdb->get_results($query);
	}
	else {
		return $wpdb->get_col($query);
	}
}
#
/**
 * Get info about expired renters
 * 
 * @param string[] $columns Columns to fetch for the renters
 * 
 * @return array|object|null Selected columns for expired renters
 */
function get_expired_renters($columns = ['*']) {
	global $wpdb;

	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	$query = "SELECT " . join(",", $columns) . " FROM {$wpdb->prefix}swpm_allowed_rentercreation WHERE initial_reset = 0 AND end_time <= \"{$now->format('Y-m-d H:i:s')}\"";

	if (count($columns) > 1) {
		return $wpdb->get_results($query);
	}
	else {
		return $wpdb->get_col($query);
	}
}
#
/**
 * Update an existing renter permit with new values
 * 
 * @param mixed $apartment_num Apartment number to update
 * @param mixed $values Associate array of values to update
 * 
 * @return bool Number of updated permits, or false on error
 */
function update_renter_permit($apartment_num, $values) {
	global $wpdb;

	return $wpdb->update($wpdb->prefix . 'swpm_allowed_rentercreation', $values, ['apartment_number' => $apartment_num]);
}
############################################################