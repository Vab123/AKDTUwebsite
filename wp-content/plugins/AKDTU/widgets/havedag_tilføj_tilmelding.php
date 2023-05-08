<?php

require_once WP_PLUGIN_DIR . '/AKDTU/functions/display_apartments.php';

function tilføj_til_havedag_dashboard_widget() {
	$scope = 'future';
	$search_limit = 4;
	$offset = 0;
	$order = 'ASC';
	$owner = false;

	$events = EM_Events::get(array('scope' => $scope, 'limit' => $search_limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 0));

	if (count($events) > 0) : ?>
		<table id="tilføj_til_havedag_widget_table" width="100%">
			<colgroup>
				<col span="1" style="width: 30%" />
				<col span="1" style="width: 70%" />
			</colgroup>
			<thead>
				<tr>
					<td><label>Begivenhed:</label></td>
					<td><select name="havedag_event" onchange="update_havedag_display(this.value)">
							<?php
							foreach ($events as $event) {
								echo '<option value="' . $event->event_id . '">' . $event->event_name . '</option>';
							};
							?>
						</select></td>
				</tr>
			</thead>
			<tbody>
				<?php $is_not_first = false;
				foreach ($events as $event) : ?>
					<form action="" method="post">
						<input type="hidden" name="havedag_event_id" value="<?php echo $event->event_id; ?>" />
						<input type="hidden" name="action" value="tilmeld_havedag" />
						<tr class="tilføj_havedag_options" id="tilføj_havedag_<?php echo $event->event_id ?>_date" <?php if ($is_not_first) {
																														echo 'style="visibility:collapse"';
																													}; ?>>
							<td><label>Dato:</label></td>
							<td><select name="havedag_dato">
									<?php foreach ($event->get_bookings()->get_tickets() as $ticket) : ?>
										<option value="<?php echo $ticket->ticket_id; ?>"><?php echo $ticket->ticket_name; ?></option>
									<?php endforeach; ?>
								</select></td>
						</tr>
						<tr class="tilføj_havedag_options" id="tilføj_havedag_<?php echo $event->event_id ?>_user" <?php if ($is_not_first) {
																														echo 'style="visibility:collapse"';
																													}; ?>>
							<td><label>Lejlighed:</label></td>
							<td><?php apartments_dropdown(); ?></td>
						</tr>
						<tr class="tilføj_havedag_options" id="tilføj_havedag_<?php echo $event->event_id ?>_signup" <?php if ($is_not_first) {
																															echo 'style="visibility:collapse"';
																														}; ?>>
							<td></td>
							<td><input type="submit" class="button-secondary" value="Tilmeld"></td>
						</tr>
					</form>
				<?php $is_not_first = true;
				endforeach; ?>
			</tbody>
		</table>
		<script>
			function update_havedag_display(id) {
				let options = document.getElementsByClassName("tilføj_havedag_options");
				for (let option of options) {
					option.style.visibility = "collapse";
				}
				document.getElementById("tilføj_havedag_" + id + "_date").style.visibility = "initial";
				document.getElementById("tilføj_havedag_" + id + "_user").style.visibility = "initial";
				document.getElementById("tilføj_havedag_" + id + "_signup").style.visibility = "initial";
			}
		</script>
	<?php else : ?>
		Ingen havedage er planlagt endnu.
<?php endif;
}

?>