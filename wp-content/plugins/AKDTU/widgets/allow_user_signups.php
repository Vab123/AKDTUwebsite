<?php

require_once WP_PLUGIN_DIR . '/AKDTU/functions/display_apartments.php';

function allow_user_signup_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="allow_user_signup" />
		<table>
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr>
					<td><label>Lejlighed</label></td>
					<td><?php apartments_dropdown(); ?></td>
				</tr>
				<tr>
					<td><label>Telefonnummer fra overdragelsesaftale</label></td>
					<td><input type="text" name="phone" /></td>
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