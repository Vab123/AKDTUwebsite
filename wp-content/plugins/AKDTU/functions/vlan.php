<?php

/**
 * @param int $vlan_id ID of the VLAN to change
 * @param int|bool $state Desired state of vlan
 */
function set_vlan($vlan_id, $state) {
	if (!is_int($vlan_id)) {
		new AKDTU_notice('error', 'DEBUG: VLAN id empty or not integer');
		return false;
	}

	if (is_bool($state)) {
		$state = ($state ? 1 : 0);
	} elseif (is_int($state)) {
		if (!($state == 1 || $state == 0)) {
			new AKDTU_notice('error', 'DEBUG: State was integer, but not 1 or 0');
			return false;
		}
	} else {
		new AKDTU_notice('error', 'DEBUG: state has to be an integer or boolean. Type was ' . gettype($state));
		return false;
	}

	$c['http']['method'] = 'PUT';
	$c['http']['header'] = "Content-Type: application/json\r\nAuthorization: Basic " . base64_encode('api_akdnetgrp:Lit5dok4cah1Ohng');
	$c['http']['content'] = '
		{
			"id": ' . $vlan_id . ', 
			"dorm": "akd", 
			"vlan_type": 0, 
			"state": ' . $state . ',
			"comment": "API change from akdtu.dk - netgruppen@akdtu.dk"
		}
		';
	$r = @file_get_contents('https://api.k-net.dk/v2/network/vlan/' . $vlan_id . '/', false, stream_context_create($c));

	if ($r === false) {
		$error = error_get_last();
		throw new Exception($error['message']);
	}

	$data = json_decode($r);
	return $data;
}

/**
 * @param int $vlan_id ID of the VLAN to retrieve info
 */
function get_vlan($vlan_id) {
	if (!is_int($vlan_id)) {
		new AKDTU_notice('error', 'DEBUG: VLAN id empty or not integer');
		return false;
	}

	$c['http']['method'] = 'GET';
	$c['http']['header'] = "Content-Type: application/json\r\nAuthorization: Basic " . base64_encode('api_akdnetgrp:Lit5dok4cah1Ohng');
	$r = @file_get_contents('https://api.k-net.dk/v2/network/vlan/' . $vlan_id . '/', false, stream_context_create($c));

	if ($r === false) {
		$error = error_get_last();
		throw new Exception($error['message']);
	}

	$data = json_decode($r);
	return $data;
}

/**
 * @param int|bool $state Desired state of vlan
 */
function set_fælleshus_vlan($state) {
	return set_vlan(624, $state);
}

/**
 * @param
 */
function get_fælleshus_vlan() {
	return get_vlan(624);
}
