<?php

require_once WP_PLUGIN_DIR . '/AKDTU/functions/display_apartments.php';

function fælleshus_juster_pris_widget(){ ?>
	<form action="" method="post">
		<input type="hidden" name="action" value="fælleshus_juster_pris" />
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
					<td><label>Prisjustering</label></td>
					<td><input type="number" name="price_change" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" class="button-secondary" value="Juster" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php } ?>