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
# Check if an apartment number is valid
#
function is_valid_apartment_number($apartment_number) {
	$apartment_number = intval($apartment_number);

	return $apartment_number % 100 >= 1 && $apartment_number % 100 <= 24 && floor($apartment_number / 100) >= 0 && floor($apartment_number / 100) <= 2
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
	return was_boardmember_from_username($username, new DateTime('now', new DateTimeZone('Europe/Copenhagen')));
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
# Check if user is currently a board deputy
#
/**
 * Checks if a username belongs to a current board deputy
 * 
 * @param string $username Username
 * 
 * @return bool True if the username belongs to a current board deputy
 */
function is_board_deputy_from_username($username) {
	# Checks if the username belongs to a board member
	return was_board_deputy_from_username($username, new DateTime('now', new DateTimeZone('Europe/Copenhagen')));
}
#
/**
 * Checks if an apartment number belongs to a current board deputy
 * 
 * @param string $number Apartment number
 * 
 * @return bool True if the username belongs to a current board deputy
 */
function is_board_deputy_from_apartment_number($number) {
	# Checks if the apartment number belongs to a board member
	return is_board_deputy_from_username(username_from_apartment_number($number));
}
#
/**
 * Checks if a user id belongs to a current board deputy
 * 
 * @param int $id User id
 * 
 * @return bool True if the user id belongs to a current board deputy
 */
function is_board_deputy_from_id($id) {
	# Checks if the user id belongs to a board member
	return is_board_deputy_from_username(username_from_id($id));
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
	$now = new DateTime('now', new DateTimeZone("Europe/Copenhagen"));

	global $wpdb;
	global $AKDTU_USER_TYPES;

	// Find all board members
	return $wpdb->get_col('SELECT apartment_number FROM ' . $wpdb->prefix . 'AKDTU_boardmembers WHERE start_datetime <= "' . $now->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $now->format('Y-m-d H:i:s') . '" AND member_type IN ("' . $AKDTU_USER_TYPES['chairman']['id'] . '","' . $AKDTU_USER_TYPES['deputy-chairman']['id'] . '","' . $AKDTU_USER_TYPES['default']['id'] . '") ORDER BY apartment_number ASC');
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
# Get all current board deputies
#
/**
 * Gets a list of the apartment numbers of all current board deputies
 * 
 * @return array[int] Array of apartment numbers for all current board deputies
 */
function all_board_deputies_apartments() {
	$now = new DateTime('now', new DateTimeZone("Europe/Copenhagen"));

	global $wpdb;
	global $AKDTU_USER_TYPES;

	// Find all board deputies
	return $wpdb->get_col($wpdb->prepare('SELECT DISTINCT apartment_number FROM ' . $wpdb->prefix . 'AKDTU_boardmembers WHERE start_datetime <= "' . $now->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $now->format('Y-m-d H:i:s') . '" AND member_type = "' . $AKDTU_USER_TYPES['deputy']['id'] . '" ORDER BY apartment_number ASC'));
}
#
/**
 * Gets a list of the usernames of all current board deputies
 * 
 * @return array[string] Array of usernames for all current board deputies
 */
function all_board_deputies_usernames() {
	# Lists the usernames of all board deputies
	return array_map(function($apartment_number) {return username_from_apartment_number($apartment_number);}, all_board_deputies_apartments());
}
#
/**
 * Gets a list of the user ids of all current board deputies
 * 
 * @return array[string] Array of user ids for all current board deputies
 */
function all_board_deputies_ids() {
	# Lists the usernames of all board deputies
	return array_map(function($apartment_number) {return id_from_apartment_number($apartment_number);}, all_board_deputies_apartments());
}
############################################################



############################################################
#
# Get all current board members and deputies
#
/**
 * Gets a list of the apartment numbers of all current board members and deputies
 * 
 * @return array[int] Array of apartment numbers for all current board members and deputies
 */
function all_board_apartments() {
	$now = new DateTime('now', new DateTimeZone("Europe/Copenhagen"));

	global $wpdb;

	// Find all board members and board deputies
	return $wpdb->get_col($wpdb->prepare('SELECT DISTINCT apartment_number FROM ' . $wpdb->prefix . 'AKDTU_boardmembers WHERE start_datetime <= "' . $now->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $now->format('Y-m-d H:i:s') . '" ORDER BY apartment_number ASC'));
}
#
/**
 * Gets a list of the usernames of all current board members and deputies
 * 
 * @return array[string] Array of usernames for all current board members and deputies
 */
function all_board_usernames() {
	# Lists the usernames of all board members and deputies
	return array_map(function($apartment_number) {return username_from_apartment_number($apartment_number);}, all_board_apartments());
}
#
/**
 * Gets a list of the user ids of all current board members and deputies
 * 
 * @return array[string] Array of user ids for all current board members and deputies
 */
function all_board_ids() {
	# Lists the usernames of all board members and deputies
	return array_map(function($apartment_number) {return id_from_apartment_number($apartment_number);}, all_board_apartments());
}
############################################################



############################################################
#
# Get board-email adresses
#
/**
 * Defines the user email for a board-related person, by their apartment number
 * 
 * @param string $number Apartment number of the board-related person
 * 
 * @return string Email adress of the board-related person
 */
function board_email_from_apartment_number($number) {
	return board_email_from_id(id_from_apartment_number($number));
}
#
/**
 * Defines the user email for a board-related person, by their username
 * 
 * @param string $username Username of the board-related person
 * 
 * @return string Email adress of the board-related person
 */
function board_email_from_username($username) {
	return board_email_from_id(id_from_username($username));
}
#
/**
 * Defines the user email for a board-related person, by their user id
 * 
 * @param string $id User id of the board-related person
 * 
 * @return string Email adress of the board-related person
 */
function board_email_from_id($id) {
	return strtolower(explode(" ", get_user_by('ID', $id)->first_name)[0]) . apartment_number_from_id($id) . "@akdtu.dk";
}
############################################################



############################################################
#
# Check type of user at a given time
#
/**
 * Checks if a username belongs to a board member at a given time
 * 
 * @param string $username Username
 * @param DateTime $datetime PHP DateTime object, with the time to check
 * 
 * @return bool True if the username belongs to a board member at the given time
 */
function was_boardmember_from_username($username, $datetime) {
	# Checks if the username belonged to a board member at the time
	return was_boardmember_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Checks if an apartment number belongs to a board member at a given time
 * 
 * @param int $number Apartment number
 * @param DateTime $datetime PHP DateTime object, with the time to check
 * 
 * @return bool True if the apartment number belongs to a board member at the given time
 */
function was_boardmember_from_apartment_number($number, $datetime) {
	global $wpdb;
	global $AKDTU_USER_TYPES;

	# Checks if the apartment number belongs to a board member
	return $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'AKDTU_boardmembers WHERE apartment_number = "' . $number . '" AND start_datetime <= "' . $datetime->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $datetime->format('Y-m-d H:i:s') . '" AND member_type IN ("' . $AKDTU_USER_TYPES['chairman']['id'] . '","' . $AKDTU_USER_TYPES['deputy-chairman']['id'] . '","' . $AKDTU_USER_TYPES['default']['id'] . '")') > 0;
}
#
/**
 * Checks if a user id belongs to a board member at a given time
 * 
 * @param int $id User id
 * @param DateTime $datetime PHP DateTime object, with the time to check
 * 
 * @return bool True if the user id belongs to a board member at the given time
 */
function was_boardmember_from_id($id, $datetime) {
	# Checks if the user id belongs to a board member
	return was_boardmember_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Checks if a username belongs to a board deputy at a given time
 * 
 * @param string $username Username
 * @param DateTime $datetime PHP DateTime object, with the time to check
 * 
 * @return bool True if the username belongs to a board deputy at the given time
 */
function was_board_deputy_from_username($username, $datetime) {
	# Checks if the username belonged to a board deputy at the time
	return was_board_deputy_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Checks if an apartment number belongs to a board deputy at a given time
 * 
 * @param int $number Apartment number
 * @param DateTime $datetime PHP DateTime object, with the time to check
 * 
 * @return bool True if the apartment number belongs to a board deputy at the given time
 */
function was_board_deputy_from_apartment_number($number, $datetime) {
	global $wpdb;
	global $AKDTU_USER_TYPES;

	# Checks if the apartment number belongs to a board member
	return $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'AKDTU_boardmembers WHERE apartment_number = "' . $number . '" AND start_datetime <= "' . $datetime->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $datetime->format('Y-m-d H:i:s') . '" AND member_type = "' . $AKDTU_USER_TYPES['deputy']['id'] . '"') > 0;
}
#
/**
 * Checks if a user id belongs to a board deputy at a given time
 * 
 * @param int $id User id
 * @param DateTime $datetime PHP DateTime object, with the time to check
 * 
 * @return bool True if the user id belongs to a board_deputy at the given time
 */
function was_board_deputy_from_id($id, $datetime) {
	# Checks if the user id belongs to a board deputy
	return was_board_deputy_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Checks if a user was chairman of the board at a given time, by their apartment number
 * 
 * @param string $number Apartment number of the user
 * @param DateTime $datetime Time to check if the user was chairman of the board
 * 
 * @return bool True if the user was chairman of the board at the given time
 */
function was_chairman_from_apartment_number($number, $datetime) {
	global $AKDTU_USER_TYPES;
	# Checks if the user was chairman at the given time
	return user_type_from_apartment_number($number, $datetime) == $AKDTU_USER_TYPES['chairman']['id'];
}
#
/**
 * Checks if a user was chairman of the board at a given time, by their username
 * 
 * @param string $username Username of the user
 * @param DateTime $datetime Time to check if the user was chairman of the board
 * 
 * @return bool True if the user was chairman of the board at the given time
 */
function was_chairman_from_username($username, $datetime) {
	return was_chairman_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Checks if a user was chairman of the board at a given time, by their user id
 * 
 * @param string $id User id of the user
 * @param DateTime $datetime Time to check if the user was chairman of the board
 * 
 * @return bool True if the user was chairman of the board at the given time
 */
function was_chairman_from_id($id, $datetime) {
	return was_chairman_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Checks if a user was deputy chairman of the board at a given time, by their apartment number
 * 
 * @param string $number Apartment number of the user
 * @param DateTime $datetime Time to check if the user was deputy chairman of the board
 * 
 * @return bool True if the user was deputy chairman of the board at the given time
 */
function was_deputy_chairman_from_apartment_number($number, $datetime) {
	global $AKDTU_USER_TYPES;
	# Checks if the user was chairman at the given time
	return user_type_from_apartment_number($number, $datetime) == $AKDTU_USER_TYPES['deputy-chairman']['id'];
}
#
/**
 * Checks if a user was deputy chairman of the board at a given time, by their username
 * 
 * @param string $username Username of the user
 * @param DateTime $datetime Time to check if the user was deputy chairman of the board
 * 
 * @return bool True if the user was deputy chairman of the board at the given time
 */
function was_deputy_chairman_from_username($username, $datetime) {
	return was_deputy_chairman_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Checks if a user was deputy chairman of the board at a given time, by their user id
 * 
 * @param string $id User id of the user
 * @param DateTime $datetime Time to check if the user was deputy chairman of the board
 * 
 * @return bool True if the user was deputy chairman of the board at the given time
 */
function was_deputy_chairman_from_id($id, $datetime) {
	return was_deputy_chairman_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Checks if a user was a default member of the board at a given time, by their apartment number
 * 
 * @param string $number Apartment number of the user
 * @param DateTime $datetime Time to check if the user was a default member of the board
 * 
 * @return bool True if the user was a default member of the board at the given time
 */
function was_default_boardmember_from_apartment_number($number, $datetime) {
	global $AKDTU_USER_TYPES;
	# Checks if the user was chairman at the given time
	return user_type_from_apartment_number($number, $datetime) == $AKDTU_USER_TYPES['default']['id'];
}
#
/**
 * Checks if a user was a default member of the board at a given time, by their username
 * 
 * @param string $username Username of the user
 * @param DateTime $datetime Time to check if the user was a default member of the board
 * 
 * @return bool True if the user was a default member of the board at the given time
 */
function was_default_boardmember_from_username($username, $datetime) {
	return was_default_boardmember_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Checks if a user was a default member of the board at a given time, by their user id
 * 
 * @param string $id User id of the user
 * @param DateTime $datetime Time to check if the user was a default member of the board
 * 
 * @return bool True if the user was a default member of the board at the given time
 */
function was_default_boardmember_from_id($id, $datetime) {
	return was_default_boardmember_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Gets the user type of a board member or deputy at a given time, by their apartment number
 * 
 * @param string $number Apartment number of the user
 * @param DateTime $datetime Time to find the user type of the user
 * 
 * @return int User type of the user if they were a board member or deputy at the given time. 0 otherwise.
 */
function user_type_from_apartment_number($number, $datetime) {
	global $wpdb;
	
	$user_type = $wpdb->get_var($wpdb->prepare('SELECT member_type FROM ' . $wpdb->prefix . 'AKDTU_boardmembers WHERE apartment_number = "' . $number . '" AND start_datetime <= "' . $datetime->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $datetime->format('Y-m-d H:i:s') . '"'));

	if (is_null($user_type)) {
		global $AKDTU_USER_TYPES;
		return $AKDTU_USER_TYPES['none']['id'];
	}

	return $user_type;
}
#
/**
 * Gets the user type of a board member or deputy at a given time, by their username
 * 
 * @param string $username Username of the user
 * @param DateTime $datetime Time to find the user type of the user
 * 
 * @return int User type of the user if they were a board member or deputy at the given time. 0 otherwise.
 */
function user_type_from_username($username, $datetime) {
	return user_type_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Gets the user type of a board member or deputy at a given time, by their user id
 * 
 * @param string $id User id of the user
 * @param DateTime $datetime Time to find the user type of the user
 * 
 * @return int User type of the user if they were a board member or deputy at the given time. 0 otherwise.
 */
function user_type_from_id($id, $datetime) {
	return user_type_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Gets the name of the user type of a board member or deputy at a given time, by their apartment number
 * 
 * @param string $number Apartment number of the user
 * @param DateTime $datetime Time to find the user type of the user
 * 
 * @return string Name of the user type of the user at the given time..
 */
function user_type_name_from_apartment_number($number, $datetime) {
	global $AKDTU_USER_TYPES;
	$user_type = user_type_from_apartment_number($number, $datetime);

	return array_values(
		array_filter(
			$AKDTU_USER_TYPES,
			function($type) use($user_type) {
				return $type['id'] == $user_type;
			}
		)
	)[0]['name'];
}
#
/**
 * Gets the name of the user type of a board member or deputy at a given time, by their username
 * 
 * @param string $username Username of the user
 * @param DateTime $datetime Time to find the user type of the user
 * 
 * @return string Name of the user type of the user at the given time..
 */
function user_type_name_from_username($username, $datetime) {
	return user_type_name_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Gets the name of the user type of a board member or deputy at a given time, by their user ID
 * 
 * @param string $id User id of the user
 * @param DateTime $datetime Time to find the user type of the user
 * 
 * @return string Name of the user type of the user at the given time..
 */
function user_type_name_from_id($id, $datetime) {
	return user_type_name_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Gets the id of the user type of a board member or deputy at a given time, by their apartment number
 * 
 * @param string $number Apartment number of the user
 * @param DateTime $datetime Time to find the user type of the user
 * 
 * @return string Id of the user type of the user at the given time..
 */
function user_type_key_from_apartment_number($number, $datetime) {
	global $AKDTU_USER_TYPES;
	$user_type = user_type_from_apartment_number($number, $datetime);

	return array_keys(
		array_filter(
			$AKDTU_USER_TYPES,
			function($type) use($user_type) {
				return $type['id'] == $user_type;
			}
		)
	)[0];
}
#
/**
 * Gets the id of the user type of a board member or deputy at a given time, by their username
 * 
 * @param string $username Username of the user
 * @param DateTime $datetime Time to find the user type of the user
 * 
 * @return string Id of the user type of the user at the given time..
 */
function user_type_key_from_username($username, $datetime) {
	return user_type_key_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Gets the id of the user type of a board member or deputy at a given time, by their user ID
 * 
 * @param string $id User id of the user
 * @param DateTime $datetime Time to find the user type of the user
 * 
 * @return string Id of the user type of the user at the given time..
 */
function user_type_key_from_id($id, $datetime) {
	return user_type_key_from_apartment_number(apartment_number_from_id($id), $datetime);
}
############################################################


############################################################
#
# Check if a user is related to the network group
#
/**
 * Gets the apartment numbers of all apartments that are members of the network group
 * 
 * @return int[] Array of apartment numbers
 */
function all_networkgroup_apartment_numbers() {
	global $wpdb;

	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return $wpdb->get_col($wpdb->prepare('SELECT apartment_number FROM ' . $wpdb->prefix . 'AKDTU_networkgroupmembers WHERE start_datetime <= "' . $now->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $now->format('Y-m-d H:i:s') . '" ORDER BY member_type ASC, apartment_number ASC'));
}
#
/**
 * Gets the usernames of all apartments that are members of the network group
 * 
 * @return string[] Array of usernames
 */
function all_networkgroup_usernames() {
	return array_map(function ($apartment_number) {return username_from_apartment_number($apartment_number);}, all_networkgroup_apartment_numbers());
}
#
/**
 * Gets the user ids of all apartments that are members of the network group
 * 
 * @return int[] Array of user ids
 */
function all_networkgroup_ids() {
	return array_map(function ($apartment_number) {return id_from_apartment_number($apartment_number);}, all_networkgroup_apartment_numbers());
}
#
#
#
/**
 * Checks if an apartment is currently the representative of the dorm in K-Net
 * 
 * @param int $number Apartment number to check
 * @return bool True if the apartment is currently the representative in K-Net
 */
function is_KNet_representative_from_apartment_number($number) {
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return was_KNet_representative_from_apartment_number($number, $now);
}
#
/**
 * Checks if an apartment is currently the representative of the dorm in K-Net
 * 
 * @param string $username Username to check
 * @return bool True if the apartment is currently the representative in K-Net
 */
function is_KNet_representative_from_username($username) {
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return is_KNet_representative_from_username($username, $now);
}
#
/**
 * Checks if an apartment is currently the representative of the dorm in K-Net
 * 
 * @param int $id User id to check
 * @return bool True if the apartment is currently the representative in K-Net
 */
function is_KNet_representative_from_id($id) {
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return was_KNet_representative_from_id($id, $now);
}
#
#
#
/**
 * Checks if an apartment was the representative of the dorm in K-Net at a specified time
 * 
 * @param int $number Apartment number to check
 * @param DateTime $datetime Time to check if the apartment was the K-Net representative
 * @return bool True if the apartment was the representative in K-Net at the specified time
 */
function was_KNet_representative_from_apartment_number($number, $datetime) {
	global $KNET_USER_TYPES;
	return KNet_type_from_apartment_number($number, $datetime) == $KNET_USER_TYPES['representative']['id'];
}
#
/**
 * Checks if an apartment was the representative of the dorm in K-Net at a specified time
 * 
 * @param string $username Username to check
 * @param DateTime $datetime Time to check if the apartment was the K-Net representative
 * @return bool True if the apartment was the representative in K-Net at the specified time
 */
function was_KNet_representative_from_username($username, $datetime) {
	return was_KNet_representative_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Checks if an apartment was the representative of the dorm in K-Net at a specified time
 * 
 * @param int $id User id to check
 * @param DateTime $datetime Time to check if the apartment was the K-Net representative
 * @return bool True if the apartment was the representative in K-Net at the specified time
 */
function was_KNet_representative_from_id($id, $datetime) {
	return was_KNet_representative_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Checks if an apartment is currently the deputy of the dorm in K-Net
 * 
 * @param int $number Apartment number to check
 * @return bool True if the apartment is currently the deputy in K-Net
 */
function is_KNet_deputy_from_apartment_number($number) {
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return was_KNet_deputy_from_apartment_number($number, $now);
}
#
/**
 * Checks if an apartment is currently the deputy of the dorm in K-Net
 * 
 * @param string $username Username to check
 * @return bool True if the apartment is currently the deputy in K-Net
 */
function is_KNet_deputy_from_username($username) {
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return was_KNet_deputy_from_username($username, $now);
}
#
/**
 * Checks if an apartment is currently the deputy of the dorm in K-Net
 * 
 * @param int $id User id to check
 * @return bool True if the apartment is currently the deputy in K-Net
 */
function is_KNet_deputy_from_id($id) {
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	return was_KNet_deputy_from_id($id, $now);
}
#
#
#
/**
 * Checks if an apartment was the deputy of the dorm in K-Net at a specified time
 * 
 * @param int $number Apartment number to check
 * @param DateTime $datetime Time to check if the apartment was the K-Net deputy
 * @return bool True if the apartment was the deputy in K-Net at the specified time
 */
function was_KNet_deputy_from_apartment_number($number, $datetime) {
	global $KNET_USER_TYPES;
	return KNet_type_from_apartment_number($number, $datetime) == $KNET_USER_TYPES['deputy']['id'];
}
#
/**
 * Checks if an apartment was the deputy of the dorm in K-Net at a specified time
 * 
 * @param string $username Username to check
 * @param DateTime $datetime Time to check if the apartment was the K-Net deputy
 * @return bool True if the apartment was the deputy in K-Net at the specified time
 */
function was_KNet_deputy_from_username($username, $datetime) {
	return was_KNet_deputy_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Checks if an apartment was the deputy of the dorm in K-Net at a specified time
 * 
 * @param int $id User id to check
 * @param DateTime $datetime Time to check if the apartment was the K-Net deputy
 * @return bool True if the apartment was the deputy in K-Net at the specified time
 */
function was_KNet_deputy_from_id($id, $datetime) {
	return was_KNet_deputy_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Gets the K-Net type of a user at a given time, by their apartment number
 * 
 * @param string $number Apartment number of the user
 * @param DateTime $datetime Time to find the K-Net type of the user
 * 
 * @return int|bool K-Net type of the user.
 */
function KNet_type_from_apartment_number($number, $datetime) {
	global $wpdb;
	
	$user_type = $wpdb->get_var($wpdb->prepare('SELECT member_type FROM ' . $wpdb->prefix . 'AKDTU_networkgroupmembers WHERE apartment_number = "' . $number . '" AND start_datetime <= "' . $datetime->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $datetime->format('Y-m-d H:i:s') . '"'));

	if (is_null($user_type)) {
		global $KNET_USER_TYPES;
		return $KNET_USER_TYPES['none']['id'];
	}

	return $user_type;
}
#
/**
 * Gets the K-Net type of a board member or deputy at a given time, by their username
 * 
 * @param string $username Username of the user
 * @param DateTime $datetime Time to find the K-Net type of the user
 * 
 * @return int K-Net type of the user.
 */
function KNet_type_from_username($username, $datetime) {
	return KNet_type_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Gets the K-Net type of a board member or deputy at a given time, by their user id
 * 
 * @param string $id User id of the user
 * @param DateTime $datetime Time to find the K-Net type of the user
 * 
 * @return int K-Net type of the user.
 */
function KNet_type_from_id($id, $datetime) {
	return KNet_type_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Gets the name of the K-Net type of a board member or deputy at a given time, by their apartment number
 * 
 * @param string $number Apartment number of the user
 * @param DateTime $datetime Time to find the K-Net type of the user
 * 
 * @return string Name of the K-Net type of the user at the given time..
 */
function KNet_type_name_from_apartment_number($number, $datetime) {
	global $KNET_USER_TYPES;
	$user_type = KNet_type_from_apartment_number($number, $datetime);

	return array_values(
		array_filter(
			$KNET_USER_TYPES,
			function($type) use($user_type) {
				return $type['id'] == $user_type;
			}
		)
	)[0]['name'];
}
#
/**
 * Gets the name of the K-Net type of a board member or deputy at a given time, by their username
 * 
 * @param string $username Username of the user
 * @param DateTime $datetime Time to find the K-Net type of the user
 * 
 * @return string Name of the K-Net type of the user at the given time..
 */
function KNet_type_name_from_username($username, $datetime) {
	return KNet_type_name_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Gets the name of the K-Net type of a board member or deputy at a given time, by their user ID
 * 
 * @param string $id User id of the user
 * @param DateTime $datetime Time to find the K-Net type of the user
 * 
 * @return string Name of the K-Net type of the user at the given time..
 */
function KNet_type_name_from_id($id, $datetime) {
	return KNet_type_name_from_apartment_number(apartment_number_from_id($id), $datetime);
}
#
#
#
/**
 * Gets the id of the K-Net type of a board member or deputy at a given time, by their apartment number
 * 
 * @param string $number Apartment number of the user
 * @param DateTime $datetime Time to find the K-Net type of the user
 * 
 * @return string Id of the K-Net type of the user at the given time..
 */
function KNet_type_key_from_apartment_number($number, $datetime) {
	global $KNET_USER_TYPES;
	$user_type = KNet_type_from_apartment_number($number, $datetime);

	return array_keys(
		array_filter(
			$KNET_USER_TYPES,
			function($type) use($user_type) {
				return $type['id'] == $user_type;
			}
		)
	)[0];
}
#
/**
 * Gets the id of the K-Net type of a board member or deputy at a given time, by their username
 * 
 * @param string $username Username of the user
 * @param DateTime $datetime Time to find the K-Net type of the user
 * 
 * @return string Id of the K-Net type of the user at the given time..
 */
function KNet_type_key_from_username($username, $datetime) {
	return KNet_type_key_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
/**
 * Gets the id of the K-Net type of a board member or deputy at a given time, by their user ID
 * 
 * @param string $id User id of the user
 * @param DateTime $datetime Time to find the K-Net type of the user
 * 
 * @return string Id of the K-Net type of the user at the given time..
 */
function KNet_type_key_from_id($id, $datetime) {
	return KNet_type_key_from_apartment_number(apartment_number_from_id($id), $datetime);
}
############################################################



############################################################
#
# Check if a user had to pay rental costs at a given time
#
/**
 * Check if an apartment had to pay rental costs at a given time
 * 
 * @param int $apartment_number Apartment number to check
 * @param DateTime $datetime Time and date to check whether the apartment had to pay rental costs
 * 
 * @return bool True if the user had to pay rental cost at the given time
 */
function had_to_pay_rental_cost_from_apartment_number($apartment_number, $datetime) {
	return had_to_pay_rental_cost_from_id(id_from_apartment_number($apartment_number), $datetime);
}
#
/**
 * Check if a username had to pay rental costs at a given time
 * 
 * @param string $username Username of the user to check
 * @param DateTime $datetime Time and date to check whether the apartment had to pay rental costs
 * 
 * @return bool True if the user had to pay rental cost at the given time
 */
function had_to_pay_rental_cost_from_username($username, $datetime) {
	return had_to_pay_rental_cost_from_id(id_from_username($username), $datetime);
}
#
/**
 * Gets a list of the user ids of all current board deputies
 * 
 * @param int $user_id Id of the user to check
 * @param DateTime $datetime Time and date to check whether the apartment had to pay rental costs
 * 
 * @return bool True if the user had to pay rental cost at the given time
 */
function had_to_pay_rental_cost_from_id($user_id, $datetime) {
	# Check if the user is not an apartment user or was a board member at the given time. In these cases, the user should not pay any rental cost.
	return !(!is_apartment_from_id($user_id) || was_boardmember_from_id($user_id, $datetime));
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
 * Gets a list of the usernames of the apartments where residents have moved out after a given date
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
 * Gets a list of the user ids of the apartments where residents have moved out after a given date
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



############################################################
#
# Get all apartments
#
###
/**
 * Returns an array with the apartment numbers for all apartments
 * 
 * @return array[int] Array of apartment numbers for all apartments
 */
function all_apartments() {
	# Return array of apartment numbers
	return [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,201,202,203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224];
}
#
/**
 * Creates a html-dropdown element containing an element for each apartment. Values are always unpadded apartment numbers
 * 
 * @param bool $display_apartment_numbers True if the dropdown should contain the apartment numbers of the apartment users. Default: true
 * @param bool $display_names True if the dropdown should contain the names of the apartment users. Default: true
 * @param bool $use_padded_apartment_numbers True if the apartment numbers should contain leading zeros if the number is less than three digits. Default: true
 * @param string $apartment_number_and_name_separator String separator placed between apartment number and name, if both `$display_apartment_numbers` and `$display_names` are true. Default: ' - '
 * @param string $name Name of the dropdown. Default: 'user'
 * @param string $id ID of the dropdown. Default: ''
 * @param string $class Class(es) of the dropdown. Multiple classes are space-separated. Default: ''
 * 
 * @return string Dropdown containing all apartment users
 * 
 * @throws InvalidArgumentException If `$display_apartment_numbers` and `$display_names` are both false
 */
function apartments_dropdown($display_apartment_numbers = true, $display_names = true, $use_padded_apartment_numbers = true, $apartment_number_and_name_separator = ' - ', $name = "user", $id = "", $class = "") {
	if (!$display_apartment_numbers && !$display_names) {
		throw new InvalidArgumentException("Both $display_apartment_numbers and $display_names cannot be false");
	}

	$dropdown = '<select' . ($name != "" ? ' name="' . $name . '"' : '') . ($class != "" ? ' class="' . $class . '"' : '') . ($id != "" ? ' id="' . $id . '"' : '') . '>';
	$dropdown .= join('', array_map(function ($apartment) use($display_apartment_numbers, $display_names, $use_padded_apartment_numbers, $apartment_number_and_name_separator) {
		return '<option value="' . ($apartment) . '">' . ($display_apartment_numbers ? ($use_padded_apartment_numbers ? padded_apartment_number_from_apartment_number($apartment) : $apartment) : '') . ($display_apartment_numbers && $display_names ? $apartment_number_and_name_separator : '') . ($display_names ? name_from_apartment_number($apartment) : '') . '</option>';
	}, all_apartments()));

	$dropdown .= '</select>';

	return $dropdown;
}
############################################################

?>