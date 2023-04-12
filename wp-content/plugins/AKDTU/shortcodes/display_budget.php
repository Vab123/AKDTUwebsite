<?php

if (!function_exists('AKDTU_display_list')){
	require_once 'shortcodes/AKDTU_display_list.php';
}

add_shortcode("AKDTU-display-budgetter", "AKDTU_display_budget");

function AKDTU_display_budget( $atts ){
	$default = array(
		'date-format' => 'YYYY', # Format for output for date of document
		'entry-text' => '%s', # What to write in element text
		'before-link-text' => '', # Text written before element link
		'after-link-text' => '', # Text written after element link
		'download-name'  => '', # Name of downloaded file: %1$s is year, %2$s is month, %3$s is day, %4$s is type
    );
    $values = shortcode_atts($default, $atts);

	$values['skip'] = 0; # How many of first found elements to skip
	$values['limit'] = 100; # How many elements to maximum write
	$values['file-dir-root'] = WORKING_DIR; # Root file directory
	$values['file-dir'] = BUDGET_FOLDER; # File directory starting from 'file-dir-root'
	$values['filename-match'] = ' Budget.pdf'; # Filename to match after 'file-dir'
	$values['filename-match-date'] = '([\d]{4})'; # Date format used in file name
	$values['filename-match-date-year'] = 1; # If 'filename-match-date' contains year, which match group is it
	$values['filename-match-date-month'] = false; # If 'filename-match-date' contains month, which match group is it
	$values['filename-match-date-day'] = false; # If 'filename-match-date' contains date, which match group is it
	$values['filename-match-type'] = false; # If 'filename-match' contains type, which match group is it

	return AKDTU_display_list( $values );
}
