<?php

// Allow extention of styling from Neve-theme
add_action('wp_enqueue_scripts', 'AKDTU_enqueue_styles');
function AKDTU_enqueue_styles() {
	wp_enqueue_style(
		'child-style',
		get_stylesheet_uri()
	);
}




require_once "translations/contact-form-7.php";
require_once "translations/events-manager.php";
require_once "translations/simple-membership.php";
require_once "translations/Wordpress.php";

require_once "cover-changes.php";

require_once "mail_filters.php";

require_once "custom_roles.php";

require_once "hide_admin_bars.php";

require_once "custom_user_fields.php";
