<?php

/**
 * @file Functionality related to the manipulation of VLANS using the K-Net API (https://wiki.k-net.dk/api-instructions)
 */

$AKDTU_VLANS = array(
	'001' => 550,
	'002' => 551,
	'003' => 552,
	'004' => 553,
	'005' => 554,
	'006' => 555,
	'007' => 556,
	'008' => 557,
	'009' => 558,
	'010' => 559,
	'011' => 560,
	'012' => 561,
	'013' => 562,
	'014' => 563,
	'015' => 564,
	'016' => 565,
	'017' => 566,
	'018' => 567,
	'019' => 568,
	'020' => 569,
	'021' => 570,
	'022' => 571,
	'023' => 572,
	'024' => 573,
	'101' => 574,
	'102' => 575,
	'103' => 576,
	'104' => 577,
	'105' => 578,
	'106' => 579,
	'107' => 580,
	'108' => 581,
	'109' => 582,
	'110' => 583,
	'111' => 584,
	'112' => 585,
	'113' => 586,
	'114' => 587,
	'115' => 588,
	'116' => 589,
	'117' => 590,
	'118' => 591,
	'119' => 592,
	'120' => 593,
	'121' => 594,
	'122' => 595,
	'123' => 596,
	'124' => 597,
	'201' => 598,
	'202' => 599,
	'203' => 600,
	'204' => 601,
	'205' => 602,
	'206' => 603,
	'207' => 604,
	'208' => 605,
	'209' => 606,
	'210' => 607,
	'211' => 608,
	'212' => 609,
	'213' => 610,
	'214' => 611,
	'215' => 612,
	'216' => 613,
	'217' => 614,
	'218' => 615,
	'219' => 616,
	'220' => 617,
	'221' => 618,
	'222' => 619,
	'223' => 620,
	'224' => 621,
	'servers' => 622,
	'fælleshus_adk' => 623,
	'fælleshus' => 624,
);

/**
 * Set the state of a VLAN
 * 
 * @param int $vlan_id ID of the VLAN to change
 * @param int|bool $state Desired state of vlan
 * 
 * @return array[Any] json-decoded response from K-Net API
 */
function set_vlan($vlan_id, $state) {
	global $AKDTU_VLANS;

	// Check if selected VLAN is of correct type
	if (!is_int($vlan_id)) {
		new AKDTU_notice('error', 'DEBUG: VLAN id empty or not integer');
		return false;
	}

	// Check if selected VLAN is valid
	if (!in_array($vlan_id, array_keys($AKDTU_VLANS))) {
		new AKDTU_notice('error', 'DEBUG: VLAN ' . $vlan_id . ' not found in list of valid VLANs');
		return false;
	}

	// Translate state into integer
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

	// Prepare data payload
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
	
	// Send data payload
	$r = @file_get_contents('https://api.k-net.dk/v2/network/vlan/' . $vlan_id . '/', false, stream_context_create($c));

	// Check if an error was recieved
	if ($r === false) {
		$error = error_get_last();
		throw new Exception($error['message']);
	}

	// Decode returned data
	$data = json_decode($r);

	// Return returned data
	return $data;
}

/**
 * Get info about a VLAN
 * 
 * @param int $vlan_id ID of the VLAN to retrieve info
 * 
 * @return array[Any] json-decoded response from K-Net API
 */
function get_vlan($vlan_id) {
	global $AKDTU_VLANS;

	// Check if selected VLAN is of correct type
	if (!is_int($vlan_id)) {
		new AKDTU_notice('error', 'DEBUG: VLAN id empty or not integer');
		return false;
	}

	// Check if selected VLAN is valid
	if (!in_array($vlan_id, array_keys($AKDTU_VLANS))) {
		new AKDTU_notice('error', 'DEBUG: VLAN ' . $vlan_id . ' not found in list of valid VLANs');
		return false;
	}

	// Prepare payload
	$c['http']['method'] = 'GET';
	$c['http']['header'] = "Content-Type: application/json\r\nAuthorization: Basic " . base64_encode('api_akdnetgrp:Lit5dok4cah1Ohng');

	// Send payload
	$r = @file_get_contents('https://api.k-net.dk/v2/network/vlan/' . $vlan_id . '/', false, stream_context_create($c));

	// Check if an error was recieved
	if ($r === false) {
		$error = error_get_last();
		throw new Exception($error['message']);
	}

	// Decode returned data
	$data = json_decode($r);

	// Return returned data
	return $data;
}

/**
 * Set the state of the VLAN corresponding to the common house internet
 * 
 * @param int|bool $state Desired state of vlan
 * 
 * @return array[Any] json-decoded response from K-Net API
 */
function set_fælleshus_vlan($state) {
	global $AKDTU_VLANS;

	# Set desired state and return
	return set_vlan($AKDTU_VLANS['fælleshus'], $state);
}

/**
 * Get info about the VLAN corresponding to the common house internet
 * 
 * @return array[Any] json-decoded response from K-Net API
 */
function get_fælleshus_vlan() {
	global $AKDTU_VLANS;

	# Get info and return
	return get_vlan($AKDTU_VLANS['fælleshus']);
}
