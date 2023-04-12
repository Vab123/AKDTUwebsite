<?php

function havedag_past_dashboard_widget() {
	$scope = 'past';
	$search_limit = 20;
	$offset = 0;
	$order = 'DESC';
	$owner = !current_user_can('manage_others_bookings') ? get_current_user_id() : false;

	$events = array_filter(EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0)), function ($event) {
		return pll_get_post_language($event->post_id, "slug") == "da";
	});

	$actual_limit = 1;

	if (count($events) > 0) :
		$events = array_slice($events, 0, $actual_limit);

		foreach ($events as $event) : ?>
			<table class="widefat">
				<thead>
					<tr>
						<th>Dato</th>
						<th>Tilmeldte</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $row = 0;
					$total_booked = 0;
					$total_spaces = 0;
					foreach ($event->get_tickets() as $ticket) : ?>
						<tr <?php if ($row % 2 == 0) {
								echo 'class="alternate"';
							};
							$row++; ?>>
							<td><?php echo $ticket->ticket_name; ?></td>
							<td><?php echo $ticket->get_booked_spaces() . "/" . $ticket->get_spaces();
								$total_booked += $ticket->get_booked_spaces();
								$total_spaces += $ticket->get_spaces(); ?></td>
							<td><?php if ($ticket->get_booked_spaces() > 0) {
									echo '<b><a href="' . get_site_url(null, "wp-admin/edit.php?post_type=event&page=events-manager-print-bookings&event_id=" . $event->event_id . "&event_ticket_id=" . $ticket->ticket_id) . '">Tilmeldingsliste</a></b>';
								}; ?></td>
						</tr>
					<?php endforeach; ?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						} ?>>
						<td><b>Total:</b></td>
						<td><b><?php echo $total_booked . "/" . $total_spaces; ?></b></td>
						<td><b><?php if ($total_booked > 0) {
									echo '<a href="' . get_site_url(null, "wp-admin/edit.php?post_type=event&page=events-manager-print-bookings&event_id=" . $event->event_id . "&event_ticket_id=total") . '">Samlet liste</a>';
								} ?></b></td>
					</tr>
				</tbody>
			</table>
		<?php endforeach;
	else : ?>
		Ingen tidligere havedage blev fundet.
<?php endif;
}

?>