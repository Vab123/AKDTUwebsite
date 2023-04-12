<?php

require_once WP_PLUGIN_DIR . '/AKDTU/functions/fælleshus.php';

function fælleshus_vis_foreløbig_pris_widget(){ 
	$month_start = new DateTime("first day of this month", new DateTimeZone('Europe/Copenhagen'));
	$month_end = new DateTime("last day of this month", new DateTimeZone('Europe/Copenhagen'));

	$month = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
	$month->setPattern('MMMM');

	$price_to_pay = get_price_to_pay($month_start, $month_end);
	$price_adjustments = get_price_adjustments($month_start, $month_end);
	$final_price = get_final_price($price_to_pay, $price_adjustments);

	if (count(array_keys($final_price)) > 0):
	?>
	<h3>Foreløbige opkrævninger for <?php echo $month->format($month_start); ?> måned:</h3>
	<table class="widefat">
		<colgroup>
			<col span="1" style="width: 20%" />
			<col span="1" style="width: 25%" />
			<col span="1" style="width: 25%" />
			<col span="1" style="width: 25%" />
		</colgroup>
		<thead>
			<tr>
				<th>Lejlighed</th>
				<th>Pris for leje</th>
				<th>Rettelser</th>
				<th>Endelig pris</th>
			</tr>
		</thead>
		<tbody>
		<?php $row = 0; foreach ($final_price as $apartment => $price): ?>
			<tr <?php if ($row % 2 == 0){echo 'class="alternate"';}; $row++; ?>>
				<td><?php echo $apartment; ?></td>
				<td><?php echo $price_to_pay[$apartment]; ?> kr.</td>
				<td><?php echo (isset($price_adjustments[$apartment]) ? $price_adjustments[$apartment] : 0); ?> kr.</td>
				<td><?php echo $price; ?> kr.</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	Der er indtil videre ingen udlejninger for <?php echo $month->format($month_start); ?> måned.
<?php endif; } ?>