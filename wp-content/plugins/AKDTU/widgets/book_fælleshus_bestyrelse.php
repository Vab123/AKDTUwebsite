<?php

/**
 * @file Widget to book the common house for the Board
 */

function book_fælleshus_bestyrelse_dashboard_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="book_fælleshus" />
		<input type="hidden" name="type" value="bestyrelse" />
		<table>
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr>
					<td><label>Dansk titel</label></td>
					<td><input type="text" name="name_da" placeholder=""></td>
				</tr>
				<tr>
					<td><label>Engelsk titel</label></td>
					<td><input type="text" name="name_en" placeholder=""></td>
				</tr>
				<tr>
					<td><label>Starttidspunkt</label></td>
					<td><input type="datetime-local" name="start_date" /></td>
				</tr>
				<tr>
					<td><label>Sluttidspunkt</label></td>
					<td><input type="datetime-local" name="end_date" /></td>
				</tr>
				<tr>
					<td><label>Hele dagen</label></td>
					<td><input type="checkbox" name="all_day" /></td>
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