<?php

/**
 * @file Widget to allow a new resident to sign up to the website
 */

function add_move_widget() { ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="add_move" />
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead>
				<tr>
					<th colspan="2" style="font-weight: bold;">Bemærk: Denne widget opretter tilladelser til brugeroprettelser og koordinerer at de tidligere beboeres adgang ophører.</th>
				</tr>
			</thead>
			<tbody>
				<tr class="alternate">
					<td style="vertical-align:middle"><label>Lejlighed</label></td>
					<td style="vertical-align:middle"><?php echo apartments_dropdown(true); ?></td>
				</tr>
				<tr>
					<td style="vertical-align:middle"><label>Email på køber</label></td>
					<td style="vertical-align:middle"><textarea type="text" name="email" placeholder="Emails fra overdragelsesaftale, komma-separeret" style="width: 100%" rows="3"></textarea></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle"><label>Tidspunkt for overtagelse</label></td>
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