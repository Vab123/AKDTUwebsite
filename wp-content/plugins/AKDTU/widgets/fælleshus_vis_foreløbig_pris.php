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
			
		$price_to_pay_by_apartment = price_to_pay_per_apartment($month_start, $month_end);

		$return_string = "";

		if (count(array_filter($price_to_pay_by_apartment, function($price) {return $price["current_owner"]["total"] != 0 || $price["previous_owner"]["total"] != 0;})) > 0) {
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

			foreach ($price_to_pay_by_apartment as $apartment => $prices) {
				if ($prices["current_owner"]["total"] != 0) {
					$return_string .= '<tr' . ($row % 2 == 0 ? ' class="alternate"' : '') . '>';
					$row++;

					$return_string .= '<td>' . padded_apartment_number_from_apartment_number($apartment) . '</td>';
					$return_string .= '<td>' . number_format($prices["current_owner"]["original_price"], 2, ",", ".") . ' kr.</td>';
					$return_string .= '<td>' . number_format($prices["current_owner"]["adjustments"] ?? 0, 2, ",", ".") . ' kr.</td>';
					$return_string .= '<td>' . number_format($prices["current_owner"]["total"], 2, ",", ".") . ' kr.</td>';
					$return_string .= '</tr>';
				}
				if ($prices["previous_owner"]["total"] != 0) {
					$return_string .= '<tr' . ($row % 2 == 0 ? ' class="alternate"' : '') . '>';
					$row++;

					$return_string .= '<td>' . padded_apartment_number_from_apartment_number($apartment) . ' (TB)</td>';
					$return_string .= '<td>' . number_format($prices["previous_owner"]["original_price"], 2, ",", ".") . ' kr.</td>';
					$return_string .= '<td>' . number_format($prices["previous_owner"]["adjustments"] ?? 0, 2, ",", ".") . ' kr.</td>';
					$return_string .= '<td>' . number_format($prices["previous_owner"]["total"], 2, ",", ".") . ' kr.</td>';
					$return_string .= '</tr>';
				}
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