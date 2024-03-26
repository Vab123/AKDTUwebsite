<?php

# Add custom shortcode
add_shortcode("AKDTU-display-list", "AKDTU_display_list");

/**
 * Outputs a formatted list of documents
 * 
 * @param array $atts Array of settings to be displayed
 * 
 * Default values:
 * 	 'skip' => 0: How many of first found elements to skip
 *   'limit' => 100: How many elements to maximum write
 *   'date-format' => 'd. MMMM YYYY': Format for output for date of document
 *   'file-dir-root' => WORKING_DIR: Root file directory
 *   'file-dir' => '': File directory starting from 'file-dir-root'
 *   'filename-match' => '': Filename to match after 'YYYY-MM-DD '
 *   'filename-match-date' => '([\d]{4})-([\d]{2})-([\d]{2})': Date format used in file name
 *   'filename-match-date-year' => 1: If 'filename-match-date' contains year, which match group is it
 *   'filename-match-date-month' => 2: If 'filename-match-date' contains month, which match group is it
 *   'filename-match-date-day' => 3: If 'filename-match-date' contains date, which match group is it
 *   'filename-match-type' => false: If 'filename-match' contains type, which match group is it
 *   'filename-match-type-dict' => false,
 *   'entry-text' => '%s': What to write in element text
 *   'before-link-text' => '': Text written before element link
 *   'after-link-text' => '': Text written after element link
 *   'download-name'  => '': Name of downloaded file: %1$s is year, %2$s is month, %3$s is day, %4$s is type
 *   'list-style-type' => 'disclosure-closed': # Type of the items in the list
 * 
 * @return string Formatted list of all found documents
 */
function AKDTU_display_list($atts) {
	# Default values
	$default = array(
		'skip'                      => 0, 								# How many of first found elements to skip
		'limit'                     => 100, 							# How many elements to maximum write
		'date-format'               => 'd. MMMM YYYY', 					# Format for output for date of document
		'file-dir-root'             => WORKING_DIR, 					# Root file directory
		'file-dir'                  => '', 								# File directory starting from 'file-dir-root'
		'filename-match'            => '', 								# Filename to match after 'YYYY-MM-DD '
		'filename-match-date'       => '([\d]{4})-([\d]{2})-([\d]{2})',	# Date format used in file name
		'filename-match-date-year'  => 1, 								# If 'filename-match-date' contains year, which match group is it
		'filename-match-date-month' => 2, 								# If 'filename-match-date' contains month, which match group is it
		'filename-match-date-day'   => 3, 								# If 'filename-match-date' contains date, which match group is it
		'filename-match-type'       => false, 							# If 'filename-match' contains type, which match group is it
		'filename-match-type-dict'  => false,
		'entry-text'                => '%s', 							# What to write in element text
		'before-link-text'          => '', 								# Text written before element link
		'after-link-text'           => '', 								# Text written after element link
		'download-name'             => '', 								# Name of downloaded file: %1$s is year, %2$s is month, %3$s is day, %4$s is type
		'list-style-type'           => 'disclosure-closed',				# Type of the items in the list
	);

	# Combine defaults and provided values
	$values = shortcode_atts($default, $atts);

	# Date formatter
	$formatter = new IntlDateFormatter(
		pll_current_language('locale'),
		IntlDateFormatter::NONE,
		IntlDateFormatter::NONE,
		'Europe/Copenhagen',
		IntlDateFormatter::GREGORIAN,
		$values['date-format']
	);

	# Check if it is a valid directory
	if (is_dir($values['file-dir-root'] . $values['file-dir'])) {
		# Look for all files in the directory
		$files = scandir($values['file-dir-root'] . $values['file-dir'], 1);

		# Start list of elements
		$return_val = '<ul style="list-style-type: ' . $values['list-style-type'] . ';">';

		$return_val .= join('', array_map(function ($file) use($values, $formatter) {
			# Prepare list of matching files
			$matches = array();

			# Check if file should be output
			if (preg_match('/^' . $values['filename-match-date'] . $values['filename-match'] . '/si', $file, $matches) && !is_dir($values['file-dir-root'] . $values['file-dir'] . $file)) {
				# Get file date
				$date = new DateTime(($values['filename-match-date-year'] > 0 ? $matches[$values['filename-match-date-year']] : '2022') . "-" . ($values['filename-match-date-month'] > 0 ? $matches[$values['filename-match-date-month']] : '02') . "-" . ($values['filename-match-date-day'] > 0 ? $matches[$values['filename-match-date-day']] : '01'));

				# Get file type
				$type = ($values['filename-match-type'] > 0 && $values['filename-match-type-dict'] != false ? $values['filename-match-type-dict'][$matches[$values['filename-match-type']]][pll_current_language('locale')] : '');

				# Write info about the element to output value
				return '<li>' . sprintf($values['before-link-text'], $formatter->format($date), $type) . ($values['before-link-text'] != '' ? ' ' : '') . '<a href="/' . $values['file-dir'] . $file . '" download' . ($values['download-name'] == '' ? '' : '="' . sprintf($values['download-name'], ($values['filename-match-date-year'] > 0 ? $matches[$values['filename-match-date-year']] : '2022'), ($values['filename-match-date-month'] > 0 ? $matches[$values['filename-match-date-month']] : '02'), ($values['filename-match-date-day'] > 0 ? $matches[$values['filename-match-date-day']] : '01'), $type) . '"') .  '>' . sprintf($values['entry-text'], $formatter->format($date), $type) . '</a>' . ($values['after-link-text'] != '' ? ' ' : '') . sprintf($values['after-link-text'], $formatter->format($date), $type) . '</li>';
			}
		}, array_slice($files, $values['skip'], $values['limit'])));

		# Finalize return value
		$return_val .=  '</ul>';

		# Return formatted list
		return $return_val;
	}

	# Directory did not exist. Return nothing
	return '';
}
