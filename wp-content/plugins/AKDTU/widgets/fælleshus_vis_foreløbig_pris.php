<?php

/**
 * @file Widget to display the expected price for all rentals this month, which will be automatically sent to Cobblestone
 */

function fælleshus_vis_foreløbig_pris_widget() {
	$months = [
		// "last month",
		"this month",
		"next month",
		// "+2 months"
	];

	$date = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen', null, 'MMMM YYYY');

	echo join("<br>", array_map(function ($month) use($date) {
		$month_start = new DateTime("first day of " . $month, new DateTimeZone('Europe/Copenhagen'));
		$month_end = new DateTime("last day of " . $month, new DateTimeZone('Europe/Copenhagen'));

		$price_to_pay = get_price_to_pay($month_start, $month_end);
		$price_adjustments = get_price_adjustments($month_start, $month_end);
		$final_price = get_final_price($price_to_pay, $price_adjustments);

		$return_string = "";

		if (count(array_keys($final_price)) > 0) {
			$return_string .= '<b>' . $date->format($month_start) . ':</b>';
			$return_string .= '<table class="widefat">';
			$return_string .= '<colgroup>';
				$return_string .= '<col span="1" style="width: 20%" />';
				$return_string .= '<col span="1" style="width: 25%" />';
				$return_string .= '<col span="1" style="width: 25%" />';
				$return_string .= '<col span="1" style="width: 25%" />';
			$return_string .= '</colgroup>';
			$return_string .= '<thead>';
				$return_string .= '<tr>';
					$return_string .= '<th>Lejlighed</th>';
					$return_string .= '<th>Pris for leje</th>';
					$return_string .= '<th>Rettelser</th>';
					$return_string .= '<th>Endelig pris</th>';
				$return_string .= '</tr>';
			$return_string .= '</thead>';
			$return_string .= '<tbody>';
			
			$row = 0;
			foreach ($final_price as $username => $price) {
				$return_string .= '<tr' . ($row % 2 == 0 ? ' class="alternate"' : '') . '>';
				$row++;

					$return_string .= '<td>' . padded_apartment_number_from_username($username) . (is_archive_user_from_username($username) ? ' (TB)' : '') . '</td>';
					$return_string .= '<td>' . number_format($price_to_pay[$username], 2, ",", ".") . ' kr.</td>';
					$return_string .= '<td>' . number_format($price_adjustments[apartment_number_from_username($username)] ?? 0, 2, ",", ".") . ' kr.</td>';
					$return_string .= '<td>' . number_format($price, 2, ",", ".") . ' kr.</td>';
				$return_string .= '</tr>';
			}
			$return_string .= '</tbody>';
		$return_string .= '</table>';
		}
		else {
			$return_string = "Der er indtil videre ingen udlejninger for " . $date->format($month_start) . " måned.";
		}

		return $return_string;
	}, $months));

} ?>