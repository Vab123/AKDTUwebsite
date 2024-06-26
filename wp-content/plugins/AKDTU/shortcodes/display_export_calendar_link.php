<?php

# Add custom shortcode
add_shortcode("AKDTU-display-calendar-export-link", "AKDTU_display_calendar_export_link");

/**
 * Returns a link to where the user can subscribe to the common house calendar, formatted in a html code-object
 * 
 * @param array $atts Array of settings to be displayed
 * 
 * Default values:
 *   'lang' => 'da': Language of the calendar
 *   'before' => 'Du kan abonnere på kalenderen i Google Calendar, Outlook, mm. med følgende URL: ': Text to write before the html code-object
 *   'after' => '': Text to write after the html code-object
 *	 'version' => '1.0': Version text
 * 
 * @return string Link to where the user can subscribe to the common house calendar, formatted in a html code-object
 */
function AKDTU_display_calendar_export_link($atts) {
	# Default values
	$default = array(
		'lang' 				=> 'da', 																				# Language of the calendar
		'before' 			=> 'Du kan abonnere på kalenderen i Google Calendar, Outlook, mm. med følgende URL: ',	# Text to write before the html code-object
		'after' 			=> '', 																					# Text to write after the html code-object
		'version' 			=> '1.0', 																				# Version text
	);
	
	# Combine default values and provided settings
	$values = shortcode_atts($default, $atts);

	# Get the current user
	$user = wp_get_current_user();

	# Return link to where the user can subscribe to the common house calendar, formatted in a html code-object
	return $values['before'] . '<code style="user-select: all;-webkit-touch-callout: all;-webkit-user-select: all;-khtml-user-select: all;-moz-user-select: all;-ms-user-select: all;line-break: anywhere;">https://akdtu.dk/calendar/' . $values['version'] . '/' . $values['lang'] . '/' . $user->user_login . '/' .  md5($user->user_pass) . '.ics' . '</code>' . $values['after'];
}
