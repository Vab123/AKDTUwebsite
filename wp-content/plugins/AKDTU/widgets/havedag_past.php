<?php

/**
 * @file Widget to display the previous set of garden days, if these exist
 */

function havedag_past_dashboard_widget() {
	$gardendays = previous_gardenday('da', 2);

	if (!is_null($gardendays)) {
		echo join("<br>", array_map(function ($gardenday) {
			$returnstring = '<b>' . $gardenday->name . ':</b>';

			$returnstring .= '<table class="widefat">';
				$returnstring .= '<colgroup>';
					$returnstring .= '<col span="1" style="width: 45%" />';
					$returnstring .= '<col span="1" style="width: 20%" />';
					$returnstring .= '<col span="1" style="width: 35%" />';
				$returnstring .= '</colgroup>';
				$returnstring .= '<thead>';
					$returnstring .= '<tr>';
						$returnstring .= '<th>Dato</th>';
						$returnstring .= '<th>Tilmeldte</th>';
						$returnstring .= '<th></th>';
					$returnstring .= '</tr>';
				$returnstring .= '</thead>';
				$returnstring .= '<tbody>';
				
					$row = 0;
					$total_booked = 0;
					$total_spaces = 0;

					$havedag_formatter = new IntlDateFormatter("da_DK", IntlDateFormatter::LONG, IntlDateFormatter::NONE);

					foreach ($gardenday->get_tickets() as $ticket) {
						$returnstring .= '<tr' . ($row % 2 == 0 ? ' class="alternate"' : '') . '>';
						$row++;
							$returnstring .= '<td>' . ((bool)strtotime($ticket->ticket_name) ? $havedag_formatter->format(new DateTime($ticket->ticket_name)) : $ticket->ticket_name) . '</td>';
							$returnstring .= '<td>' . $ticket->get_booked_spaces() . "/" . $ticket->get_spaces();
								$total_booked += $ticket->get_booked_spaces();
								$total_spaces += $ticket->get_spaces();
							$returnstring .= '</td>';
							$returnstring .= '<td>' . ($ticket->get_booked_spaces() > 0 ? '<b><a href="' . get_site_url(null, "wp-admin/edit.php?post_type=event&page=events-manager-print-bookings&event_id=" . $gardenday->event_id . "&event_ticket_id=" . $ticket->ticket_id) . '">Tilmeldingsliste</a></b>' : '') . '</td>';
						$returnstring .= '</tr>';
					}
					$returnstring .= '<tr' . ($row % 2 == 0 ? ' class="alternate"' : '') . '>';
						$returnstring .= '<td><b>Total:</b></td>';
						$returnstring .= '<td><b>' . $total_booked . "/" . $total_spaces . '</b></td>';
						$returnstring .= '<td><b>' . ($total_booked > 0 ? '<a href="' . get_site_url(null, "wp-admin/edit.php?post_type=event&page=events-manager-print-bookings&event_id=" . $gardenday->event_id . "&event_ticket_id=total") . '">Samlet liste</a>' : '') . '</b></td>';
					$returnstring .= '</tr>';
				$returnstring .= '</tbody>';
			$returnstring .= '</table>';

			return $returnstring;
		}, $gardendays));
	} else {
		echo 'Ingen havedage er planlagt endnu.';
	}
}

?>