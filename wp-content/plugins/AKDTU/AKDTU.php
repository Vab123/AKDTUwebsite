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

include_once "add-widgets.php";

include_once "options/brugeradgang.php";
include_once "options/fælleshus.php";
include_once "options/fælleshus-vlan.php";
include_once "options/lejeradgang.php";
include_once "options/ny-bruger.php";
include_once "options/leje-af-fælleshus-beboer.php";
include_once "options/leje-af-fælleshus-bestyrelse-besluttet.php";
include_once "options/leje-af-fælleshus-bestyrelse-modtaget.php";
include_once "options/havedag-tilmelding-bestyrelse.php";
include_once "options/havedag-tilmelding-beboer.php";

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

	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Leje af fælleshus, beboer', 'Mail: Leje af fælleshus, svar til beboer', 'add_users', 'akdtu-plugin-mail-leje-af-fælleshus-beboer', 'AKDTU_leje_af_fælleshus_beboer_mail_settings', 'dashicons-admin-tools', 65);

	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Leje af fælleshus, bekræftelse', 'Mail: Leje af fælleshus, bekræftelse bestyrelse', 'add_users', 'akdtu-plugin-mail-leje-af-fælleshus-bestyrelse-besluttet', 'AKDTU_leje_af_fælleshus_bestyrelse_besluttet_mail_settings', 'dashicons-admin-tools', 65);

	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Leje af fælleshus, ansøgning', 'Mail: Leje af fælleshus, ansøgning', 'add_users', 'akdtu-plugin-mail-leje-af-fælleshus-bestyrelse-modtaget', 'AKDTU_leje_af_fælleshus_bestyrelse_modtaget_mail_settings', 'dashicons-admin-tools', 65);

	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Tilmelding til havedag, bestyrelse', 'Mail: Tilmelding til havedag, bestyrelse', 'add_users', 'akdtu-plugin-mail-havedag-tilmelding-bestyrelse', 'AKDTU_havedag_tilmelding_bestyrelse_mail_settings', 'dashicons-admin-tools', 65);

	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Tilmelding til havedag, beboer', 'Mail: Tilmelding til havedag, beboer', 'add_users', 'akdtu-plugin-mail-havedag-tilmelding-beboer', 'AKDTU_havedag_tilmelding_beboer_mail_settings', 'dashicons-admin-tools', 65);

}
