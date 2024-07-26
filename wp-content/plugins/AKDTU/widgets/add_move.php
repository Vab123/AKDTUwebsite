<?php

/**
 * @file Widget to allow a new resident to sign up to the website
 */

function add_move_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="add_move" />
		<table>
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr>
					<td><label>Lejlighed</label></td>
					<td><?php echo apartments_dropdown(true); ?></td>
				</tr>
				<tr>
					<td><label>Email på køber</label></td>
					<td><textarea type="text" name="email" placeholder="Emails fra overdragelsesaftale, komma-separeret" style="width: 100%" rows="3"></textarea></td>
				</tr>
				<tr>
					<td><label>Tidspunkt for overtagelse</label></td>
					<td><input type="datetime-local" name="takeover_time" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" class="button-secondary" value="Gem" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
}

?>