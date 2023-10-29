<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'delete_renter_signup' && isset($_REQUEST['user']) && isset($_REQUEST['phone']) && isset($_REQUEST['start_time']) && isset($_REQUEST['end_time'])) {
		delete_renter_signup();
	}
}

function delete_renter_signup(){
	global $wpdb;
	$data = array(
		'apartment_number' => $_REQUEST['user'],
		'phone_number' => $_REQUEST['phone'],
		'start_time' => $_REQUEST['start_time'],
		'end_time' => $_REQUEST['end_time']
	);
	$deleted = $wpdb->delete($wpdb->prefix . 'swpm_allowed_rentercreation',$data);

	if ($deleted == 0) {
		new AKDTU_notice('error','Der blev ikke fundet nogen midlertidig lejer med de givne oplysninger.');
	}else{
		new AKDTU_notice('success','Den midlertidige lejer blev slettet.');
	}
}
