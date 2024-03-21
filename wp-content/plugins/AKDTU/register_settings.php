<?php

/**
 * @var $AKDTU_CONSTANTS Structure containing information about all settings and constants to load.
 * 
 * Structure:
 * - "CONSTANT_ROOT" => array
 * > - "additional_constant",
 * > - ...
 * 
 * Keys: 
 * - "CONSTANT_ROOT": Root of the defined constants. E.g. "AKDTU_EMAIL_TO" would be created from `CONSTANT_ROOT="EMAIL"`.
 * - "additional_constant": Additional constant to define. E.g. `additional_constant => "_FORMAT", CONSTANT_ROOT => "EMAIL"` would create `AKDTU_EMAIL_FORMAT`.
 */
$AKDTU_CONSTANTS = array(
	"FJERNBRUGERADGANG" => array(				# Mail med info om fjernet brugeradgang
		"_FORMAT_RENTALS", 						# Format for reservationer af fælleshuset
		"_FORMAT_PREVIOUS_GARDENDAYS", 			# Format for tilmeldinger til tidligere havedage
		"_FORMAT_FUTURE_GARDENDAYS" 			# Format for tilmeldinger til fremtidige havedage
	),
	"FJERNLEJERADGANG" => array(				# Mail med info om fjernet lejeradgang
		"_FORMAT_RENTALS", 						# Format for reservationer af fælleshuset
		"_FORMAT_PREVIOUS_GARDENDAYS", 			# Format for tilmeldinger til tidligere havedage
		"_FORMAT_FUTURE_GARDENDAYS" 			# Format for tilmeldinger til fremtidige havedage
	),
	"FÆLLESHUS" => array(						# Mail om opkrævning for leje af fælleshus
		'_FORMAT',								# Format af opkrævning
	),
	"FÆLLESHUS_INTERNET" => array(), 			# Mail om ændring af VLAN status i fælleshuset
	"FÆLLESHUS_INTERNET_BRUGER_DA" => array(	# Mail om ændring af VLAN status i fælleshuset
		"_TOGGLE"
	),
	"FÆLLESHUS_INTERNET_BRUGER_EN" => array(	# Mail om ændring af VLAN status i fælleshuset
		"_TOGGLE"
	),
	"NYBRUGER_BRUGER_DA" => array(), 			# Mail med info om ny bruger
	"NYBRUGER_BRUGER_EN" => array(), 			# Mail med info om ny bruger
	"NYBRUGER_BESTYRELSE" => array(), 			# Mail med info om ny bruger
	"HAVEDAG" => array(
		"_DAYS",
		"_FORMAT",
		"_BOARDMEMBER",
		"_BOARD_DEPUTY",
	),
	"HAVEDAG_WARNING" => array(
		"_DAYS",
	),
);

/**
 * Registers a series of settings for custom AKDTU emails.
 * 
 * Registers `AKDTU_..._TO`, `AKDTU_..._FROM`, `AKDTU_..._REPLYTO`, `AKDTU_..._CC`, `AKDTU_..._SUBJECT`, `AKDTU_..._MAILCONTENT`, `AKDTU_..._ATTACHMENTS`, as well as any additionally specified settings.
 * 
 * @param string $CONSTANT_ROOT Root name of the settings to define. E.g. "AKDTU_EMAIL_TO" would be created from `$CONSTANT_ROOT="EMAIL"`.
 * @param array $additionals Additional settings to define. E.g. `$additionals=array("_FORMAT"); $CONSTANT_ROOT="EMAIL"` would create `AKDTU_EMAIL_FORMAT`.
 */
function AKDTU_REGISTER($CONSTANT_ROOT, $additionals = array()) {
	# Register default settings
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_TO', array('type' => 'string', 'default' => '')); 			# To address.
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_FROM', array('type' => 'string', 'default' => '')); 			# From address.
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_REPLYTO', array('type' => 'string', 'default' => '')); 		# Reply-to address. Set to '' for no reply-to address.
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_CC', array('type' => 'string', 'default' => '')); 			# CC address. Set to '' for no cc address.
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_SUBJECT', array('type' => 'string', 'default' => '')); 		# Email subject.
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_MAILCONTENT', array('type' => 'string', 'default' => ''));	# Email content.
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_ATTACHMENTS', array('type' => 'string', 'default' => '')); 	# Email attachments.

	# Register any additional settings
	foreach ($additionals as $additional) {
		register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . $additional, array('type' => 'string', 'default' => ''));
	}
}

/**
 * Registers a series of constants for custom AKDTU emails.
 * 
 * Registers `..._TO`, `..._FROM`, `..._REPLYTO`, `..._CC`, `..._SUBJECT`, `..._MAILCONTENT`, `..._ATTACHMENTS`, as well as any additionally specified constants.
 * 
 * @param string $CONSTANT_ROOT Root name of the constants to define. E.g. "EMAIL_TO" would be created from `$CONSTANT_ROOT="EMAIL"`.
 * @param array $additionals Additional constants to define. E.g. `$additionals=array("_FORMAT"); $CONSTANT_ROOT="EMAIL"` would create `EMAIL_FORMAT`.
 */
function AKDTU_DEFINE($CONSTANT_ROOT, $additionals = array()) {
	# Define default constants
	define($CONSTANT_ROOT . '_TO', get_option('AKDTU_' . $CONSTANT_ROOT . '_TO')); 						# To address.
	define($CONSTANT_ROOT . '_FROM', get_option('AKDTU_' . $CONSTANT_ROOT . '_FROM')); 					# From address.
	define($CONSTANT_ROOT . '_REPLYTO', get_option('AKDTU_' . $CONSTANT_ROOT . '_REPLYTO')); 			# Reply-to address. Set to '' for no reply-to address.
	define($CONSTANT_ROOT . '_CC', get_option('AKDTU_' . $CONSTANT_ROOT . '_CC')); 						# CC address. Set to '' for no cc address.
	define($CONSTANT_ROOT . '_SUBJECT', get_option('AKDTU_' . $CONSTANT_ROOT . '_SUBJECT')); 			# Email subject.
	define($CONSTANT_ROOT . '_MAILCONTENT', get_option('AKDTU_' . $CONSTANT_ROOT . '_MAILCONTENT')); 	# Email content.
	define($CONSTANT_ROOT . '_ATTACHMENTS', get_option('AKDTU_' . $CONSTANT_ROOT . '_ATTACHMENTS')); 	# Email attachments.

	# Define any additional constants
	foreach ($additionals as $additional) {
		define($CONSTANT_ROOT . $additional, get_option('AKDTU_' . $CONSTANT_ROOT . $additional));
	}
}

foreach ($AKDTU_CONSTANTS as $constant_root => $additionals) {
	AKDTU_REGISTER($constant_root, $additionals);
	AKDTU_DEFINE($constant_root, $additionals);
}





# Additional settings and constants
define('WORKING_DIR', ABSPATH); 								# Working directory on the server.
define('BESTYRELSE_FOLDER', 'public_files/bestyrelsesmøder/');	# Where minutes from Board meetings should be saved.
define('GF_FOLDER', 'public_files/generalforsamlinger/'); 		# Where minutes from General Assemblies should be saved.
define('ÅRSRAPPORT_FOLDER', 'public_files/årsrapporter/'); 		# Where annual reports should be saved.
define('BUDGET_FOLDER', 'public_files/budgetter/'); 			# Where budgets should be saved.
define('AKDTU_DIR', WP_PLUGIN_DIR . '/AKDTU');

register_setting('options', 'NYBRUGER_BRUGER_TOGGLE', array('type' => 'string', 'default' => '')); # Whether emails should be sent to new users
define('NYBRUGER_BRUGER_TOGGLE', get_option('AKDTU_NYBRUGER_BRUGER_TOGGLE'));