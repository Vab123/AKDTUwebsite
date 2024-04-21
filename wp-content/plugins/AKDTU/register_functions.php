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
	'chairman' => array(
		'id' => 1,
		'name' => 'Formand',
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',
		'user_role' => 'board_member',
	),
	'deputy-chairman' => array(
		'id' => 2,
		'name' => 'Næstformand',
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',
		'user_role' => 'board_member',
	),
	'deputy' => array(
		'id' => 3,
		'name' => 'Suppleant',
		'user_level' => 'Beboerprofil til bestyrelsessuppleant',
		'user_role' => 'deputy',
	),
	'default' => array(
		'id' => 0,
		'name' => 'Medlem',
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',
		'user_role' => 'board_member',
	),
);

foreach ($AKDTU_FUNCTIONS as $function_file) {
	include_once "functions/" . $function_file;
}

?>