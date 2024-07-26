<?php

/**
 * @file Functionality related to the creation of new users on the website
 */

############################################################
#
# User-creation permit
#

function user_creation_permit_exists_where($where)
{
	global $wpdb;

	$where_string = "";
	foreach ($where as $key => $value) {
		$where_string .= " AND {$key} = \"{$value}\"";
	}

	return $wpdb->query("SELECT apartment_number FROM {$wpdb->prefix}swpm_allowed_membercreation WHERE allow_creation_date >= \"{(new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s')}\"" . $where_string) > 0;
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
function renter_creation_permit_exists_where($where)
{
	global $wpdb;
	
	$where_string = "";
	foreach ($where as $key => $value) {
		$where_string .= " AND {$key} = \"{$value}\"";
	}

	return $wpdb->query("SELECT apartment_number FROM {$wpdb->prefix}swpm_allowed_rentercreation WHERE allow_creation_date >= \"{(new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s')}\"" . $where_string) > 0;
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

function AKDTU_user_registration($apartment, $is_temporary_renter, $phone, $email) {
	global $wpdb;

	$current_time = new DateTime("now", new DateTimeZone('Europe/Copenhagen'));
	$current_time->setTimezone(new DateTimeZone('UTC'));
	$current_time = $current_time->format("Y-m-d H:i:s");

	$user_permit_settings = [
		"apartment_number" => $apartment,
		"phone_number" => $phone,
	];

	$user_creation_is_permitted = !($is_temporary_renter ? renter_creation_permit_exists_where($user_permit_settings) : user_creation_permit_exists_where($user_permit_settings));

	if (!$user_creation_is_permitted) {
		$message = array(
			'succeeded' => false,
			'message'   => pll__('Creating a user for this apartment is currently not permitted.', 'simple-membership'),
		);
		SwpmTransfer::get_instance()->set('status', $message);
		return;
	}

	//If captcha is present and validation failed, it returns an error string. If validation succeeds, it returns an empty string.
	$captcha_validation_output = apply_filters('swpm_validate_registration_form_submission', '');
	if (!empty($captcha_validation_output)) {
		$message = array(
			'succeeded' => false,
			'message'   => SwpmUtils::_('Security check: captcha validation failed.'),
		);
		SwpmTransfer::get_instance()->set('status', $message);
		return;
	}

	## Check if email already exists
	if (email_exists($email)) {
		$message = array(
			'succeeded' => false,
			'message'   => pll__('Email already exists.', 'simple-membership'),
		);
		SwpmTransfer::get_instance()->set('status', $message);
		return;
	}

	## Verify that apartment number is valid
	$user_login = 'lejl' . str_pad($_POST['apartment_number'], 3, "0", STR_PAD_LEFT) . ($is_temporary_renter ? '_lejer' : '');
	$user = get_user_by('login', $user_login);
	if (!$user) {
		$message = array(
			'succeeded' => false,
			'message'   => pll__('Wrong apartment number recieved.', 'simple-membership'),
		);
		SwpmTransfer::get_instance()->set('status', $message);
		return;
	}
	$user_id = $user->ID;

	// Update password
	wp_set_password($_POST['password'], $user_id);
	$args = [
		'ID'         => $user_id,
		'user_email' => $_POST['email'],
		'first_name' => $_POST['first_name'],
		'last_name' => $_POST['last_name'],
		'display_name' => $_POST['first_name'] . ' ' . $_POST['last_name']
	];
	wp_update_user($args);

	if ($is_temporary_renter) {
		update_renter_permit($apartment, ['initial_takeover' => 1]);
	} else {
		update_user_permit($apartment, ['initial_takeover' => 1]);
	}

	$login_page_url = SwpmSettings::get_instance()->get_value('login-page-url');

	// Allow hooks to change the value of login_page_url
	$login_page_url = apply_filters('swpm_register_front_end_login_page_url', $login_page_url);

	$after_rego_msg = '<div class="swpm-registration-success-msg">' . sprintf(pll__('Registration completed message', 'simple-membership'), $user_login, $login_page_url) . (NYBRUGER_BRUGER_TOGGLE ? " " . pll__('Confirmation email sent', 'simple-membership') : '') . '</div>';
	$after_rego_msg = apply_filters('swpm_registration_success_msg', $after_rego_msg);
	$message        = [
		'succeeded' => true,
		'message'   => $after_rego_msg,
	];

	########################
	## Mail to new user
	########################
	if (NYBRUGER_BRUGER_TOGGLE) {
		$content_replaces = [
			'#APT' => $_POST['apartment_number'],
			'#NEWLOGIN' => $user_login,
			'#NEWEMAIL' => $_POST['email'],
			'#NEWFIRSTNAME' => $_POST['first_name'],
			'#NEWLASTNAME' => $_POST['last_name']
		];

		$subject_replaces = [
			'#APT' => $_POST['apartment_number'],
			'#NEWLOGIN' => $user_login,
			'#NEWEMAIL' => $_POST['email'],
			'#NEWFIRSTNAME' => $_POST['first_name'],
			'#NEWLASTNAME' => $_POST['last_name']
		];

		send_AKDTU_email(false, $subject_replaces, $content_replaces, 'NYBRUGER_BRUGER' . (pll_current_language() == "da" ? '_DA' : '_EN'), $_POST['email']);
	}

	########################
	## Mail to admins
	########################
	$content_replaces = array(
		'#APT' => $_POST['apartment_number'],
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $_POST['email'],
		'#NEWFIRSTNAME' => $_POST['first_name'],
		'#NEWLASTNAME' => $_POST['last_name']
	);

	$subject_replaces = array(
		'#APT' => $_POST['apartment_number'],
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $_POST['email'],
		'#NEWFIRSTNAME' => $_POST['first_name'],
		'#NEWLASTNAME' => $_POST['last_name']
	);

	send_AKDTU_email(false, $subject_replaces, $content_replaces, 'NYBRUGER_BESTYRELSE');

	SwpmTransfer::get_instance()->set('status', $message);
	return;
}