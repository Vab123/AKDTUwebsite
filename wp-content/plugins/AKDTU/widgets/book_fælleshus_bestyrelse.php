<?php

/**
 * @file Widget to book the common house for the Board
 */

function book_fælleshus_bestyrelse_dashboard_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="book_fælleshus" />
		<input type="hidden" name="type" value="bestyrelse" />
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr class="alternate">
					<td style="vertical-align:middle;"><label>Dansk titel</label></td>
					<td style="vertical-align:middle;"><input type="text" name="name_da" placeholder=""></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;"><label>Engelsk titel</label></td>
					<td style="vertical-align:middle;"><input type="text" name="name_en" placeholder=""></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle;"><label>Starttidspunkt</label></td>
					<td style="vertical-align:middle;"><input type="datetime-local" name="start_date" /></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;"><label>Sluttidspunkt</label></td>
					<td style="vertical-align:middle;"><input type="datetime-local" name="end_date" /></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle;"><label>Hele dagen</label></td>
					<td style="vertical-align:middle;"><input type="checkbox" name="all_day" /></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;"></td>
					<td style="vertical-align:middle;"><input type="submit" class="button-secondary" value="Reservér" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
}

?>