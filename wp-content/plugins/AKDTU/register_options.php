<?php

/**
 * @var $AKDTU_OPTIONS Structure containing information about all option pages to load.
 * 
 * Structure:
 * 
 * - "optionfilename" => array
 * > - "page-title" => string
 * > - "menu-title" => string
 * > - "menu-slug" => string
 * > - "function" => string
 * 
 * Keys: 
 * - "page-title": String. Title of the options page. Shown at the top of the page.
 * - "menu-title": String. Title of the menu object. Shown in the menu at the left of the control panel.
 * - "menu-slug": String. Slug for the menu page. Part of the page URL.
 * - "function": String. Name of the function to call to get the content of the menu page.
 * - "optionfilename": String. Filename of the file with the option function.
 */
$AKDTU_OPTIONS = array(
	"fælleshus.php" => array(
		"page-title" => "Mail: Fælleshus opkrævning",
		"menu-title" => "Mail: Fælleshus opkrævning",
		"menu-slug" => "akdtu-plugin-fælleshus-opkrævning",
		"function" => "AKDTU_fælleshus_mail_settings",
	),
	"fælleshus-internet.php" => array(
		"page-title" => "Mail: Fælleshus internet",
		"menu-title" => "Mail: Fælleshus internet",
		"menu-slug" => "akdtu-plugin-mail-fælleshus-internet",
		"function" => "AKDTU_fælleshus_internet_mail_settings",
	),
	"brugeradgang.php" => array(
		"page-title" => "Mail: Bruger adgang",
		"menu-title" => "Mail: Bruger adgang",
		"menu-slug" => "akdtu-plugin-mail-bruger-adgang",
		"function" => "AKDTU_brugeradgang_mail_settings",
	),
	"lejeradgang.php" => array(
		"page-title" => "Mail: Lejer adgang",
		"menu-title" => "Mail: Lejer adgang",
		"menu-slug" => "akdtu-plugin-mail-lejer-adgang",
		"function" => "AKDTU_lejeradgang_mail_settings",
	),
	"ny-bruger.php" => array(
		"page-title" => "Mail: Ny bruger",
		"menu-title" => "Mail: Ny bruger",
		"menu-slug" => "akdtu-plugin-mail-ny-bruger",
		"function" => "AKDTU_ny_bruger_mail_settings",
	),
	"havedag-tilmelding-beboer.php" => array(
		"page-title" => "Mail: Havedag tilmelding - beboer",
		"menu-title" => "Mail: Havedag tilmelding - beboer",
		"menu-slug" => "akdtu-plugin-havedag-tilmelding-beboer-mail-settings",
		"function" => "AKDTU_havedag_tilmelding_beboer_mail_settings",
	),
	"havedag-tilmelding-bestyrelse.php" => array(
		"page-title" => "Mail: Havedag tilmelding - bestyrelse",
		"menu-title" => "Mail: Havedag tilmelding - bestyrelse",
		"menu-slug" => "akdtu-plugin-havedag-tilmelding-bestyrelse-mail-settings",
		"function" => "AKDTU_havedag_tilmelding_bestyrelse_mail_settings",
	),
	"leje-af-fælleshus-beboer.php" => array(
		"page-title" => "Mail: Leje af fælleshus - beboer",
		"menu-title" => "Mail: Leje af fælleshus - beboer",
		"menu-slug" => "akdtu-plugin-leje-af-fælleshus-beboer-mail-settings",
		"function" => "AKDTU_leje_af_fælleshus_beboer_mail_settings",
	),
	"leje-af-fælleshus-bestyrelse-modtaget.php" => array(
		"page-title" => "Mail: Leje af fælleshus - bestyrelse, modtaget",
		"menu-title" => "Mail: Leje af fælleshus - bestyrelse, modtaget",
		"menu-slug" => "akdtu-plugin-leje-af-fælleshus-bestyrelse-modtaget-mail-settings",
		"function" => "AKDTU_leje_af_fælleshus_bestyrelse_modtaget_mail_settings",
	),
	"leje-af-fælleshus-bestyrelse-besluttet.php" => array(
		"page-title" => "Mail: Leje af fælleshus - bestyrelse, besluttet",
		"menu-title" => "Mail: Leje af fælleshus - bestyrelse, besluttet",
		"menu-slug" => "akdtu-plugin-leje-af-fælleshus-bestyrelse-besluttet-mail-settings",
		"function" => "AKDTU_leje_af_fælleshus_bestyrelse_besluttet_mail_settings",
	),
	"havedag.php" => array(
		"page-title" => "Mail: Havedag opkrævning",
		"menu-title" => "Mail: Havedag opkrævning",
		"menu-slug" => "akdtu-plugin-havedag-opkrævning-settings",
		"function" => "AKDTU_havedag_opkrævning_mail_settings",
	),
);

add_action('admin_menu', 'AKDTU_menu');

function AKDTU_menu() {
	global $AKDTU_OPTIONS;

	add_menu_page('AKDTU', 'AKDTU', 'add_users', 'akdtu-plugin-fælleshus-opkrævning', '', 'dashicons-admin-tools', 65);

	foreach ($AKDTU_OPTIONS as $option_file => $option_spec) {
		include_once "options/" . $option_file;

		add_submenu_page('akdtu-plugin-fælleshus-opkrævning', $option_spec["page-title"], $option_spec["menu-title"], 'add_users', $option_spec["menu-slug"], $option_spec["function"], 'dashicons-admin-tools', 65);
	}
}

?>