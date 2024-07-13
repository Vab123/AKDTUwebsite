<?php

/**
 * @file Widget to display future residents moving in and out
 */

function moves_future_dashboard_widget() {
	$limit = 10; # How many past moves to show

	$future_moves = get_future_moves(['*'], $limit, null, null);

	if (count($future_moves) > 0) : ?>
		<table id='dbem-bookings-table' class='widefat post ' style="max-width:75em">
			<colgroup>
				<col span="1" style="width: 15%" />
				<col span="1" style="width: 85%" />
			</colgroup>
			<thead>
				<tr>
					<th class='manage-column' scope='col'>Lejl.</th>
					<th class='manage-column' scope='col'>Overdragelsesdato</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$row = 0;
				$event_count = (!empty($event_count)) ? $event_count : 0;
				foreach ($future_moves as $user) {
				?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						};
						$row++; ?>>
						<td style="vertical-align:middle"><?php echo padded_apartment_number_from_apartment_number($user->apartment_number); ?></td>
						<td style="vertical-align:middle"><?php echo (new DateTime($user->allow_creation_date))->format('d-m-Y H:i'); ?></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	<?php else : ?>
		Ingen fremtidige udflytninger.
	<?php endif; ?>
<?php
}

?>