<?php
require_once WP_PLUGIN_DIR . '/AKDTU/functions/notice.php';

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_juster_pris' && isset($_REQUEST['user']) && isset($_REQUEST['price_change'])) {
		fælleshus_juster_pris();
	}
}

function fælleshus_juster_pris(){
	global $wpdb;

	$data = array(
		'apartment' => $_REQUEST['user'] . (isset($_REQUEST['archive']) ? '_archive' : ''),
		'price_change' => intval($_REQUEST['price_change']),
		'change_date' => (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d')
	);

	$inserted = $wpdb->insert($wpdb->prefix . 'em_lejepris_ændringer', $data);

	if ($inserted == 0) {
		new AKDTU_notice('error',$wpdb->last_error);
		
	}else{
		new AKDTU_notice('success','Ændringen i opkrævning af leje blev gemt.');
	}
}
