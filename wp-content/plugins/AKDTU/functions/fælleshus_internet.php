<?php

/**
 * @file Functionality related to the control of the router in the common house, such as setting the password
 */

/**
 * Returns a key-value array with information used to connect to the common house router, used for e.g. updating the password before rentals
 * 
 * @return array[string,string] key-value array with information used to connect to the common house router
 */
function get_router_settings() {
	## Router connection settings
	$router_settings = array();

	# External IP of router. Access from WAN must be enabled. Advanced Settings -> Administration -> System -> Enable Web Access from WAN
	$router_settings["ip"] = "82.211.216.215";

	# External port of router. Advanced Settings -> Administration -> System -> HTTPS Port of Web Access from WAN
	$router_settings["port"] = "8443";

	# Username to log into admin interface of router
	$router_settings["username"] = "admin";

	# Password to log into admin interface of router. Please note, some characters (like $) must be escaped by writing "\" in front of them here!
	$router_settings["password"] = "vr*P6NUsh4XA\$ma1";

	# Model of router
	$router_settings["model"] = "RT-AX57";

	# SSID of internet connection
	$router_settings["ssid"] = "AKDTU Fælleshus";

	# Previous password of the internet-connection (not admin interface). Does not matter, but here for future reference
	$router_settings["old_pass"] = "";

	# URL for post-requests when getting current values from the router
	$router_settings["url_for_getting_values"] = "https://" . $router_settings["ip"] . ":" . $router_settings["port"] . "/appGet.cgi";

	# URL for post-requests when setting new values from the router
	$router_settings["url_for_setting_values"] = "https://" . $router_settings["ip"] . ":" . $router_settings["port"] . "/start_apply2.htm";

	# URL for post-requests when logging into the router
	$router_settings["url_for_logging_in"] = "https://" . $router_settings["ip"] . ":" . $router_settings["port"] . "/login.cgi"; 

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
	// Generate password in case the common house is rented for an apartment
	$new_pass = $event->event_start_date . "." . username_from_id($event->owner);

	// MD5-hash password
	$new_pass = md5($new_pass);

	// Limit password length to 12 characters
	$new_pass = substr($new_pass, 0, 12);

	return $new_pass;
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
	return "Bestyrelsen2024";
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
function generate_password_info() {
	# Default password, set if the common house is not rented by an apartment
	$default_pass = default_password();

	# Get id of an event currently going on
	$event_id = get_current_common_house_event_ids(1);

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
		$event_id = get_recent_common_house_event_ids(1);

		# Get event
		$event = em_get_event($event_id, 'event_id');
	
		# Set potential password to default value.
		$new_password = $default_pass;
	}

	# Check if the current passcode is not the correct one
	$password_should_be_changed = $new_password != get_fælleshus_password();

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
 * @return string|false Header string, which can be used to make subsequent requests to the router, e.g. when changing values. False if connection failed.
 */
function authenticate_router() {
	$router_settings = get_router_settings();

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
		# Email should be sent as an html-email
		add_filter('wp_mail_content_type', function ($content_type) {
			return 'text/html';
		});

		// Send error mail to netgruppen
		wp_mail("netgruppen@akdtu.dk", "authenticate_router failed", "Forsøgte at logge ind på routeren, men det fejlede da der ikke kunne oprettes forbindelse til routeren. Måske url'en er forkert?<br><br>url: " . $router_settings["url_for_logging_in"] . "<br><br>Dette skal fikses. Intet andet blev gjort.");

		return false;
	}

	# Request successful. Parse results
	$r = json_decode($r);

	# Check if authentication was successful, and return header content to make future requests
	if (array_key_exists("asus_token", $r)) {
		# Authentication success. Extract headers for future requests.
		$new_headers = "user-agent: asusrouter-Android-DUTUtil-1.0.0.245\r\ncookie: asus_token=" . $r->asus_token . "\r\nReferer: https://" . $router_settings["ip"] . "." . $router_settings["port"] . "/device-map/router.asp\r\nContent-Type: application/x-www-form-urlencoded";

		return $new_headers;
	}
	else {
		# Email should be sent as an html-email
		add_filter('wp_mail_content_type', function ($content_type) {
			return 'text/html';
		});

		// Send error mail to netgruppen
		wp_mail("netgruppen@akdtu.dk", "authenticate_router failed", "Forsøgte at logge ind på routeren. Forbindelsen blev oprettet, men routeren ikke sendte positivt svar tilbage. Måske brugernavn eller adgangskode er forkert?<br><br>url: " . $router_settings["url_for_logging_in"] . "<br><br>Dette skal fikses. Intet andet blev gjort.");

		// Authentication failed
		return false;
	}
}

/**
 * Function for updating the password to the router in the common house
 * Performs the authenticating and updating of the password
 * 
 * Returns a key-value array with the SSID and password to the router
 * 
 * @param string $new_password New password
 * 
 * @return array[string,string]|string|false Key-value array with the SSID and password to the router, if update was successful. String containing error-message if authentication was successful, but updating password failed. False if authentication failed.
 */
function set_fælleshus_password($new_password) {
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
			# Email should be sent as an html-email
			add_filter('wp_mail_content_type', function ($content_type) {
				return 'text/html';
			});

			wp_mail("netgruppen@akdtu.dk", "set_fælleshus_password fejlet", "Forsøgte at opdatere routerens adgangskode, men det fejlede.<br><br>url: " . $router_settings["url_for_setting_values"] . "<br><br>Payload: " . $payload . "<br><br>Dette skal fikses. Intet andet blev gjort.");

			# Request failed. Return false.
			return false;
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

/**
 * Function for getting the current SSID and password of the router
 * 
 * Returns a key-value array with the following keys and values:
 * 	- wl0_ssid: SSID of the router
 * 	- wl0_wpa_psk: Password of the router
 * 
 * @return array[string,string]|false Key-value array with the SSID and password to the router, if values were successfully retrieved. False if authentication failed.
 */
function get_fælleshus_password() {
	# Get router password
	return get_fælleshus_info(array('wl0_ssid','wl0_wpa_psk'))['wl0_wpa_psk'];
}

/**
 * Function for getting the current SSID and password of the router
 * 
 * Returns a key-value array with the following keys and values:
 * 	- wl0_ssid: SSID of the router
 * 	- wl0_wpa_psk: Password of the router
 * 
 * @return array[string,string]|false Key-value array with the SSID and password to the router, if values were successfully retrieved. False if authentication failed.
 */
function get_fælleshus_ssid() {
	# Get router password
	return get_fælleshus_info(array('wl0_ssid','wl0_wpa_psk'))['wl0_ssid'];
}

/**
 * Utility function for getting info from the common house router
 * 
 * Returns a key-value array with the following keys and values:
 * 	- wl0_ssid: SSID of the router
 * 	- wl0_wpa_psk: Password of the router
 * 
 * @param string|string[] $datapoints Which datapoints to get. The following are permitted values:
 * 	- cfg_clientlist: Gets basic info about the router
 * 	- clientlist: Gets basic info about all connected devices. Check 'isOnline' property on returned value to check if devices are currently active.
 * 	- wl0_ssid: SSID of the 2.4GHz band.
 * 	- wl0_wpa_psk: Password of the 2.4GHz band.
 * 	- wl1_ssid: SSID of the 5GHz band.
 * 	- wl1_wpa_psk: Password of the 5GHz band.
 * 
 * @return array[string,string]|false Key-value array with the SSID and password to the router, if values were successfully retrieved. False if authentication failed.
 */
function get_fælleshus_info($datapoints = null) {
	# Get router settings
	$router_settings = get_router_settings();

	# Log in to router, and get header information for future request
	$new_headers = authenticate_router($router_settings);

	$hooks = array(
		'cfg_clientlist' => 'get_cfg_clientlist()',
		'clientlist'     => 'get_clientlist()',
		'wl0_ssid'       => 'nvram_char_to_ascii(wl0_ssid,wl0_ssid)',
		'wl0_wpa_psk'    => 'nvram_char_to_ascii(wl0_wpa_psk,wl0_wpa_psk)',
		'wl1_ssid'       => 'nvram_char_to_ascii(wl1_ssid,wl1_ssid)',
		'wl1_wpa_psk'    => 'nvram_char_to_ascii(wl1_wpa_psk,wl1_wpa_psk)',
	);

	# Check if login was successful
	if ($new_headers != false && !is_null($datapoints)) {
		# Prepare information about get-request
		$c['http']['method'] = 'GET';
		$c['http']['header'] = $new_headers;
		$c['ssl']["verify_peer"] = false;
		$c['ssl']["verify_peer_name"] = false;

		# Send get request and return
		return json_decode(urldecode(file_get_contents($router_settings["url_for_getting_values"] . '?hook=' . join("%3B", array_map(function ($datapoint) use($hooks) {return $hooks[$datapoint];}, (is_array($datapoints) ? $datapoints : array($datapoints)))), false, stream_context_create($c))), true);
	}

	return false;
}

?>