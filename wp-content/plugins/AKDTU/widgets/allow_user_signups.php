<?php

/**
 * @file Widget to allow a new resident to sign up to the website
 */

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
					<td><?php echo apartments_dropdown(true); ?></td>
				</tr>
				<tr>
					<td><label>Email fra overdragelsesaftale</label></td>
					<td><input type="text" name="email" /></td>
				</tr>
				<tr>
					<td><label>Tidspunkt for oprettelse</label></td>
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