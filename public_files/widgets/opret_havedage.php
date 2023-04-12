<?php

function opret_havedag_dashboard_widget(){?>
	<form action="" method="post">
		<input type="hidden" name="action" value="add_havedag" />
		<table>
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr>
					<td><label>Dansk navn</label></td>
					<td><input type="text" name="danish_name" placeholder="Havedag - Efterår 2022" /></td>
				</tr>
				<tr>
					<td><label>Dansk tekst</label></td>
					<td><textarea cols="30" rows="4" name="danish_post_content">Tilmeld dig kun havedag én dag. Man er velkommen til at deltage flere dage end den man har tilmeldt sig, men i så fald bedes man sende en besked til bestyrelsen på <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.
Du kan ændre hvilken dato du har tilmeldt dig senere, indtil midnat d. 18/09/2022 eller dagene er fyldt. Det gør du ved først at afmelde dig din nuværende dag <a href="/for-beboere/havedage/mine-tilmeldinger/">her</a>, og derefter tilmelde dig en anden dag i stedet.</textarea></td>
				</tr>
				<tr>
					<td><label>Engelsk navn</label></td>
					<td><input type="text" name="english_name" placeholder="Gardenday - Fall 2022" /></td>
				</tr>
				<tr>
					<td><label>Engelsk tekst</label></td>
					<td><textarea cols="30" rows="4" name="english_post_content">Only sign up for one garden day. You are welcome to join other days as well, but in that case please send a message to the board at <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.
You can change which date you want to attend later, until 18/09/2022 at 12 am. or the dates are full. This is done by cancelling your current selection <a href="/en/for-residents/garden-days/my-signups/">here</a>, and afterwards selecting a different day instead.</textarea></td>
				</tr>
				<tr>
					<td><label>Havedage, åååå-mm-dd, adskilt af komma</label></td>
					<td><textarea cols="30" rows="2" name="gardenday_dates"></textarea></td>
				</tr>
				<tr>
					<td><label>Seneste tidspunkt for tilmelding</label></td>
					<td><input type="datetime-local" name="latest_signup" /></td>
				</tr>
				<tr>
					<td><label>Antal pladser pr. havedag</label></td>
					<td><input type="number" name="spaces" /></td>
				</tr>
				<tr>
					<td><label>Antal pladser pr. lejlighed</label></td>
					<td><input type="number" name="max_spaces" /></td>
				</tr>
				<tr>
					<td><label>Tidspunkt for offentliggørelse</label></td>
					<td><input type="datetime-local" name="publish_date" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" class="button-secondary" value="Opret havedage" /></td>
				</tr>
			</tbody>
		</table>
	</form>
	<?php
}

?>