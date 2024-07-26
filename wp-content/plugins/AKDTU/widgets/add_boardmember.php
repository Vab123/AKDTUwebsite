<?php

/**
 * @file Widget to add new board members to the system
 */

function add_boardmember_widget() { 
	global $AKDTU_USER_TYPES;
	?>
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
					<td><?php echo users_dropdown(); ?></td>
				</tr>
				<tr>
					<td><label>Medlemstype:</label></td>
					<td><select name="user-type"><?php echo join("", array_map(function ($key, $user_type) {
						return ($key == 'none' ? '' : '<option value="' . $key . '">' . $user_type['name'] . '</option>');
					}, array_keys($AKDTU_USER_TYPES), array_values($AKDTU_USER_TYPES))); ?></select></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" class="button-secondary" value="Tilføj" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php } ?>