<?php

function get_router_settings() {
	## Router connection settings
	$router_settings = array(
		"ip" => "82.211.216.215",
		"port" => "8443",
		"username" => "admin",
		"password" => "vr*P6NUsh4XA\$ma1",
		"model" => "RT-AX57",
		"ssid" => "AKDTU Fælleshus",
		"old_pass" => "", # Does not matter, but here for future reference
	);

	$router_settings["url_for_getting_values"] = "https://" . $router_settings["ip"] . ":" . $router_settings["port"] . "/appGet.cgi";
	$router_settings["url_for_setting_values"] = "https://" . $router_settings["ip"] . ":" . $router_settings["port"] . "/start_apply2.htm";
	$router_settings["url_for_logging_in"] = "https://" . $router_settings["ip"] . ":" . $router_settings["port"] . "/login.cgi";

	return $router_settings;
}

function hash_password($event) {
	## Generate password in case the common house is rented for an apartment
	$full_pass = md5($event->event_start_date . ".lejl" . padded_apartment_number_from_id($event->owner));
	return substr($full_pass, 0, 12);
}

function default_password() {
	## Generate password in case the common house is NOT rented for an apartment
	return "Bestyrelsen2023";
}

function generate_password_info($run_every_hours_amount = 24) {
	global $wpdb;

	$default_pass = default_password(); # Default password, set if the common house is not rented by an apartment
	
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	$event_id = $wpdb->get_var('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_start <= "' . $now->format('Y-m-d H:i:s') . '" AND event_end >= "' . $now->format('Y-m-d H:i:s') . '" AND event_status = 1 LIMIT 1');

	$password_should_be_changed = false;
	$send_renter_mail = false;
	$rented_state = 0;

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
		$event_id = $wpdb->get_var('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_end <= "' . $now->format('Y-m-d H:i:s') . '" AND event_status = 1 ORDER BY event_end DESC LIMIT 1'); # Get the ID of the last booking

		$event = em_get_event($event_id, 'event_id');
	
		$new_password = $default_pass;
	}
	
	$event_start_date = new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('Europe/Copenhagen'));

	$password_should_be_changed = $now->diff($event_start_date) < $run_every_hours_amount;

	return array(
		'password' => $new_password,
		'should_be_changed' => $password_should_be_changed,
		'event' => $event,
		'send_mail_to_renter' => $send_renter_mail,
		'rented_state' => $rented_state
	);
}

function authenticate_router($router_settings) {
	$auth = iconv("UTF-8", "ASCII", $router_settings["username"] . ":" . $router_settings["password"]);

	$logintoken = iconv( "ASCII", "UTF-8", base64_encode( $auth ) );

	$payload = "login_authorization=" . $logintoken;

	$c['http']['method'] = 'POST';
	$c['http']['header'] = "user-agent: asusrouter-Android-DUTUtil-1.0.0.245";
	$c['http']['content'] = $payload;
	$c['ssl']["verify_peer"] = false;
	$c['ssl']["verify_peer_name"] = false;

	$r = file_get_contents($router_settings["url_for_logging_in"], false, stream_context_create($c));
 
	if ($r === false) {
		$error = error_get_last();
		throw new Exception($error['message']);
	}

	$r = json_decode($r);

	if (array_key_exists("asus_token", $r)) {
		# Authentication success. Extract headers for future requests.
		$new_headers = "user-agent: asusrouter-Android-DUTUtil-1.0.0.245\r\ncookie: asus_token=" . $r->asus_token . "\r\nReferer: https://" . $router_settings["ip"] . "." . $router_settings["port"] . "/device-map/router.asp\r\nContent-Type: application/x-www-form-urlencoded";

		return $new_headers;
	}
	return false;

}

function update_common_house_internet_password($password_struct) {
	$new_password = $password_struct['password'];

	$router_settings = get_router_settings();

	$new_headers = authenticate_router($router_settings);

	if ($new_headers != false) {
		# Authentication succeeded. Proceed with changing password
		$encoded_old_password = urlencode( $router_settings["old_pass"] );
		$encoded_ssid = urlencode( $router_settings["ssid"] );
	
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
	
				  
		$c['http']['method'] = 'POST';
		$c['http']['header'] = $new_headers;
		$c['http']['content'] = $payload;
		$c['ssl']["verify_peer"] = false;
		$c['ssl']["verify_peer_name"] = false;
	
		$r = file_get_contents($router_settings["url_for_setting_values"], false, stream_context_create($c));

		if ($r === false) {
			$error = error_get_last();
			return $error['message'];
		}
	
		return array(
			'password' => $new_password,
			'ssid' => $router_settings['ssid']
		);
	}
	return false;
}

?>