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

include_once "register_definitions.php";

include_once "register_settings.php";

require_once "register_functions.php";

include_once "register_shortcodes.php";

include_once "register_cronjobs.php";

include_once "register_endpoints.php";

include_once "register_widgets.php";

include_once "register_options.php";
