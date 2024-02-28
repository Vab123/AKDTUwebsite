<?php

/**
 * @var $AKDTU_FUNCTIONS Structure containing filenames for all functions to load.
 */
$AKDTU_FUNCTIONS = array(
	"bestyrelsesdokumenter.php",
	"delete_users.php",
	"fælleshus.php",
	"fælleshus_internet.php",
	"notice.php",
	"send_mail.php",
	"users.php",
	"vlan.php",
);

foreach ($AKDTU_FUNCTIONS as $function_file) {
	include_once "functions/" . $function_file;
}

?>