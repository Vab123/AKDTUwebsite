<?php

/**
 * @var $AKDTU_SHORTCODES Structure containing filenames for all shortcodes to load.
 */
$AKDTU_SHORTCODES = array(
	"display_list.php",
	"display_bestyrelsesmøder.php",
	"display_budget.php",
	"display_årsrapport.php",
	"display_generalforsamlinger.php",
	"display_export_calendar_link.php",
	"download_mailbox_label.php",
	"contact-form-7-custom-tags.php",
	"board_list.php",
	"networkgroup_list.php",
);

foreach ($AKDTU_SHORTCODES as $shortcode_file) {
	include_once "shortcodes/" . $shortcode_file;
}
