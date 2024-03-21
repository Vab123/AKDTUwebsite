<?php

/**
 * @file Widget to display the previous reservations of the common house
 */

function fælleshus_tidligere_reservationer_dashboard_widget() {

	$scope = 'past';
	$search_limit = 20;
	$offset = 0;
	$order = 'DESC';
	$owner = false;

	$events = array_filter(EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => false, 'owner' => $owner, 'pagination' => 0, 'event_status' => 1)), function ($event) {
		return count(pll_get_post_translations($event->post_id)) == 1 || pll_get_post_language($event->post_id) == 'da';
	});

	$actual_limit = 10;

	if (count($events) > 0) :
		$events = array_slice($events, 0, $actual_limit); ?>
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 20%">
				<col span="1" style="width: 60%">
				<col span="1" style="width: 20%">
			</colgroup>
			<thead>
				<tr>
					<th>Lejer</th>
					<th>Tidspunkt</th>
					<th>Pris</th>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;
				foreach ($events as $event) : ?>
					<tr style="height:46px" <?php if ($row % 2 == 0) {
												echo 'class="alternate"';
											};
											$row++; ?>>
						<td style="vertical-align:middle"><?php if (is_apartment_from_id($event->event_owner)) {
																echo "Lejl. " . padded_apartment_number_from_id($event->event_owner) . (is_archive_user_from_id($event->event_owner) ? ' (TB)' : '');
															} elseif (is_vicevært_from_id($event->event_owner)) {
																echo "Vicevært";
															} else {
																echo "Bestyrelsen";
															}; ?></td>
						<td style="vertical-align:middle"><?php
															$start_date = new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('UTC'));
															$end_date = new DateTime($event->event_end_date . " " . $event->event_end_time, new DateTimeZone('UTC'));

															echo $start_date->format("d-m-y H:i") . " - " . $end_date->format("d-m-y H:i"); ?></td>
						<td style="vertical-align:middle"><?php echo number_format(calc_rental_cost($start_date,$end_date,$event->owner), 2, ",", "."); ?>,00 kr.</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		Ingen udlejninger er planlagt endnu.
<?php endif;
}

?>