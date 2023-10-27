<?php

function is_apartment_from_username($username) {
	return substr($username, 0, 4) == "lejl";
}

function is_apartment_from_id($username) {
	return is_apartment_from_username(get_user_by('id',$id)->user_login);
}

function is_archive_user_from_username($username) {
	return substr($username, 7, 8) == '_archive';
}

function is_archive_user_from_id($id) {
	return is_archive_user_from_username(get_user_by('id',$id)->user_login);
}

function is_vicevært_from_username($username) {
	return count(array_filter(get_user_by('login', $username)->roles,function($role){return $role == 'vicevaert';})) > 0;
}

function is_vicevært_from_id($id) {
	return is_vicevært_from_username(get_user_by('id',$id)->user_login);
}

function apartment_number_from_username($username) {
	return ltrim(substr($username,4,3),"0");
}

function apartment_number_and_type_from_username($username) {
	return ltrim(substr($username,4),"0");
}

function apartment_number_from_id($id) {
	return apartment_number_from_username(get_user_by('id',$id)->user_login);
}

function apartment_number_and_type_from_id($id) {
	return apartment_number_and_type_from_username(get_user_by('id',$id)->user_login);
}

?>