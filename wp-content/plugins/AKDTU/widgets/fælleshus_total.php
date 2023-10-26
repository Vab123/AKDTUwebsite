<?php

require_once WP_PLUGIN_DIR . '/AKDTU/functions/fælleshus.php';

function fælleshus_total_widget() {
	$start_year = 2022;
	$years_ahead_to_show = ((new DateTime)->format('m') > 6 ? 1 : 0);
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
			for ($year = (new DateTime)->format('Y') + $years_ahead_to_show; $year >= $start_year; $year--) :
				$year_start = new DateTime("01-01-" . $year . " 00:00:00", new DateTimeZone('Europe/Copenhagen'));
				$year_end = new DateTime("31-12-" . $year . " 23:59:59", new DateTimeZone('Europe/Copenhagen'));

				$price_to_pay = get_price_to_pay($year_start, $year_end);
				$price_adjustments = get_price_adjustments($year_start, $year_end);
				$final_price = get_final_price($price_to_pay, $price_adjustments);
			?>
				<tr <?php if ($row % 2 == 0) {
						echo 'class="alternate"';
					};
					$row++; ?>>
					<td><?php echo $year; ?></td>
					<td><?php echo (!is_null($price_to_pay) && !empty($price_to_pay) ? array_sum($price_to_pay) : 0); ?> kr.</td>
					<td><?php echo (!is_null($price_adjustments) && !empty($price_adjustments) ? array_sum($price_adjustments) : 0); ?> kr.</td>
					<td><?php echo (!is_null($final_price) && !empty($final_price) ? array_sum($price_to_pay) + array_sum($price_adjustments) : 0); ?> kr.</td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
<?php } ?>