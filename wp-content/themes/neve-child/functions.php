<?php

// Allow extention of styling from Neve-theme
add_action('wp_enqueue_scripts', 'AKDTU_enqueue_styles');
function AKDTU_enqueue_styles() {
	wp_enqueue_style(
		'child-style',
		get_stylesheet_uri()
	);
}

// Change the context of event-pages to page, such that event-pages get a cover-photo as well.
add_filter( 'neve_context_filter', function ($context) { return $context === 'event' ? 'page' : $context; }, 1, 1 );



require_once "translations/contact-form-7.php";
require_once "translations/events-manager.php";
require_once "translations/simple-membership.php";

require_once "mail_filters.php";

require_once "custom_roles.php";

require_once "hide_admin_bars.php";

require_once "custom_user_fields.php";
