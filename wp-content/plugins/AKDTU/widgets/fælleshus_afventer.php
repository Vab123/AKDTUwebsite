<?php

/**
 * @file Widget to list all pending requests to book the common house
 */

function fælleshus_afventer_dashboard_widget() {

	$scope = 'all';
	$search_limit = 20;
	$offset = 0;
	$order = 'ASC';
	$owner = false;

	$events = EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'status' => 0, 'owner' => $owner));

	$actual_limit = 10;

	if (count($events) > 0) :
		$events = array_slice($events, 0, $actual_limit); ?>
		<h3><?php echo count($events); ?> reservation<?php if (count($events) > 1) {
															echo 'er';
														} ?> mangler godkendelse</h3>
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 20%" />
				<col span="1" style="width: 35%" />
				<col span="1" style="width: 45%" />
			</colgroup>
			<thead>
				<tr>
					<th>Lejer</th>
					<th>Tidspunkt</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;
				foreach ($events as $event) : ?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						};
						$row++; ?>>
						<td style="vertical-align:middle"><?php echo (is_apartment_from_id($event->event_owner) ? "Lejl. " . padded_apartment_number_from_id($event->event_owner) : "Bestyrelsen"); ?></td>
						<td style="vertical-align:middle"><?php
															$start_date = new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('Europe/Copenhagen'));
															$end_date = new DateTime($event->event_end_date . " " . $event->event_end_time, new DateTimeZone('Europe/Copenhagen'));
															echo "{$start_date->format("d-m-y H:i")} - {$end_date->format("d-m-y H:i")}"; ?></td>
						<td style="vertical-align:middle; text-align:right;">
							<form style="display: inline;" action="" method="post">
								<input type="hidden" name="action" value="approve_leje" />
								<input type="hidden" name="leje_event_id" value="<?php echo $event->event_id; ?>" />
								<input type="submit" class="button-secondary" value="Godkend" />
							</form>
							<form style="display: inline;" action="" method="post" style="text-align:center">
								<input type="hidden" name="action" value="delete_leje" />
								<input type="hidden" name="leje_event_id" value="<?php echo $event->event_id; ?>" />
								<input type="submit" class="button-secondary" value="Slet" />
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		Ingen ansøgninger om leje af fælleshus mangler godkendelse.
<?php endif;
}

?>