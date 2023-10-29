<?php
/*
Plugin Name: AKDTU
Version: 99.9.9
Description: Tilføjer shortcodes og widgets til wordpress
Author: Victor Brandsen
Text Domain: AKDTU
*/

/*
Copyright (c) 2022, Victor Brandsen

*/

include_once "register_settings.php";
include_once "definitions.php";

require_once "register_functions.php";

include_once "add-shortcodes.php";

include_once "add-cronjobs.php";

include_once "export-calendar.php";
include_once "export-mailbox-label.php";

include_once "add-widgets.php";

include_once "register_options.php";
