<?php

# Add custom shortcode
add_shortcode("AKDTU-download-mailbox-label", "AKDTU_download_mailbox_label");

/**
 * Returns a html form-object where the user can generate a label for their mailbox
 * 
 * The full name of the current user is used as a default value
 * 
 * @param array $atts Array of settings to be displayed
 * 
 * Default values:
 *   'name-placeholder' => 'Navn': Placeholder in html input-element for the name of the user
 * 
 * @return string Html form-object where the user can generate a label for their mailbox
 */
function AKDTU_download_mailbox_label( $atts ){
	# Default values
	$default = array(
		'name-placeholder' => 'Navn', # Placeholder in html input-element for the name of the user
    );
	
	# Combine default values and provided settings
    $values = shortcode_atts($default, $atts);

	# Get the current user
	$user = wp_get_current_user();

	# Set full name of the user
	$name = ( $user->first_name ? $user->first_name . ( $user->last_name ? ' ' . $user->last_name : '' ) : $user->display_name );

	# Return html form-object where the user can generate a label for their mailbox
	return '<form action="/export-mailbox-label.php" method="post" enctype="multipart/form-data"><input size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="' . $values['name-placeholder'] . '" value="' . $name . '" type="text" name="user_name"><br><br><button type="submit">Download</button></form>';
}
