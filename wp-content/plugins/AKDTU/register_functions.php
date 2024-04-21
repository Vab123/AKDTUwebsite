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
		'name' => 'Formand'
	),
	'deputy-chairman' => array(
		'id' => 2,
		'name' => 'Næstformand'
	),
	'default' => array(
		'id' => 0,
		'name' => 'Medlem'
	),
);

foreach ($AKDTU_FUNCTIONS as $function_file) {
	include_once "functions/" . $function_file;
}

?>