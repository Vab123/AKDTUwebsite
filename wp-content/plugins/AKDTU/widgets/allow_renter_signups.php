<?php

/**
 * @file Widget to allow a temporary renter to sign up to the website
 */

function allow_renter_signup_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="allow_renter_signup" />
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr class="alternate">
					<td style="vertical-align:middle"><label>Lejlighed</label></td>
					<td style="vertical-align:middle"><?php echo apartments_dropdown(true); ?></td>
				</tr>
				<tr>
					<td style="vertical-align:middle"><label>Email</label></td>
					<td style="vertical-align:middle"><input type="text" name="email" /></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle"><label>Starttidspunkt</label></td>
					<td style="vertical-align:middle"><input type="datetime-local" name="start_time" /></td>
				</tr>
				<tr>
					<td style="vertical-align:middle"><label>Sluttidspunkt</label></td>
					<td style="vertical-align:middle"><input type="datetime-local" name="end_time" /></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle"></td>
					<td style="vertical-align:middle"><input type="submit" class="button-secondary" value="Gem" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
}

?>