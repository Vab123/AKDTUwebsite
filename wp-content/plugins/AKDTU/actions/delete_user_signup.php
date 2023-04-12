<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'delete_user_signup' && isset($_REQUEST['user']) && isset($_REQUEST['phone']) && isset($_REQUEST['takeover_time'])) {
		delete_user_signup();
	}
}

function delete_user_signup(){
	global $wpdb;
	$data = array(
		'apartment_number' => $_REQUEST['user'],
		'phone_number' => $_REQUEST['phone'],
		'allow_creation_date' => $_REQUEST['takeover_time']
	);
	$deleted = $wpdb->delete($wpdb->prefix . 'swpm_allowed_membercreation',$data);

	if ($deleted == 0) {
		new AKDTU_notice('error','Der blev ikke fundet nogen bruger med de givne oplysninger.');
	}else{
		new AKDTU_notice('success','Tilladelsen til brugeroprettelse blev slettet.');
	}
}
