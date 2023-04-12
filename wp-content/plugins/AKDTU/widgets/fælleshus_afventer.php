<?php

function fælleshus_afventer_dashboard_widget() {
	$scope = 'all';
	$search_limit = 20;
	$offset = 0;
	$order = 'ASC';
	$owner = !current_user_can('manage_others_bookings') ? get_current_user_id() : false;

	$events = EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'status' => 0));

	$actual_limit = 10;

	if (count($events) > 0) :
		$events = array_slice($events, 0, $actual_limit); ?>
		<h3><?php echo count($events); ?> reservation<?php if (count($events) > 1) {
															echo 'er';
														} ?> mangler godkendelse</h3>
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 20%" />
				<col span="1" style="width: 40%" />
				<col span="1" style="width: 40%" />
			</colgroup>
			<thead>
				<tr>
					<th>Lejer</th>
					<th>Tidspunkt</th>
					<th>Handlinger</th>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;
				foreach ($events as $event) : ?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						};
						$row++; ?>>
						<td style="vertical-align:middle"><?php $event_owner = get_user_by('id', $event->event_owner)->user_login;
															if (substr($event_owner, 0, 4) == "lejl") {
																echo "Lejl. " . ltrim(substr($event_owner, 4, 3), "0");
															} else {
																echo "Bestyrelsen";
															}; ?></td>
						<td style="vertical-align:middle"><?php
															$start_date = new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('UTC'));
															$start_date = $start_date->format("d-m-y");
															$end_date = new DateTime($event->event_end_date . " " . $event->event_end_time, new DateTimeZone('UTC'));
															$end_date = $end_date->format("d-m-y");
															echo $start_date . " - " . $end_date; ?></td>
						<td style="vertical-align:middle">
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