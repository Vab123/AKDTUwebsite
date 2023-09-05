<?php

add_shortcode("AKDTU-download-mailbox-label", "AKDTU_download_mailbox_label");

function AKDTU_download_mailbox_label( $atts ){
	$default = array(
		'name-placeholder' => 'Navn', # Format for output for date of document
    );
    $values = shortcode_atts($default, $atts);

	$user = wp_get_current_user();

	if ( $user->first_name ) {
		if ( $user->last_name ) {
		  $name = $user->first_name . ' ' . $user->last_name;
		} else {
			$name = $user->first_name;
		}
	  } else {
		$name = $user->display_name;
	  }

	$html = '<form action="/export-mailbox-label.php" method="post" enctype="multipart/form-data"><input size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="' . $values['name-placeholder'] . '" value="' . $name . '" type="text" name="user_name"><br><br><button type="submit">Download</button></form>';

	return $html;
}
