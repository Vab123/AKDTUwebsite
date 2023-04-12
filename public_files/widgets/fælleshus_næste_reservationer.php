<?php

function fælleshus_næste_reservationer_dashboard_widget() {
	$scope = 'future';
	$search_limit = 20;
	$offset = 0;
	$order = 'ASC';
	$owner = !current_user_can('manage_others_bookings') ? get_current_user_id() : false;

	$events = array_filter(EM_Events::get( array('scope'=>$scope, 'limit'=>$search_limit, 'offset' => $offset, 'order'=>$order, 'orderby'=>'event_start', 'bookings'=>false, 'owner' => $owner, 'pagination' => 0, 'event_status' => 1 ) ),function($event){return count(pll_get_post_translations( $event->post_id )) == 1;});

	$actual_limit = 10;

	if (count($events) > 0):
		$events = array_slice($events,0,$actual_limit); ?>
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
				<th>Handlinger</th>
			</tr>
		</thead>
		<tbody>
		<?php $row = 0; foreach ($events as $event): ?>
			<tr <?php if ($row % 2 == 0){echo 'class="alternate"';}; $row++; ?>>
				<td style="vertical-align:middle"><?php $event_owner = get_user_by('id',$event->event_owner)->user_login; if (substr($event_owner,0,4) == "lejl"){echo "Lejl. " . ltrim(substr($event_owner,4,3),"0");}else{echo "Bestyrelsen";}; ?></td>
				<td style="vertical-align:middle"><?php 
				$start_date = new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('UTC'));
				$start_date = $start_date->format("d-m-y H:i");
				$end_date = new DateTime($event->event_end_date . " " . $event->event_end_time, new DateTimeZone('UTC'));
				$end_date = $end_date->format("d-m-y H:i");
				echo $start_date . " - " . $end_date; ?></td>
				<td style="vertical-align:middle">
					<form style="display: inline;" action="" method="post" style="text-align:center" style="text-align:center">
						<input type="hidden" name="action" value="delete_leje" />
						<input type="hidden" name="leje_event_id" value="<?php echo $event->event_id; ?>" />
						<input type="submit" class="button-secondary" value="Slet" />
					</form>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
		Ingen udlejninger er planlagt endnu.
	<?php endif;
}

?>