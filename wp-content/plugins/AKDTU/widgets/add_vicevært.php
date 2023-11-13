<?php

/**
 * @file Widget to add new board members to the system
 */

function add_vicevært_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="add_vicevært" />
		<table width="100%">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr>
					<td><label>Fornavn:</label></td>
					<td><input type="text" name="first_name" /></td>
				</tr>
				<tr>
					<td><label>Efternavn:</label></td>
					<td><input type="text" name="last_name" /></td>
				</tr>
				<tr>
					<td><label>Brugernavn:</label></td>
					<td><input type="text" name="username" /></td>
				</tr>
				<tr>
					<td><label>Email:</label></td>
					<td><input type="email" name="email" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" class="button-secondary" value="Tilføj" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php } ?>