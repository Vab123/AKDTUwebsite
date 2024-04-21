<?php

/**
 * @file Widget to add new board members to the system
 */

function add_boardmember_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="add_boardmember" />
		<table width="100%">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr>
					<td><label>Lejlighed:</label></td>
					<td><?php echo apartments_dropdown(); ?></td>
				</tr>
				<tr>
					<td><label>Formand:</label></td>
					<td><input type="checkbox" name="chairman" /></td>
				</tr>
				<tr>
					<td><label>Næstformand:</label></td>
					<td><input type="checkbox" name="deputy-chairman" /></td>
				</tr>
				<tr>
					<td><label>Suppleant:</label></td>
					<td><input type="checkbox" name="deputy" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" class="button-secondary" value="Tilføj" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php } ?>