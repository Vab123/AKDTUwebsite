<?php
/*
Plugin Name: AKDTU
Version: 99.9.9
Description: Tilføjer shortcodes og widgets til wordpress
Author: Victor Brandsen
Text Domain: AKDTU
*/

/*
Copyright (c) 2022, Victor Brandsen

*/

include_once "register_settings.php";
include_once "definitions.php";

include_once "add-shortcodes.php";

include_once "add-cronjobs.php";

include_once "export-calendar.php";
include_once "export-mailbox-label.php";

include_once "add-widgets.php";

include_once "options/brugeradgang.php";
include_once "options/fælleshus.php";
include_once "options/fælleshus-vlan.php";
include_once "options/lejeradgang.php";
include_once "options/ny-bruger.php";

add_action('admin_menu', 'AKDTU_menu');

function AKDTU_settings() {
}

function AKDTU_menu() {
	add_menu_page('AKDTU', 'AKDTU', 'add_users', 'akdtu-plugin-fælleshus-opkrævning', 'AKDTU_settings', 'dashicons-admin-tools', 65);
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Fælleshus opkrævning', 'Mail: Fælleshus opkrævning', 'add_users', 'akdtu-plugin-fælleshus-opkrævning', 'AKDTU_fælleshus_mail_settings', 'dashicons-admin-tools', 65);
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Bruger adgang', 'Mail: Bruger adgang', 'add_users', 'akdtu-plugin-mail-bruger-adgang', 'AKDTU_brugeradgang_mail_settings', 'dashicons-admin-tools', 65);
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Lejer adgang', 'Mail: Lejer adgang', 'add_users', 'akdtu-plugin-mail-lejer-adgang', 'AKDTU_lejeradgang_mail_settings', 'dashicons-admin-tools', 65);
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Ny bruger', 'Mail: Ny bruger', 'add_users', 'akdtu-plugin-mail-ny-bruger', 'AKDTU_ny_bruger_mail_settings', 'dashicons-admin-tools', 65);
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Fælleshus VLAN', 'Mail: Fælleshus VLAN', 'add_users', 'akdtu-plugin-mail-fælleshus-vlan', 'AKDTU_fælleshus_vlan_mail_settings', 'dashicons-admin-tools', 65);
}
