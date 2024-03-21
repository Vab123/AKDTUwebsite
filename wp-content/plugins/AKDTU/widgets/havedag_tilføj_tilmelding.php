<?php

/**
 * @file Widget to add a signup to a garden day for a resident
 */

function tilføj_til_havedag_dashboard_widget() {
	$gardendays = next_gardenday('all', 1);

	$havedag_formatter = new IntlDateFormatter('da_DK', IntlDateFormatter::LONG, IntlDateFormatter::NONE);

	if (!is_null($gardendays)) : ?>
		<table id="tilføj_til_havedag_widget_table" width="100%">
			<colgroup>
				<col span="1" style="width: 30%" />
				<col span="1" style="width: 70%" />
			</colgroup>
			<thead>
				<tr>
					<td><label>Begivenhed:</label></td>
					<td><select name="havedag_event" onchange="update_add_gardenday_display(this.value)">
							<?php
							foreach ($gardendays as $gardenday) {
								foreach ($gardenday as $language => $event) {
									echo '<option value="' . $event->event_id . '">' . $event->event_name . '</option>';
								}
							};
							?>
						</select></td>
				</tr>
			</thead>
			<tbody>
				<?php $is_not_first = false;
				foreach ($gardendays as $gardenday) :
					foreach ($gardenday as $language => $event) : ?>
						<form action="" method="post" id="add_gardenday_form_<?php echo $event->event_id; ?>">
							<input type="hidden" name="havedag_event_id" value="<?php echo $event->event_id; ?>" />
							<input type="hidden" name="action" value="tilmeld_havedag" />
							<tr class="add_gardenday_options" id="add_gardenday_<?php echo $event->event_id ?>_date" <?php if ($is_not_first) {
																															echo 'style="visibility:collapse"';
																														}; ?>>
								<td><label>Dato:</label></td>
								<td><select name="havedag_dato">
										<?php foreach ($event->get_bookings()->get_tickets() as $ticket) : ?>
											<option value="<?php echo $ticket->ticket_id; ?>"><?php echo ((bool)strtotime($ticket->ticket_name) ? $havedag_formatter->format(new DateTime($ticket->ticket_name)) : $ticket->ticket_name); ?></option>
										<?php endforeach; ?>
									</select></td>
							</tr>
							<tr class="add_gardenday_options" id="add_gardenday_<?php echo $event->event_id ?>_user" <?php if ($is_not_first) {
																															echo 'style="visibility:collapse"';
																														}; ?>>
								<td><label>Lejlighed:</label></td>
								<td><?php echo apartments_dropdown(); ?></td>
							</tr>
							<tr class="add_gardenday_options" id="add_gardenday_<?php echo $event->event_id ?>_signup" <?php if ($is_not_first) {
																																echo 'style="visibility:collapse"';
																															}; ?>>
								<td></td>
								<td><input type="submit" class="button-secondary" value="Tilmeld"></td>
							</tr>
						</form>
					<?php $is_not_first = true;
					endforeach;
				endforeach ?>
			</tbody>
		</table>
		<script>
			function update_add_gardenday_display(id) {
				let options = document.getElementsByClassName("add_gardenday_options");
				for (let option of options) {
					option.style.visibility = "collapse";
				}
				document.getElementById("add_gardenday_" + id + "_date").style.visibility = "initial";
				document.getElementById("add_gardenday_" + id + "_user").style.visibility = "initial";
				document.getElementById("add_gardenday_" + id + "_signup").style.visibility = "initial";
			}
		</script>
	<?php else : ?>
		Ingen havedage er planlagt endnu.
<?php endif;
}

?>