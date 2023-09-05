<?php

require_once WP_PLUGIN_DIR . '/AKDTU/cronjobs/Fjern_brugeradgang.php';
require_once WP_PLUGIN_DIR . '/AKDTU/cronjobs/Fjern_lejeradgang.php';
require_once WP_PLUGIN_DIR . '/AKDTU/cronjobs/Opkrævning_fælleshus.php';
require_once WP_PLUGIN_DIR . '/AKDTU/cronjobs/Opdater_fælleshus_VLAN.php';

add_action('AKDTUcronjob_fjern_brugeradgang', 'runcronjob_fjern_brugeradgang');
add_action('AKDTUcronjob_fjern_lejeradgang', 'runcronjob_fjern_lejeradgang');
add_action('AKDTUcronjob_opkrævning_fælleshus', 'runcronjob_opkrævning_fælleshus');
add_action('AKDTUcronjob_opdater_fælleshus_vlan', 'runcronjob_opdater_fælleshus_vlan');

function runcronjob_fjern_brugeradgang() {
	send_fjern_brugeradgang();
}

function runcronjob_fjern_lejeradgang() {
	send_fjern_lejeradgang();
}

function runcronjob_opkrævning_fælleshus() {
	send_opkrævning_fælleshus();
}

function runcronjob_opdater_fælleshus_vlan() {
	send_opdater_fælleshus_vlan();
}
