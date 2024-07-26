<?php

/**
 * @var $AKDTU_FUNCTIONS Structure containing filenames for all functions to load.
 */
$AKDTU_FUNCTIONS = array(
	"bestyrelsesdokumenter.php",
	"delete_users.php",
	"fælleshus.php",
	"fælleshus_internet.php",
	"havedag.php",
	"notice.php",
	"render_options_page.php",
	"send_mail.php",
	"user_registration.php",
	"users.php",
	"vlan.php",
);

$AKDTU_USER_TYPES = array(
	'vicevært' => array(
		'id' => -1,													# ID of the user type, used in database
		'name' => 'Vicevært',										# Human readable name of the user type
		'user_level' => 'Vicevært',									# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'vicevaert',									# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'none' => array(
		'id' => 0,													# ID of the user type, used in database
		'name' => 'Beboer',											# Human readable name of the user type
		'user_level' => 'Beboer',									# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'subscriber',								# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'default' => array(
		'id' => 1,													# ID of the user type, used in database
		'name' => 'Bestyrelsesmedlem',								# Human readable name of the user type
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',		# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'board_member',								# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'deputy' => array(
		'id' => 2,													# ID of the user type, used in database
		'name' => 'Bestyrelsessuppleant',							# Human readable name of the user type
		'user_level' => 'Beboerprofil til bestyrelsessuppleant',	# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'deputy',									# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'chairman' => array(
		'id' => 3,													# ID of the user type, used in database
		'name' => 'Formand',										# Human readable name of the user type
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',		# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'board_member',								# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'deputy-chairman' => array(
		'id' => 4,													# ID of the user type, used in database
		'name' => 'Næstformand',									# Human readable name of the user type
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',		# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'board_member',								# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'renter' => array(
		'id' => 5,													# ID of the user type, used in database
		'name' => 'Midlertidig lejer',								# Human readable name of the user type
		'user_level' => 'Midlertidig lejer',						# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'subscriber',								# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'archive' => array(
		'id' => 6,													# ID of the user type, used in database
		'name' => 'Archive bruger',									# Human readable name of the user type
		'user_level' => 'Archive bruger',							# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'subscriber',								# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'website-admin' => array(
		'id' => 7,													# ID of the user type, used in database
		'name' => 'Website administrator',							# Human readable name of the user type
		'user_level' => 'Administrator',							# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'administrator',								# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
);

$KNET_USER_TYPES = array(
	'none' => array(
		'id' => -1,													# ID of the user type, used in database
		'name' => 'Ikke relateret',									# Human readable name of the user type
	),
	'representative' => array(
		'id' => 0,													# ID of the user type, used in database
		'name' => 'K-NET Repræsentant',								# Human readable name of the user type
	),
	'deputy' => array(
		'id' => 1,													# ID of the user type, used in database
		'name' => 'K-NET Suppleant',								# Human readable name of the user type
	),
);

foreach ($AKDTU_FUNCTIONS as $function_file) {
	include_once "functions/" . $function_file;
}

?>