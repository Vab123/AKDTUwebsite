<?php

add_shortcode("AKDTU-display-calendar-export-link", "AKDTU_display_calendar_export_link");

function AKDTU_display_calendar_export_link($atts) {
	$default = array(
		'lang' => 'da', # Format for output for date of document
		'before' => 'Du kan abonnere på kalenderen i Google Calendar, Outlook, mm. med følgende URL: ', # Format for output for date of document
		'after' => '', # Format for output for date of document
	);
	$values = shortcode_atts($default, $atts);

	$user = wp_get_current_user();

	return $values['before'] . '<code>https://akdtu.dk/export-calendar.php?lang=' . $values['lang'] . '&user=' . $user->user_login . '&auth=' .  md5($user->user_pass) . '</code>' . $values['after'];
}
