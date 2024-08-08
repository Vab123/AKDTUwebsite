<?php

/**
 * @file Functionality related to the creation of new users on the website
 */

############################################################
#
# User-creation permit
#
/**
 * Checks if a user-creation permit exists given criteria
 * @param array $where Key-value array, where keys have to be equal to values in the sql-query.
 * @param null|DateTime $timelimit Latest time for user-creation permit
 * @return bool True if a permit exists with the defined criteria
 */
function user_creation_permit_exists_where($where, $timelimit = null)
{
	global $wpdb;

	$timelimit ??= new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	$where_string = "";
	foreach ($where as $key => $value) {
		$where_string .= " AND {$key} = \"{$value}\"";
	}

	$timelimit_string = $timelimit->format('Y-m-d H:i:s');

	return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}swpm_allowed_membercreation WHERE allow_creation_date <= \"{$timelimit_string}\"" . $where_string) > 0;
}
#
/**
 * Creates a new user-creation permit for an apartment
 * 
 * @param int $apartment_number Apartment number of the apartment to create a new permit for
 * @param string $email Email adress of the apartment as written in the transfer agreement
 * @param string $takeover_time Time of the apartment takeover, from the transfer agreement
 * 
 * @return int The number of permits created (0 on failure, 1 on success)
 */
function create_user_creation_permit($apartment_number, $email, $takeover_time)
{
	global $wpdb;

	# Data for new user
	$data = [
		'apartment_number' => $apartment_number,
		'email' => $email,
		'allow_creation_date' => $takeover_time,
		'reset' => false,
		'takeover' => false
	];

	# Insert permit into database
	return $wpdb->insert("{$wpdb->prefix}swpm_allowed_membercreation", $data);
}
#
/**
 * Deletes an existing user-creation permit for an apartment, if one exists
 * 
 * @param int $apartment_number Apartment number of the apartment to create a new permit for
 * @param string $email Email adress of the apartment as written in the transfer agreement
 * @param string $takeover_time Time of the apartment takeover, from the transfer agreement
 * 
 * @return int The number of permits deleted (0 on failure, 1 on success)
 */
function delete_user_creation_permit($apartment_number, $email, $takeover_time)
{
	global $wpdb;

	# Data for user
	$data = [
		'apartment_number' => $apartment_number,
		'email' => $email,
		'allow_creation_date' => $takeover_time,
	];

	# Delete renter permission
	return $wpdb->delete("{$wpdb->prefix}swpm_allowed_membercreation", $data);
}
#
/**
 * Get info about current user-creation permits
 * @param string[] $columns Columns to fetch for the user-creation permits
 * @param int|null $limit Maximum amount of user-creation permits to get, or null to get all found
 * 
 * @return array|object|null Selected columns for current user-creation permits
 */
function get_current_user_permits($columns = ['*'], $limit = null) {
	global $wpdb;

	$query = "SELECT " . join(",", $columns) . " FROM {$wpdb->prefix}swpm_allowed_membercreation WHERE takeover=0 ORDER BY allow_creation_date ASC, apartment_number ASC" . (!is_null($limit) ? " LIMIT {$limit}" : "");

	if (count($columns) > 1 || $columns[0] == '*') {
		return $wpdb->get_results($query);
	}
	else {
		return $wpdb->get_col($query);
	}
}
/**
 * Get info about expired user-creation permits
 * @param string[] $columns Columns to fetch for the user-creation permits
 * @param int|null $limit Maximum amount of user-creation permits to get, or null to get all found
 * 
 * @return array|object|null Selected columns for expired user-creation permits
 */
function get_expired_user_permits($columns = ['*'], $limit = null) {
	global $wpdb;

	$query = "SELECT " . join(",", $columns) . " FROM {$wpdb->prefix}swpm_allowed_membercreation WHERE takeover=1 ORDER BY allow_creation_date ASC, apartment_number ASC" . (!is_null($limit) ? " LIMIT {$limit}" : "");

	if (count($columns) > 1 || $columns[0] == '*') {
		return $wpdb->get_results($query);
	}
	else {
		return $wpdb->get_col($query);
	}
}
#
/**
 * Get information about moves
 * 
 * @param string[] $columns Columns to fetch for the moves
 * @param DateTime|null $date_before Date to get moves prior to, or null to get any moves
 * @param DateTime|null $date_after Date to get moves after, or null to get any moves
 * @param int|null $limit Maximum amount of moves to get, or null to get all found
 * @param string|null $orderby Order by string, or null to not sort
 * 
 * @return array|object|null Selected columns for moves
 */
function get_moves($columns = ['*'], $date_before = null, $date_after = null, $limit = null, $orderby = "move_date DESC, apartment_number ASC") {
	global $wpdb;

	$wheres = [];

	if (!is_null($date_before)) {
		$wheres[] = "move_date <= \"{$date_before->format("Y-m-d H:i:s")}\"";
	}
	if (!is_null($date_after)) {
		$wheres[] = "move_date >= \"{$date_after->format("Y-m-d H:i:s")}\"";
	}

	$query = "SELECT " . join(",", $columns) . " FROM {$wpdb->prefix}AKDTU_moves" . (count($wheres) > 0 ? " WHERE " . join(' AND ', $wheres) : "") . (!is_null($orderby) ? " ORDER BY {$orderby}" : "") . (!is_null($limit) ? " LIMIT {$limit}" : "");

	if (count($columns) > 1 || $columns[0] == '*') {
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
 * @param string|null $orderby Order by string, or null to not sort
 * 
 * @return array|object|null Selected columns for past moves
 */
function get_past_moves($columns = ['*'], $limit = null, $orderby = "move_date DESC, apartment_number ASC") {
	return get_moves($columns, new DateTime('now', new DateTimeZone('Europe/Copenhagen')), null, $limit, $orderby);
}
#
/**
 * Get information about future moves
 * 
 * @param string[] $columns Columns to fetch for the future moves
 * @param int|null $limit Maximum amount of future moves to get, or null to get all found
 * @param string|null $orderby Order by string, or null to not sort
 * 
 * @return array|object|null Selected columns for future moves
 */
function get_future_moves($columns = ['*'], $limit = null, $orderby = "move_date ASC, apartment_number ASC") {
	return get_moves($columns, null, new DateTime('now', new DateTimeZone('Europe/Copenhagen')), $limit, $orderby);
}
#
/**
 * Adds a new move to the database
 * @param string $apartment Apartment number of the move
 * @param DateTime $datetime Date and time of the move
 * @return bool True if the move was added successfully
 */
function add_move($apartment, $datetime) {
	global $wpdb;

	return $wpdb->insert("{$wpdb->prefix}AKDTU_moves", ["apartment_number" => $apartment, "move_date" => $datetime->format("Y-m-d H:i:s"), "reset" => false]) > 0;
}
#
/**
 * Deletes an existing move from the database
 * @param string $apartment Apartment number of the move
 * @param DateTime $datetime Date and time of the move
 * @return bool True if the move was deleted successfully
 */
function delete_move($apartment, $datetime) {
	global $wpdb;

	return $wpdb->delete("{$wpdb->prefix}AKDTU_moves", ["apartment_number" => $apartment, "move_date" => $datetime->format("Y-m-d H:i:s")]) > 0;
}
#
/**
 * Marks a move as done
 * @param string $apartment Apartment number of the move
 * @param DateTime $datetime Date and time of the move
 * @return bool True if the move was marked as done successfully
 */
function mark_move_as_done($apartment, $datetime) {
	global $wpdb;

	return $wpdb->query("UPDATE {$wpdb->prefix}AKDTU_moves SET reset={false} WHERE apartment_number={$apartment} AND move_date=\"{$datetime->format('Y-m-d H:i:s')}\"") > 0;
}
#
/**
 * Marks a move as done
 * @return object Object of moves to be reset
 */
function get_moves_to_reset() {
	global $wpdb;

	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}AKDTU_moves WHERE move_date < \"{$now->format('Y-m-d H:i:s')}\" AND reset=0");
}
#
/**
 * Update an existing user creation permit with new values
 * 
 * @param mixed $email Email adress of the user to update
 * @param mixed $values Associate array of values to update
 * 
 * @return bool Number of updated permits, or false on error
 */
function update_user_permit($email, $values) {
	global $wpdb;

	return $wpdb->update($wpdb->prefix . 'swpm_allowed_membercreation', $values, ['email' => $email]);
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
function renter_creation_permit_exists_where($where)
{
	global $wpdb;
	
	$where_string = "";
	foreach ($where as $key => $value) {
		$where_string .= " AND {$key} = \"{$value}\"";
	}

	return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}swpm_allowed_rentercreation WHERE allow_creation_date <= \"{(new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s')}\"" . $where_string) > 0;
}
#
/**
 * Creates a new renter-creation permit for an apartment
 * 
 * @param int $apartment_number Apartment number of the apartment to create a new permit for
 * @param string $email Email adress of the apartment as written in the transfer agreement
 * @param string $takeover_time Time of the apartment takeover, from the transfer agreement
 * 
 * @return int The number of permits created (0 on failure, 1 on success)
 */
function create_renter_creation_permit($apartment_number, $email, $start_time, $end_time)
{
	global $wpdb;

	# Data for user
	$data = [
		'apartment_number' => $apartment_number,
		'email' => $email,
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
 * @param string $email Email adress of the apartment as written in the transfer agreement
 * @param string $takeover_time Time of the apartment takeover, from the transfer agreement
 * 
 * @return int The number of permits deleted (0 on failure, 1 on success)
 */
function delete_renter_creation_permit($apartment_number, $email, $start_time, $end_time)
{
	global $wpdb;

	# Data for user
	$data = [
		'apartment_number' => $apartment_number,
		'email' => $email,
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

	if (count($columns) > 1 || $columns[0] == '*') {
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

	$query = "SELECT " . join(",", $columns) . " FROM {$wpdb->prefix}swpm_allowed_rentercreation WHERE end_time <= \"{$now->format('Y-m-d H:i:s')}\"";

	if (count($columns) > 1 || $columns[0] == '*') {
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
 * @param mixed $email Email adress of the renter permit to update
 * @param mixed $values Associate array of values to update
 * 
 * @return bool Number of updated permits, or false on error
 */
function update_renter_permit($email, $values) {
	global $wpdb;

	return $wpdb->update($wpdb->prefix . 'swpm_allowed_rentercreation', $values, ['email' => $email]);
}
############################################################

############################################################
#
# User registration handling
#
/**
 * Handles the creation of a new user on the website
 * @param string $user_login Requested username of the user to be created
 * @param string $apartment Apartment number of the user to be created
 * @param bool $is_temporary_renter True if the user is being created for a temporary renter
 * @param string $phone Phone number of the new user
 * @param string $email Email adress of the new user
 * @param string $first_name First name of the new user
 * @param string $last_name Last name of the new user
 * @param string $password Password of the new user
 * @return void
 */
function AKDTU_user_registration($user_login, $apartment, $is_temporary_renter, $phone, $email, $first_name, $last_name, $password) {
	global $wpdb;

	if (username_exists($user_login)) {
		$message = [
			'succeeded' => false,
			'message'   => pll__('A user with that username already exists.', 'simple-membership'),
		];
		SwpmTransfer::get_instance()->set('status', $message);
		return;
	}

	$user_permit_settings = [
		"apartment_number" => $apartment,
		"email" => $email,
		"takeover" => 0
	];

	$user_creation_is_permitted = $is_temporary_renter ? renter_creation_permit_exists_where($user_permit_settings) : user_creation_permit_exists_where($user_permit_settings);

	echo $user_creation_is_permitted;

	if (!$user_creation_is_permitted) {
		$message = [
			'succeeded' => false,
			'message'   => pll__('Creating a user for this apartment is currently not permitted.', 'simple-membership'),
		];
		SwpmTransfer::get_instance()->set('status', $message);
		return;
	}

	//If captcha is present and validation failed, it returns an error string. If validation succeeds, it returns an empty string.
	$captcha_validation_output = apply_filters('swpm_validate_registration_form_submission', '');
	if (!empty($captcha_validation_output)) {
		$message = [
			'succeeded' => false,
			'message'   => SwpmUtils::_('Security check: captcha validation failed.'),
		];
		SwpmTransfer::get_instance()->set('status', $message);
		return;
	}

	## Check if email already exists
	if (email_exists($email)) {
		$message = [
			'succeeded' => false,
			'message'   => pll__('Email already exists.', 'simple-membership'),
		];
		SwpmTransfer::get_instance()->set('status', $message);
		return;
	}

	## Verify that apartment number is valid
	$user_id = wp_create_user($user_login, $password, $email);
	if (is_wp_error($user_id)) {
		$message = [
			'succeeded' => false,
			'message'   => "Errors: " . join(", ", $user_id->errors),
		];
		SwpmTransfer::get_instance()->set('status', $message);
		return;
	}

	// Update password
	$args = [
		'ID'         => $user_id,
		'user_email' => $email,
		'first_name' => $first_name,
		'last_name' => $last_name,
		'display_name' => "{$first_name} {$last_name}",
	];
	wp_update_user($args);

	if ($is_temporary_renter) {
		update_renter_permit($email, ['takeover' => 1]);
	} else {
		update_user_permit($email, ['takeover' => 1]);
	}

	global $AKDTU_USER_TYPES;
	global $KNET_USER_TYPES;
	update_user_meta($user_id, 'apartment_number', $apartment);
	update_user_meta($user_id, 'user_type', $AKDTU_USER_TYPES['none']['id']);
	update_user_meta($user_id, 'knet_type', $KNET_USER_TYPES['none']['id']);
	update_user_meta($user_id, 'is_active', true);

	$login_page_url = SwpmSettings::get_instance()->get_value('login-page-url');

	// Allow hooks to change the value of login_page_url
	$login_page_url = apply_filters('swpm_register_front_end_login_page_url', $login_page_url);

	$after_rego_msg = '<div class="swpm-registration-success-msg">' . sprintf(pll__('Registration completed message', 'simple-membership'), $user_login, $login_page_url) . (NYBRUGER_BRUGER_TOGGLE == "on" ? " " . pll__('Confirmation email sent', 'simple-membership') : '') . '</div>';
	$after_rego_msg = apply_filters('swpm_registration_success_msg', $after_rego_msg);
	$message        = [
		'succeeded' => true,
		'message'   => $after_rego_msg,
	];

	########################
	## Mail to new user
	########################
	if (NYBRUGER_BRUGER_TOGGLE == "on") {
		$content_replaces = [
			'#APT' => $apartment,
			'#NEWLOGIN' => $user_login,
			'#NEWEMAIL' => $email,
			'#NEWFIRSTNAME' => $first_name,
			'#NEWLASTNAME' => $last_name,
		];

		$subject_replaces = [
			'#APT' => $apartment,
			'#NEWLOGIN' => $user_login,
			'#NEWEMAIL' => $email,
			'#NEWFIRSTNAME' => $first_name,
			'#NEWLASTNAME' => $last_name,
		];

		send_AKDTU_email(false, $subject_replaces, $content_replaces, 'NYBRUGER_BRUGER' . (pll_current_language() == "da" ? '_DA' : '_EN'), $email);
	}

	########################
	## Mail to admins
	########################
	$content_replaces = [
		'#APT' => $apartment,
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $email,
		'#NEWFIRSTNAME' => $first_name,
		'#NEWLASTNAME' => $last_name,
	];

	$subject_replaces = [
		'#APT' => $apartment,
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $email,
		'#NEWFIRSTNAME' => $first_name,
		'#NEWLASTNAME' => $last_name,
	];

	send_AKDTU_email(false, $subject_replaces, $content_replaces, 'NYBRUGER_BESTYRELSE');

	SwpmTransfer::get_instance()->set('status', $message);

	return;
}
############################################################