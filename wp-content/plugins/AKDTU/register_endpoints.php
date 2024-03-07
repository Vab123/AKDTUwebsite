<?php

/**
 * @var $AKDTU_ENDPOINTS Structure containing filenames for all endpoints to load.
 */
$AKDTU_ENDPOINTS = array(
	"export-calendar.php",
	// "export-mailbox-label.php",
);

foreach ($AKDTU_ENDPOINTS as $endpoint_file) {
	include_once "endpoints/" . $endpoint_file;
}

?>