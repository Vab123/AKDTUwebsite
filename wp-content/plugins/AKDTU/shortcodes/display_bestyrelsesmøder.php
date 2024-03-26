<?php

# Add custom shortcode
add_shortcode("AKDTU-display-bestyrelsesmøder", "AKDTU_display_bestyrelsesmøde");

/**
 * Outputs a formatted list of minutes from all board meetings
 * 
 * @param array $atts Array of settings to be displayed
 * 
 * Default values:
 *   'date-format' => 'd. MMMM YYYY': Format for output for date of document
 *   'entry-text' => '%s': What to write in element text
 *   'before-link-text' => '': Text written before element link
 *   'after-link-text' => '': Text written after element link
 *   'download-name'  => '': Name of downloaded file: %1$s is year, %2$s is month, %3$s is day, %4$s is type
 *	 'list-style-type' => 'disclosure-closed': Type of the items in the list
 * 
 * @return string Formatted list of all found documents
 */
function AKDTU_display_bestyrelsesmøde( $atts ){
	# Default values
	$default = array(
		'date-format' 		=> 'd. MMMM YYYY', 		# Format for output for date of document
		'entry-text' 		=> '%s', 				# What to write in element text
		'before-link-text'	=> '', 					# Text written before element link
		'after-link-text'	=> '', 					# Text written after element link
		'download-name' 	=> '', 					# Name of downloaded file: %1$s is year, %2$s is month, %3$s is day, %4$s is type
		'list-style-type'	=> 'disclosure-closed',	# Type of the items in the list
    );

	# Combine default values and provided settings
    $values = shortcode_atts($default, $atts);

	# Other default settings, the user are not allowed to set
	$values['skip'] = 0; 												# How many of first found elements to skip
	$values['limit'] = 100; 											# How many elements to maximum write
	$values['file-dir-root'] = WORKING_DIR; 							# Root file directory
	$values['file-dir'] = BESTYRELSE_FOLDER; 							# File directory starting from 'file-dir-root'
	$values['filename-match'] = ' referat.pdf'; 						# Filename to match after 'file-dir'
	$values['filename-match-date'] = '([\d]{4})-([\d]{2})-([\d]{2})'; 	# Date format used in file name
	$values['filename-match-date-year'] = 1; 							# If 'filename-match-date' contains year, which match group is it
	$values['filename-match-date-month'] = 2; 							# If 'filename-match-date' contains month, which match group is it
	$values['filename-match-date-day'] = 3; 							# If 'filename-match-date' contains date, which match group is it
	$values['filename-match-type'] = false; 							# If 'filename-match' contains type, which match group is it

	# Return formatted list of documents
	return AKDTU_display_list( $values );
}
