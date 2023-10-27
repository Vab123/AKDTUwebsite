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

include_once "options/fælleshus.php";
include_once "options/fælleshus-vlan.php";
include_once "options/brugeradgang.php";
include_once "options/lejeradgang.php";
include_once "options/ny-bruger.php";
include_once "options/havedag-tilmelding-beboer.php";
include_once "options/havedag-tilmelding-bestyrelse.php";
include_once "options/leje-af-fælleshus-beboer.php";
include_once "options/leje-af-fælleshus-bestyrelse-modtaget.php";
include_once "options/leje-af-fælleshus-bestyrelse-besluttet.php";
include_once "options/havedag.php";

add_action('admin_menu', 'AKDTU_menu');

function AKDTU_settings() {
}

function AKDTU_menu() {
	add_menu_page('AKDTU', 'AKDTU', 'add_users', 'akdtu-plugin-fælleshus-opkrævning', 'AKDTU_settings', 'dashicons-admin-tools', 65);

	// options/fælleshus.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Fælleshus opkrævning', 'Mail: Fælleshus opkrævning', 'add_users', 'akdtu-plugin-fælleshus-opkrævning', 'AKDTU_fælleshus_mail_settings', 'dashicons-admin-tools', 65);

	// options/fælleshus-vlan.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Fælleshus VLAN', 'Mail: Fælleshus VLAN', 'add_users', 'akdtu-plugin-mail-fælleshus-vlan', 'AKDTU_fælleshus_vlan_mail_settings', 'dashicons-admin-tools', 65);

	// options/brugeradgang.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Bruger adgang', 'Mail: Bruger adgang', 'add_users', 'akdtu-plugin-mail-bruger-adgang', 'AKDTU_brugeradgang_mail_settings', 'dashicons-admin-tools', 65);

	// options/lejeradgang.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Lejer adgang', 'Mail: Lejer adgang', 'add_users', 'akdtu-plugin-mail-lejer-adgang', 'AKDTU_lejeradgang_mail_settings', 'dashicons-admin-tools', 65);

	// options/ny-bruger.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Ny bruger', 'Mail: Ny bruger', 'add_users', 'akdtu-plugin-mail-ny-bruger', 'AKDTU_ny_bruger_mail_settings', 'dashicons-admin-tools', 65);
	
	// options/havedag-tilmelding-beboer.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Havedag tilmelding - beboer', 'Mail: Havedag tilmelding - beboer', 'add_users', 'akdtu-plugin-havedag-tilmelding-beboer-mail-settings', 'AKDTU_havedag_tilmelding_beboer_mail_settings', 'dashicons-admin-tools', 65);
	
	// options/havedag-tilmelding-bestyrelse.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Havedag tilmelding - bestyrelse', 'Mail: Havedag tilmelding - bestyrelse', 'add_users', 'akdtu-plugin-havedag-tilmelding-bestyrelse-mail-settings', 'AKDTU_havedag_tilmelding_bestyrelse_mail_settings', 'dashicons-admin-tools', 65);
	
	// options/leje-af-fælleshus-beboer.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Leje af fælleshus - beboer', 'Mail: Leje af fælleshus - beboer', 'add_users', 'akdtu-plugin-leje-af-fælleshus-beboer-mail-settings', 'AKDTU_leje_af_fælleshus_beboer_mail_settings', 'dashicons-admin-tools', 65);
	
	// options/leje-af-fælleshus-bestyrelse-modtaget.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Leje af fælleshus - bestyrelse, modtaget', 'Mail: Leje af fælleshus - bestyrelse, modtaget', 'add_users', 'akdtu-plugin-leje-af-fælleshus-bestyrelse-modtaget-mail-settings', 'AKDTU_leje_af_fælleshus_bestyrelse_modtaget_mail_settings', 'dashicons-admin-tools', 65);
	
	// options/leje-af-fælleshus-bestyrelse-besluttet.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Leje af fælleshus - bestyrelse, besluttet', 'Mail: Leje af fælleshus - bestyrelse, besluttet', 'add_users', 'akdtu-plugin-leje-af-fælleshus-bestyrelse-besluttet-mail-settings', 'AKDTU_leje_af_fælleshus_bestyrelse_besluttet_mail_settings', 'dashicons-admin-tools', 65);
	
	// options/havedag.php
	add_submenu_page('akdtu-plugin-fælleshus-opkrævning', 'Mail: Havedag opkrævning', 'Mail: Havedag opkrævning', 'add_users', 'akdtu-plugin-havedag-opkrævning-settings', 'AKDTU_havedag_opkrævning_mail_settings', 'dashicons-admin-tools', 65);
}
