<?php

/**
 * @file Widget to display the expected and realized revenue from renting the common house, by month
 */

function fælleshus_total_month_widget() {
	$show_months_ahead = 2; // How many months in the future from now to include in table
	$show_months_ago = 7; // How many months ago from now to include in table

	$current_month = (new DateTime)->format('m');
	$current_year = (new DateTime)->format('Y');
?>
	<table class="widefat">
		<colgroup>
			<col span="1" style="width: 20%" />
			<col span="1" style="width: 25%" />
			<col span="1" style="width: 25%" />
			<col span="1" style="width: 25%" />
		</colgroup>
		<thead>
			<tr>
				<th>År</th>
				<th>Pris for leje</th>
				<th>Rettelser</th>
				<th>Endelig pris</th>
			</tr>
		</thead>
		<tbody>
			<?php $row = 0;
			for ($month = $show_months_ahead; $month >= -$show_months_ago; $month--) :
				$actual_month = (12 * max(ceil($show_months_ahead / 12), ceil($show_months_ago / 12)) + $current_month + $month - 1) % 12 + 1;
				$actual_year = $current_year + floor(($current_month + $month - 1) / 12);

				$month_start = new DateTime("05-" . str_pad($actual_month, 2, "0", STR_PAD_LEFT) . "-" . $actual_year . " 12:00:00", new DateTimeZone('Europe/Copenhagen')); ## Date set to 05-xx-xxxx for a reason
				$month_end = new DateTime("05-" . str_pad($actual_month, 2, "0", STR_PAD_LEFT) . "-" . $actual_year . " 12:00:00", new DateTimeZone('Europe/Copenhagen'));
				$month_end->modify('last day of this month'); ## Date set to 05-xx-xxxx for a reason

				$price_to_pay = get_price_to_pay($month_start, $month_end);
				$price_adjustments = get_price_adjustments($month_start, $month_end);
				$final_price = get_final_price($price_to_pay, $price_adjustments);

				$monthyear_formatter = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
				$monthyear_formatter->setPattern('MMM YYYY');
			?>
				<tr <?php if ($row % 2 == 0) {
						echo 'class="alternate"';
					};
					$row++; ?>>
					<td><?php echo $monthyear_formatter->format($month_start); ?></td>
					<td><?php echo (!is_null($price_to_pay) && !empty($price_to_pay) ? array_sum($price_to_pay) : 0); ?> kr.</td>
					<td><?php echo (!is_null($price_adjustments) && !empty($price_adjustments) ? array_sum($price_adjustments) : 0); ?> kr.</td>
					<td><?php echo (!is_null($final_price) && !empty($final_price) ? array_sum($price_to_pay) + array_sum($price_adjustments) : 0); ?> kr.</td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
<?php };

?>