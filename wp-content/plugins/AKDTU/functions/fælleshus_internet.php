<?php

/**
 * Returns a key-value array with information used to connect to the common house router, used for e.g. updating the password before rentals
 * 
 * @return array[string,string] key-value array with information used to connect to the common house router
 */
function get_router_settings() {
	## Router connection settings
	$router_settings = array(
		"ip" => "82.211.216.215", # External IP of router. Access from WAN must be enabled. Advanced Settings -> Administration -> System -> Enable Web Access from WAN
		"port" => "8443", # External port of router. Advanced Settings -> Administration -> System -> HTTPS Port of Web Access from WAN
		"username" => "admin", # Username to log into admin interface of router
		"password" => "vr*P6NUsh4XA\$ma1", # Password to log into admin interface of router. Please note, some characters (like $) must be escaped by writing "\" in front of them here!
		"model" => "RT-AX57", # Model of router
		"ssid" => "AKDTU Fælleshus", # SSID of internet connection
		"old_pass" => "", # Previous password of the internet-connection (not admin interface). Does not matter, but here for future reference
	);

	$router_settings["url_for_getting_values"] = "https://" . $router_settings["ip"] . ":" . $router_settings["port"] . "/appGet.cgi"; # URL for post-requests when getting current values from the router
	$router_settings["url_for_setting_values"] = "https://" . $router_settings["ip"] . ":" . $router_settings["port"] . "/start_apply2.htm"; # URL for post-requests when setting new values from the router
	$router_settings["url_for_logging_in"] = "https://" . $router_settings["ip"] . ":" . $router_settings["port"] . "/login.cgi"; # URL for post-requests when logging into the router

	return $router_settings;
}

/**
 * Password-generating function, used if the common house is rented by an apartment
 * 
 * Returns a key-value array with information used to connect to the common house router, used for e.g. updating the password before rentals
 * 
 * @param EM_Event $event Event structure for the rental of the common house, for which the password must be created
 * 
 * @return string New password for the router in the common house
 */
function hash_password($event) {
	## Generate password in case the common house is rented for an apartment
	$full_pass = md5($event->event_start_date . ".lejl" . padded_apartment_number_from_id($event->owner));
	return substr($full_pass, 0, 12);
}

/**
 * Password-generating function, used if the common house is NOT rented by an apartment
 * 
 * Returns a key-value array with information used to connect to the common house router, used for e.g. updating the password before rentals
 * 
 * @return string New password for the router in the common house
 */
function default_password() {
	## Generate password in case the common house is NOT rented for an apartment
	return "Bestyrelsen2023";
}

/**
 * Password-generating function, used if the common house is NOT rented by an apartment
 * 
 * Returns a key-value array with information used to connect to the common house router, used for e.g. updating the password before rentals
 * 
 * @param int $run_every_hours_amount How long before now, the password to the common house has not been updated. Usually set to the frequency of the cron-job used to update the password to the router. (Default: 24) 
 * 
 * @return array[string,string] Key-value array with information about the potential new password to the router, including if it should be changed at all
 */
function generate_password_info($run_every_hours_amount = 24) {
	global $wpdb;

	# Default password, set if the common house is not rented by an apartment
	$default_pass = default_password();
	
	# Current time
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	# Get id of an event currently going on
	$event_id = $wpdb->get_var('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_start <= "' . $now->format('Y-m-d H:i:s') . '" AND event_end >= "' . $now->format('Y-m-d H:i:s') . '" AND event_status = 1 LIMIT 1');

	# Prepare variables for later
	$password_should_be_changed = false; # Flag if password should be changed
	$send_renter_mail = false; # Flag if an email should be sent to a renter
	$rented_state = 0; # State of rental. 0: not rented; 1: rented by apartment; 2: rented, but not by apartment (Board, vicevært, etc.)

	if ($event_id != null) {
		## Common house is currently rented.
		$event = em_get_event($event_id, 'event_id');

		if (is_apartment_from_id($event->owner)) {
			## Common house is rented by an apartment. Set potential password to be specific to apartment.
			$new_password = hash_password($event);

			$send_renter_mail = true;
			$rented_state = 1;
		}
		else {
			## Common house is rented by someone who is not an apartment. Set potential password to default value.
			$new_password = $default_pass;
			$rented_state = 2;
		}
	} else {
		## Common house is not currently rented. Set potential password to default value.

		# Get the ID of the last booking
		$event_id = $wpdb->get_var('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_end <= "' . $now->format('Y-m-d H:i:s') . '" AND event_status = 1 ORDER BY event_end DESC LIMIT 1');

		# Get event
		$event = em_get_event($event_id, 'event_id');
	
		# Set potential password to default value.
		$new_password = $default_pass;
	}
	
	# Start date of current or last event
	$event_start_date = new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('Europe/Copenhagen'));

	# Check if the event started less than $run_every_hours_amount hours ago
	$password_should_be_changed = $now->diff($event_start_date) < $run_every_hours_amount;

	# Return information about new password
	return array(
		'password' => $new_password,
		'should_be_changed' => $password_should_be_changed,
		'event' => $event,
		'send_mail_to_renter' => $send_renter_mail,
		'rented_state' => $rented_state
	);
}

/**
 * Function for connecting to and logging into the router.
 * 
 * Returns a header string, which can be used to make subsequent requests to the router, e.g. when changing values
 * 
 * @param array[string,string] $router_settings Key-value array, generated by a call to get_router_settings()
 * 
 * @return string|false Header string, which can be used to make subsequent requests to the router, e.g. when changing values. False if connection failed.
 */
function authenticate_router($router_settings) {
	# Generate login-information to send to router
	$auth = iconv("UTF-8", "ASCII", $router_settings["username"] . ":" . $router_settings["password"]);
	$logintoken = iconv( "ASCII", "UTF-8", base64_encode( $auth ) );
	$payload = "login_authorization=" . $logintoken;

	# Create information about post-request
	$c['http']['method'] = 'POST';
	$c['http']['header'] = "user-agent: asusrouter-Android-DUTUtil-1.0.0.245";
	$c['http']['content'] = $payload;
	$c['ssl']["verify_peer"] = false;
	$c['ssl']["verify_peer_name"] = false;

	# Send post-request to router
	$r = file_get_contents($router_settings["url_for_logging_in"], false, stream_context_create($c));
 
	# Check if request failed
	if ($r === false) {
		$error = error_get_last();
		throw new Exception($error['message']);
	}

	# Request successful. Parse results
	$r = json_decode($r);

	# Check if authentication was successful, and return header content to make future requests
	if (array_key_exists("asus_token", $r)) {
		# Authentication success. Extract headers for future requests.
		$new_headers = "user-agent: asusrouter-Android-DUTUtil-1.0.0.245\r\ncookie: asus_token=" . $r->asus_token . "\r\nReferer: https://" . $router_settings["ip"] . "." . $router_settings["port"] . "/device-map/router.asp\r\nContent-Type: application/x-www-form-urlencoded";

		return $new_headers;
	}

	# Authentication failed. Return false
	return false;

}

/**
 * Function for updating the password to the router in the common house
 * Performs the authenticating and updating of the password
 * 
 * Returns a key-value array with the SSID and password to the router
 * 
 * @param array[string,string] $password_struct Key-value array, generated by a call to generate_password_info()
 * 
 * @return array[string,string]|string|false Key-value array with the SSID and password to the router, if update was successful. String containing error-message if authentication was successful, but updating password failed. False if authentication failed.
 */
function update_common_house_internet_password($password_struct) {
	# Get information about the new password
	$new_password = $password_struct['password'];

	# Get router settings
	$router_settings = get_router_settings();

	# Log in to router, and get header information for future request
	$new_headers = authenticate_router($router_settings);

	# Check if login was successful
	if ($new_headers != false) {
		# Authentication succeeded. Proceed with changing password
		$encoded_old_password = urlencode( $router_settings["old_pass"] );
		$encoded_ssid = urlencode( $router_settings["ssid"] );
	
		# Payload contains a lot of values. Format is taken from https://github.com/aaron-junot/change-wifi-pass/blob/a6b88bc14d9a17cdf7c4ee3c0951a68c77a1dc7b/change_pass.py#L39
		$payload = "current_page=%2F" . 
				  "&next_page=%2F" .
				  "&action_mode=apply_new" .
				  "&action_script=restart_wireless" .
				  "&action_wait=8" .
				  "&productid=" . $router_settings["model"] .
				  "&wps_enable=0" .
				  "&wsc_conig_state=1" .
				  "&wl_ssid_org=" . $encoded_ssid .
				  "&wl_wpa_psk_org=" . $encoded_old_password .
				  "&wl_auth_mode_orig=psk2" .
				  "&wl_nmode_x=0" .
				  "&wps_band=0" .
				  "&wl_unit=0" .
				  "&wl_mp=1" .
				  "&wl_subunit=-1" .
				  "&smart_connect_x=0" .
				  "&smart_connect_t=0" .
				  "&wl_ssid=" . $encoded_ssid .
				  "&wl_auth_mode_x=psk2" .
				  "&wl_crypto=aes" .
				  "&wl_wpa_psk=" . $new_password;
	
		# Prepare information about post-request
		$c['http']['method'] = 'POST';
		$c['http']['header'] = $new_headers;
		$c['http']['content'] = $payload;
		$c['ssl']["verify_peer"] = false;
		$c['ssl']["verify_peer_name"] = false;
	
		# Send post request
		$r = file_get_contents($router_settings["url_for_setting_values"], false, stream_context_create($c));

		# Check if request was successful
		if ($r === false) {
			# Request failed. Return error message
			$error = error_get_last();
			return $error['message'];
		}
	
		# Request was successful. Return new password and ssid
		return array(
			'password' => $new_password,
			'ssid' => $router_settings['ssid']
		);
	}
	
	# Authentication failed. Return false
	return false;
}

?>