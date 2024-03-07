<?php

/**
 * @file Functionality related to the manipulation of user-info, such as apartment numbers, ids, usernames, as well as getting info about the types of users
 */

############################################################
#
# Display names
#
/**
 * Returns the display name of the user with the specified username
 * 
 * @param string $username Username of a user
 * 
 * @return string Display name of the user
 */
function name_from_username($username) {
	# Finds the apartment number from a username for an apartment-, archive-, or renter user
	$user = get_user_by('login', $username);
	return $user->first_name . " " . $user->last_name;
}
#
/**
 * Returns the display name of the user with the specified username
 * 
 * @param int $apartment Apartment number to find the display name of
 * 
 * @return string Display name of the user
 */
function name_from_apartment_number($apartment) {
	return name_from_username(username_from_apartment_number($apartment));
}
#
/**
 * Returns the display name of the user with the specified username
 * 
 * @param int $apartment Apartment number to find the display name of, padded with zeroes if the number is less than 3 digits
 * 
 * @return string Display name of the user
 */
function name_from_padded_apartment_number($apartment) {
	return name_from_apartment_number(unpadded_apartment_number_from_padded_apartment_number($apartment));
}
#
/**
 * Returns the display name of the user with the specified username
 * 
 * @param int $id ID of the user to find the display name of
 * 
 * @return string Display name of the user
 */
function name_from_id($id) {
	return name_from_username(username_from_id($id));
}
############################################################

############################################################
#
# Apartment number, padded with zeros
#
/**
 * Returns the apartment number, padded with zeros if the number is less than 3 digits
 * 
 * @param int $apartment Apartment number
 * 
 * @return string Apartment number, padded with zeros if the number is less than 3 digits
 */
function padded_apartment_number_from_apartment_number($apartment) {
	# Finds the apartment number from a username for an apartment-, archive-, or renter user
	return str_pad($apartment,3,"0",STR_PAD_LEFT);
}
#
/**
 * Returns the apartment number, without any padding zeroes
 * 
 * @param int $apartment Apartment number, padded with zeros if the number is less than 3 digits
 * 
 * @return string Apartment number
 */
function unpadded_apartment_number_from_padded_apartment_number($apartment) {
	# Finds the apartment number from a username for an apartment-, archive-, or renter user
	return ltrim($apartment,"0");
}
############################################################

############################################################
#
# Apartment number from username
#
/**
 * Returns the apartment number from the username
 * 
 * @param int $username Username
 * 
 * @return string Apartment number corresponding to the username
 */
function apartment_number_from_username($username) {
	# Finds the apartment number from a username for an apartment-, archive-, or renter user
	return unpadded_apartment_number_from_padded_apartment_number(padded_apartment_number_from_username($username));
}
#
/**
 * Returns the apartment number and type from the username
 * 
 * @param int $username Username
 * 
 * @return string Apartment number and type corresponding to the username
 */
function apartment_number_and_type_from_username($username) {
	# Finds the apartment number from a username for an apartment-, archive-, or renter user, including the suffix if it is an archive-, or renter user
	return unpadded_apartment_number_from_padded_apartment_number(padded_apartment_number_and_type_from_username($username));
}
#
/**
 * Returns the apartment number, padded with zeros if the number is less than 3 digits, from the username
 * 
 * @param int $username Username
 * 
 * @return string Apartment number, padded with zeros if the number is less than 3 digits
 */
function padded_apartment_number_and_type_from_username($username) {
	# Finds the apartment number from a username for an apartment-, archive-, or renter user
	return substr($username,4);
}
#
/**
 * Returns the apartment number, padded with zeros if the number is less than 3 digits, from the username
 * 
 * @param int $username Username
 * 
 * @return string Apartment number, padded with zeros if the number is less than 3 digits
 */
function padded_apartment_number_from_username($username) {
	# Finds the apartment number from a username for an apartment-, archive-, or renter user
	return substr($username,4,3);
}
############################################################



############################################################
#
# Apartment number from id
#
/**
 * Returns the apartment number from the user id
 * 
 * @param int $id User id
 * 
 * @return int Apartment number corresponding to the user id
 */
function apartment_number_from_id($id) {
	# Finds the apartment number from an id for an apartment-, archive-, or renter user
	return apartment_number_from_username(username_from_id($id));
}
#
/**
 * Returns the apartment number and type from the user id
 * 
 * @param int $id User id
 * 
 * @return string Apartment number and type corresponding to the user id
 */
function apartment_number_and_type_from_id($id) {
	# Finds the apartment number from an id for an apartment-, archive-, or renter user, including the suffix if it is an archive-, or renter user
	return apartment_number_and_type_from_username(username_from_id($id));
}
#
/**
 * Returns the apartment number, padded with zeros if the number is less than 3 digits, from the user id
 * 
 * @param int $id User id
 * 
 * @return string Apartment number, padded with zeros if the number is less than 3 digits, corresponding to the user id
 */
function padded_apartment_number_from_id($id) {
	# Finds the apartment number from an id for an apartment-, archive-, or renter user
	return padded_apartment_number_from_apartment_number(apartment_number_from_id($id));
}
#
/**
 * Returns the apartment number, padded with zeros if the number is less than 3 digits, from the user id
 * 
 * @param int $id User id
 * 
 * @return string Apartment number, padded with zeros if the number is less than 3 digits, corresponding to the user id
 */
function padded_apartment_number_and_type_from_id($id) {
	# Finds the apartment number from an id for an apartment-, archive-, or renter user
	return padded_apartment_number_from_apartment_number(apartment_number_and_type_from_id($id));
}
############################################################



############################################################
#
# Username and id from apartment number
#
/**
 * Returns the username, from an apartment number
 * 
 * @param int $number Apartment number
 * 
 * @return string Username, corresponding to the apartment number.
 */
function username_from_apartment_number($number) {
	# Finds the username from an apartment number for an apartment user
	return 'lejl' . str_pad($number, 3, "0", STR_PAD_LEFT);
}
#
/**
 * Returns the user id, from an apartment number
 * 
 * @param int $number Apartment number
 * 
 * @return int User id, corresponding to the apartment number.
 */
function id_from_apartment_number($number) {
	# Finds the username from an apartment number for an apartment user
	return id_from_username(username_from_apartment_number($number));
}
############################################################



############################################################
#
# Username from id, and id from username
#
/**
 * Returns the username, from an user id
 * 
 * @param int $id User id
 * 
 * @return string Username, corresponding to the user id.
 */
function username_from_id($id) {
	# Finds the username from an apartment number for an apartment user
	return get_user_by('id', $id)->user_login;
}
#
/**
 * Returns the username, from an user id
 * 
 * @param string $username Username
 * 
 * @return int $id User id, corresponding to the username.
 */
function id_from_username($username) {
	# Finds the username from an apartment number for an apartment user
	return get_user_by('login', $username)->ID;
}
############################################################



############################################################
#
# Check if user is apartment-, archive-, or renter-user
#
/**
 * Checks if a username belongs to a user for an current resident, past resident, or temporary renter
 * 
 * @param string $username Username
 * 
 * @return bool True if the username belongs to a user for an current resident, past resident, or temporary renter
 */
function is_apartment_from_username($username) {
	# Checks if the username belongs to an apartment user
	return substr($username, 0, 4) == "lejl";
}
#
/**
 * Checks if a user id belongs to a user for an current resident, past resident, or temporary renter
 * 
 * @param int $id User id
 * 
 * @return bool True if the user id belongs to a user for an current resident, past resident, or temporary renter
 */
function is_apartment_from_id($id) {
	# Checks if the id belongs to an apartment user
	return is_apartment_from_username(username_from_id($id));
}
#############################################################



############################################################
#
# Check if user is apartment user
#
/**
 * Checks if a username belongs to a user for an current resident
 * 
 * @param string $username Username
 * 
 * @return bool True if the username belongs to a user for an current resident
 */
function is_apartment_user_from_username($username) {
	# Checks if the username belongs to an apartment user
	return strlen($username) == 7 && is_apartment_from_username($username);
}
#
/**
 * Checks if a user id belongs to a user for an current resident
 * 
 * @param int $id User id
 * 
 * @return bool True if the user id belongs to a user for an current resident
 */
function is_apartment_user_from_id($id) {
	# Checks if the id belongs to an apartment user
	return is_apartment_user_from_username(username_from_id($id));
}
############################################################



############################################################
#
# Check if user is archive user
#
/**
 * Checks if a username belongs to a user for a past resident
 * 
 * @param string $username Username
 * 
 * @return bool True if the username belongs to a user for a past resident
 */
function is_archive_user_from_username($username) {
	# Checks if the username belongs to an archive user
	return substr($username, 7, 8) == '_archive' && is_apartment_from_username($username);
}
#
/**
 * Checks if a user id belongs to a user for a past resident
 * 
 * @param int $id User id
 * 
 * @return bool True if the user id belongs to a user for a past resident
 */
function is_archive_user_from_id($id) {
	# Checks if the id belongs to an archive user
	return is_archive_user_from_username(username_from_id($id));
}
############################################################



############################################################
#
# Check if user is vicevært user
#
/**
 * Checks if a username belongs to a user for the vicevært
 * 
 * @param string $username Username
 * 
 * @return bool True if the username belongs to a user for the vicevært
 */
function is_vicevært_from_username($username) {
	# Checks if the username belongs to a vicevært user
	return in_array('vicevaert', get_user_by('login', $username)->roles);
}
#
/**
 * Checks if a user id belongs to a user for the vicevært
 * 
 * @param int $id User id
 * 
 * @return bool True if the user id belongs to a user for the vicevært
 */
function is_vicevært_from_id($id) {
	# Checks if the id belongs to a vicevært user
	return is_vicevært_from_username(username_from_id($id));
}
############################################################



############################################################
#
# Check if user is currently a boardmember
#
/**
 * Checks if a username belongs to a current boardmember
 * 
 * @param string $username Username
 * 
 * @return bool True if the username belongs to a current boardmember
 */
function is_boardmember_from_username($username) {
	# Checks if the username belongs to a board member
	return SwpmMembershipLevelUtils::get_membership_level_name_by_level_id(SwpmMemberUtils::get_user_by_user_name($username)->membership_level) == "Beboerprofil til bestyrelsesmedlem";
}
#
/**
 * Checks if an apartment number belongs to a current boardmember
 * 
 * @param string $number Apartment number
 * 
 * @return bool True if the username belongs to a current boardmember
 */
function is_boardmember_from_apartment_number($number) {
	# Checks if the apartment number belongs to a board member
	return is_boardmember_from_username(username_from_apartment_number($number));
}
#
/**
 * Checks if a user id belongs to a current boardmember
 * 
 * @param int $id User id
 * 
 * @return bool True if the user id belongs to a current boardmember
 */
function is_boardmember_from_id($id) {
	# Checks if the user id belongs to a board member
	return is_boardmember_from_username(username_from_id($id));
}
############################################################



############################################################
#
# Check if user was a boardmember at a given time
#
/**
 * Checks if a username belongs to a boardmember at a given time
 * 
 * @param string $username Username
 * @param DateTime $datetime PHP DateTime object, with the time to check
 * 
 * @return bool True if the username belongs to a boardmember at the given time
 */
function was_boardmember_from_username($username, $datetime) {
	# Checks if the username belonged to a board member at the time
	return was_boardmember_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Checks if an apartment number belongs to a boardmember at a given time
 * 
 * @param int $number Apartment number
 * @param DateTime $datetime PHP DateTime object, with the time to check
 * 
 * @return bool True if the apartment number belongs to a boardmember at the given time
 */
function was_boardmember_from_apartment_number($number, $datetime) {
	global $wpdb;
	# Checks if the apartment number belongs to a board member
	return $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'AKDTU_boardmembers WHERE apartment_number = "' . $number . '" AND start_datetime <= "' . $datetime->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $datetime->format('Y-m-d H:i:s') . '"')) > 0;
}
#
/**
 * Checks if a user id belongs to a boardmember at a given time
 * 
 * @param int $id User id
 * @param DateTime $datetime PHP DateTime object, with the time to check
 * 
 * @return bool True if the user id belongs to a boardmember at the given time
 */
function was_boardmember_from_id($id, $datetime) {
	# Checks if the user id belongs to a board member
	return was_boardmember_from_apartment_number(apartment_number_from_id($id), $datetime);
}
############################################################



############################################################
#
# Get all current boardmembers
#
/**
 * Gets a list of the apartment numbers of all current boardmembers
 * 
 * @return array[int] Array of apartment numbers for all current boardmembers
 */
function all_boardmember_apartments() {
	# Return array of boardmembers
	return array_filter(all_apartments(), function($apartment) {return is_boardmember_from_apartment_number($apartment);});
}
#
/**
 * Gets a list of the usernames of all current boardmembers
 * 
 * @return array[string] Array of usernames for all current boardmembers
 */
function all_boardmember_usernames() {
	# Lists the usernames of all board members
	return array_map(function($apartment_number) {return username_from_apartment_number($apartment_number);}, all_boardmember_apartments());
}
#
/**
 * Gets a list of the user ids of all current boardmembers
 * 
 * @return array[string] Array of user ids for all current boardmembers
 */
function all_boardmember_ids() {
	# Lists the usernames of all board members
	return array_map(function($apartment_number) {return id_from_apartment_number($apartment_number);}, all_boardmember_apartments());
}
############################################################



############################################################
#
# Get all apartments where a resident has moved out since a given date
#
###
/**
 * Gets a list of the apartment numbers of the apartments where residents have moved out after a given date
 * 
 * @param string $moved_after_date String representation of the date, after which the apartment owner should have moved out. Format: (YYYY-MM-DD)
 * 
 * @return array[int] Array of apartment numbers for all apartments where residents have moved out after a given date
 */
function all_moved_after_apartment_numbers($moved_after_date) {
	global $wpdb;

	# Find apartment numbers of all apartments where the resident has moved out since the given date
	return $wpdb->get_col('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_membercreation WHERE allow_creation_date >= "' . $moved_after_date . '" ORDER BY allow_creation_date ASC, apartment_number ASC');
}
#
/**
 * Gets a list of the apartment numbers of the apartments where residents have moved out after a given date
 * 
 * @param string $moved_after_date String representation of the date, after which the apartment owner should have moved out. Format: (YYYY-MM-DD)
 * 
 * @return array[string] Array of usernames for all apartments where residents have moved out after a given date
 */
function all_moved_after_usernames($moved_after_date) {
	# List of usernames of all apartments where the resident has moved out since the given date
	return array_map(function ($apartment) {return username_from_apartment_number($apartment);}, all_moved_after_apartment_numbers($moved_after_date));
}
#
/**
 * Gets a list of the apartment numbers of the apartments where residents have moved out after a given date
 * 
 * @param string $moved_after_date String representation of the date, after which the apartment owner should have moved out. Format: (YYYY-MM-DD)
 * 
 * @return array[int] Array of user ids for all apartments where residents have moved out after a given date
 */
function all_moved_after_ids($moved_after_date) {
	# List of user ids of all apartments where the resident has moved out since the given date
	return array_map(function ($apartment) {return id_from_apartment_number($apartment);}, all_moved_after_apartment_numbers($moved_after_date));
}
############################################################

/**
 * Returns an array with the apartment numbers for all apartments
 * 
 * @return array[int] Array of apartment numbers for all apartments
 */
function all_apartments() {
	# Return array of apartment numbers
	return array_merge(
		...array_map(
			function ($floor) {
				return array_map(
					function ($apartment) use ($floor) {
						return $floor * 100 + $apartment;
					},
					[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24]
				);
			},
			[0,1,2]
		)
	);
}

/**
 * Creates a html-dropdown element containing an element for each apartment. Values are always unpadded apartment numbers
 * 
 * @param bool $display_apartment_numbers True if the dropdown should contain the apartment numbers of the apartment users. Default: true
 * @param bool $display_names True if the dropdown should contain the names of the apartment users. Default: true
 * @param bool $use_padded_apartment_numbers True if the apartment numbers should contain leading zeros if the number is less than three digits. Default: true
 * @param string $apartment_number_and_name_separator String separator placed between apartment number and name, if both `$display_apartment_numbers` and `$display_names` are true. Default: ' - '
 * 
 * @return string Dropdown containing all apartment users
 * 
 * @throws InvalidArgumentException If $display_apartment_numbers and $display_names are both false
 */
function apartments_dropdown($display_apartment_numbers = true, $display_names = true, $use_padded_apartment_numbers = true, $apartment_number_and_name_separator = ' - ') {
	if (!$display_apartment_numbers && !$display_names) {
		throw new InvalidArgumentException("Both $display_apartment_numbers and $display_names cannot be false");
	}

	$dropdown = '<select name="user">';
	foreach (all_apartments() as $apartment) {
		$dropdown .= '<option value="' . ($apartment) . '">' . ($display_apartment_numbers ? ($use_padded_apartment_numbers ? padded_apartment_number_from_apartment_number($apartment) : $apartment) : '') . ($display_apartment_numbers && $display_names ? $apartment_number_and_name_separator : '') . ($display_names ? name_from_apartment_number($apartment) : '') . '</option>';
	}
	$dropdown .= '</select>';

	return $dropdown;
}

?>