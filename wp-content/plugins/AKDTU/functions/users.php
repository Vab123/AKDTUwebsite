<?php



############################################################
#
# Apartment number from username
#
###
function apartment_number_from_username($username) {
	## Finds the apartment number from a username for an apartment-, archive-, or renter user
	return ltrim(substr($username,4,3),"0");
}
#
function apartment_number_and_type_from_username($username) {
	## Finds the apartment number from a username for an apartment-, archive-, or renter user, including the suffix if it is an archive-, or renter user
	return ltrim(substr($username,4),"0");
}
############################################################



############################################################
#
# Apartment number from id
#
###
function apartment_number_from_id($id) {
	## Finds the apartment number from an id for an apartment-, archive-, or renter user
	return apartment_number_from_username(username_from_id($id));
}
#
function apartment_number_and_type_from_id($id) {
	## Finds the apartment number from an id for an apartment-, archive-, or renter user, including the suffix if it is an archive-, or renter user
	return apartment_number_and_type_from_username(username_from_id($id));
}
############################################################



############################################################
#
# Username and id from apartment number
#
###
function username_from_apartment_number($number) {
	## Finds the username from an apartment number for an apartment user
	return 'lejl' . str_pad($number, 3, "0", STR_PAD_LEFT);
}
#
function id_from_apartment_number($number) {
	## Finds the username from an apartment number for an apartment user
	return id_from_username(username_from_apartment_number($number));
}
############################################################



############################################################
#
# Username from id, and id from username
#
###
function username_from_id($id) {
	## Finds the username from an apartment number for an apartment user
	return get_user_by('id', $id)->user_login;
}
#
function id_from_username($username) {
	## Finds the username from an apartment number for an apartment user
	return get_user_by('login', $username)->ID;
}
############################################################



############################################################
#
# Check if user is apartment-, archive-, or renter-user
#
###
function is_apartment_from_username($username) {
	## Checks if the username belongs to an apartment user
	return substr($username, 0, 4) == "lejl";
}
#
function is_apartment_from_id($id) {
	## Checks if the id belongs to an apartment user
	return is_apartment_from_username(username_from_id($id));
}
#############################################################



############################################################
#
# Check if user is apartment user
#
###
function is_apartment_user_from_username($username) {
	## Checks if the username belongs to an apartment user
	return strlen($username) == 7 && is_apartment_from_username($username);
}
#
function is_apartment_user_from_id($id) {
	## Checks if the id belongs to an apartment user
	return is_apartment_user_from_username(username_from_id($id));
}
############################################################



############################################################
#
# Check if user is archive user
#
###
function is_archive_user_from_username($username) {
	## Checks if the username belongs to an archive user
	return substr($username, 7, 8) == '_archive' && is_apartment_from_username($username);
}
#
function is_archive_user_from_id($id) {
	## Checks if the id belongs to an archive user
	return is_archive_user_from_username(username_from_id($id));
}
############################################################



############################################################
#
# Check if user is vicevært user
#
###
function is_vicevært_from_username($username) {
	## Checks if the username belongs to a vicevært user
	return count(array_filter(get_user_by('login', $username)->roles,function($role){return $role == 'vicevaert';})) > 0;
}
#
function is_vicevært_from_id($id) {
	## Checks if the id belongs to a vicevært user
	return is_vicevært_from_username(username_from_id($id));
}
############################################################



############################################################
#
# Check if user is currently a boardmember
#
###
function is_boardmember_from_username($username) {
	## Checks if the username belongs to a board member
	$user = SwpmMemberUtils::get_user_by_user_name($username);
	$level = SwpmMembershipLevelUtils::get_membership_level_name_by_level_id($user->membership_level);

	return $level == "Beboerprofil til bestyrelsesmedlem";
}
#
function is_boardmember_from_apartment_number($number) {
	## Checks if the apartment number belongs to a board member
	return is_boardmember_from_username(username_from_apartment_number($number));
}
#
function is_boardmember_from_id($id) {
	## Checks if the user id belongs to a board member
	return is_boardmember_from_username(username_from_id($id));
}
############################################################



############################################################
#
# Check if user was a boardmember at a given time
#
###
function was_boardmember_from_username($username, $datetime) {
	## Checks if the username belonged to a board member at the time
	return was_boardmember_from_apartment_number(apartment_number_from_username($username), $datetime);
}
#
function was_boardmember_from_apartment_number($number, $datetime) {
	global $wpdb;
	## Checks if the apartment number belongs to a board member
	return $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'AKDTU_boardmembers WHERE apartment_number = "' . $number . '" AND start_datetime <= "' . $datetime->format('Y-m-d H:i:s') . '" AND end_datetime >= "' . $datetime->format('Y-m-d H:i:s') . '"')) > 0;
}
#
function was_boardmember_from_id($id, $datetime) {
	## Checks if the user id belongs to a board member
	return was_boardmember_from_apartment_number(apartment_number_from_id($id), $datetime);
}
############################################################



############################################################
#
# Get all current boardmembers
#
###
function all_boardmember_apartments() {
	## Lists the usernames of all board members
	$board_member_usernames = array();

	for ($floor = 0; $floor <= 2; $floor++) {
		for ($apartment = $floor*100 + 1; $apartment <= $floor*100 + 24; $apartment++) {
			if(is_boardmember_from_apartment_number($apartment)) {
				$board_member_usernames[] = $apartment;
			}
		}
	}

	return $board_member_usernames;
}
#
function all_boardmember_usernames() {
	## Lists the usernames of all board members
	return array_map(function($apartment_number) {return username_from_apartment_number($apartment_number);}, all_boardmember_apartments());
}
#
function all_boardmember_ids() {
	## Lists the usernames of all board members
	return array_map(function($apartment_number) {return id_from_apartment_number($apartment_number);}, all_boardmember_apartments());
}
############################################################

?>