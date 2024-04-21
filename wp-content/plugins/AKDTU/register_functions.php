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
	"users.php",
	"vlan.php",
);

$AKDTU_BOARD_TYPES = array(
	'none' => array(
		'id' => 0,													# ID of the user type, used in database
		'name' => 'Beboer',											# Human readable name of the user type
		'user_level' => 'Beboer',									# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'subscriber',								# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'default' => array(
		'id' => 1,													# ID of the user type, used in database
		'name' => 'Medlem',											# Human readable name of the user type
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',		# User level of the user type, as defined in the simple-membership plugin
		'user_role' => 'board_member',								# User role of the user type, as defined in wordpress (potentially through functions.php)
	),
	'deputy' => array(
		'id' => 2,													# ID of the user type, used in database
		'name' => 'Suppleant',										# Human readable name of the user type
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
);

foreach ($AKDTU_FUNCTIONS as $function_file) {
	include_once "functions/" . $function_file;
}

?>