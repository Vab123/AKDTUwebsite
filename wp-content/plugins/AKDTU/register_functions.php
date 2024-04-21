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
		'id' => 0,
		'name' => 'Beboer',
		'user_level' => 'Beboer',
		'user_role' => 'subscriber',
	),
	'default' => array(
		'id' => 1,
		'name' => 'Medlem',
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',
		'user_role' => 'board_member',
	),
	'deputy' => array(
		'id' => 2,
		'name' => 'Suppleant',
		'user_level' => 'Beboerprofil til bestyrelsessuppleant',
		'user_role' => 'deputy',
	),
	'chairman' => array(
		'id' => 3,
		'name' => 'Formand',
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',
		'user_role' => 'board_member',
	),
	'deputy-chairman' => array(
		'id' => 4,
		'name' => 'Næstformand',
		'user_level' => 'Beboerprofil til bestyrelsesmedlem',
		'user_role' => 'board_member',
	),
);

foreach ($AKDTU_FUNCTIONS as $function_file) {
	include_once "functions/" . $function_file;
}

?>