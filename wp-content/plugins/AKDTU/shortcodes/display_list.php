<?php

## Add shortcodes
add_shortcode("AKDTU-display-list", "AKDTU_display_list");

function AKDTU_display_list($atts) {

	$default = array(
		'skip' => 0, # How many of first found elements to skip
		'limit' => 100, # How many elements to maximum write
		'date-format' => 'd. MMMM YYYY', # Format for output for date of document
		'file-dir-root' => WORKING_DIR, # Root file directory
		'file-dir' => '', # File directory starting from 'file-dir-root'
		'filename-match' => '', # Filename to match after 'YYYY-MM-DD '
		'filename-match-date' => '([\d]{4})-([\d]{2})-([\d]{2})', # Date format used in file name
		'filename-match-date-year' => 1, # If 'filename-match-date' contains year, which match group is it
		'filename-match-date-month' => 2, # If 'filename-match-date' contains month, which match group is it
		'filename-match-date-day' => 3, # If 'filename-match-date' contains date, which match group is it
		'filename-match-type' => false, # If 'filename-match' contains type, which match group is it
		'filename-match-type-dict' => false,
		'entry-text' => '%s', # What to write in element text
		'before-link-text' => '', # Text written before element link
		'after-link-text' => '', # Text written after element link
		'download-name'  => '', # Name of downloaded file: %1$s is year, %2$s is month, %3$s is day, %4$s is type
	);
	$values = shortcode_atts($default, $atts);

	$formatter = new IntlDateFormatter(
		pll_current_language('locale'),
		IntlDateFormatter::NONE,
		IntlDateFormatter::NONE,
		'Europe/Copenhagen',
		IntlDateFormatter::GREGORIAN,
		$values['date-format']
	);

	$directory = $values['file-dir-root'] . $values['file-dir'];

	if (is_dir($directory)) {
		$files = scandir($directory, 1);
		$found = -$values['skip'];

		$return_val = '<ul>';

		foreach ($files as $file) {

			$matches = array();

			if (preg_match('/^' . $values['filename-match-date'] . $values['filename-match'] . '/si', $file, $matches) && !is_dir($directory . $file) && $found < $values['limit']) {
				if ($found >= 0) {
					$date = new DateTime(($values['filename-match-date-year'] > 0 ? $matches[$values['filename-match-date-year']] : '2022') . "-" . ($values['filename-match-date-month'] > 0 ? $matches[$values['filename-match-date-month']] : '02') . "-" . ($values['filename-match-date-day'] > 0 ? $matches[$values['filename-match-date-day']] : '01'));
					$type = ($values['filename-match-type'] > 0 && $values['filename-match-type-dict'] != false ? $values['filename-match-type-dict'][$matches[$values['filename-match-type']]][pll_current_language('locale')] : '');

					$return_val .= '<li>' . sprintf($values['before-link-text'], $formatter->format($date), $type) . ($values['before-link-text'] != '' ? ' ' : '') . '<a href="' . $values['file-dir'] . $file . '" download' . ($values['download-name'] == '' ? '' : '="' . sprintf($values['download-name'], ($values['filename-match-date-year'] > 0 ? $matches[$values['filename-match-date-year']] : '2022'), ($values['filename-match-date-month'] > 0 ? $matches[$values['filename-match-date-month']] : '02'), ($values['filename-match-date-day'] > 0 ? $matches[$values['filename-match-date-day']] : '01'), $type) . '"') .  '>' . sprintf($values['entry-text'], $formatter->format($date), $type) . '</a>' . ($values['after-link-text'] != '' ? ' ' : '') . sprintf($values['after-link-text'], $formatter->format($date), $type) . '</li>';
				}

				$found++;
			}
		}

		$return_val .=  '</ul>';

		return $return_val;
	}
	return '';
}
