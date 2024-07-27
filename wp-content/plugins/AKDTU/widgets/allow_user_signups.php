<?php

/**
 * @file Widget to allow a new resident to sign up to the website
 */

function allow_user_signup_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="allow_user_signup" />
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead>
				<tr>
					<th colspan="2" style="font-weight: bold;">Bemærk: Denne widget er kun til at tillade brugeroprettelser som ikke er i forbindelse med en overdragelse. Widgetten "Opret overdragelse" skal benyttes ved overdragelser.</th>
				</tr>
			</thead>
			<tbody>
				<tr class="alternate">
					<td style="vertical-align:middle"><label>Lejlighed</label></td>
					<td style="vertical-align:middle"><?php echo apartments_dropdown(true); ?></td>
				</tr>
				<tr>
					<td style="vertical-align:middle"><label>Email fra overdragelsesaftale</label></td>
					<td style="vertical-align:middle"><input type="text" name="email" /></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle"><label>Tidspunkt for oprettelse</label></td>
					<td style="vertical-align:middle"><input type="datetime-local" name="takeover_time" /></td>
				</tr>
				<tr>
					<td style="vertical-align:middle"></td>
					<td style="vertical-align:middle"><input type="submit" class="button-secondary" value="Gem" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
}

?>