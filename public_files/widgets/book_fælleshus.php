<?php


function book_fælleshus_dashboard_widget(){?>
	<form action="" method="post">
		<input type="hidden" name="action" value="book_fælleshus" />
		<table>
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr>
					<td><label>Titel</label></td>
					<td><input type="text" name="name" placeholder="" /></td>
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
					<td><label>Lejer</label></td>
					<td><?php wp_dropdown_users(array('selected' => get_current_user_id())); ?></td>
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