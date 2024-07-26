<?php

/**
 * @file Widget to book the common house for a resident
 */

function book_fælleshus_beboer_dashboard_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="book_fælleshus" />
		<input type="hidden" name="type" value="beboer" />
		<table>
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr>
					<td><label>Starttidspunkt</label></td>
					<td><input type="datetime-local" name="start_date" /></td>
				</tr>
				<tr>
					<td><label>Sluttidspunkt</label></td>
					<td><input type="datetime-local" name="end_date" /></td>
				</tr>
				<tr>
					<td><label>Lejlighed</label></td>
					<td><?php echo users_dropdown(); ?></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" class="button-secondary" value="Reservér" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
}

?>