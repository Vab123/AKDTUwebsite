<?php

/**
 * @file Widget to create all events and signup structure for all garden days for a season
 */

function opret_havedag_dashboard_widget() {
	$start_showing_fall_from_month = 4;		# Month in which to start displaying fall garden day information (Including this month)
	$start_showing_fall_from_month_name_da = "april";
	$start_showing_fall_from_month_name_en = "April";

	$stop_showing_fall_after_month = 10;	# Month in which to stop displaying fall garden day information (After this month)
	$stop_showing_fall_after_month_name_da = "oktober";
	$stop_showing_fall_after_month_name_en = "October";
	?>
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
					<td><input type="text" name="danish_name" value="Havedag - <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? "Efterår " . date('Y') : "Forår " . (intval(date('n')) < $stop_showing_fall_after_month ? date('Y') : strval(intval(date('Y') + 1)))); ?>" style="width:100%;" /></td>
				</tr>
				<tr>
					<td><label>Dansk tekst</label></td>
					<td><textarea rows="35" name="danish_post_content" style="width:100%;">Tilmeld dig kun havedag én dag. Man er velkommen til at deltage flere dage end den man har tilmeldt sig, men i så fald bedes man sende en besked til bestyrelsen på <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.
Du kan ændre hvilken dato du har tilmeldt dig senere, indtil midnat d. <?php echo "01" . "/" . (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? str_pad(strval($stop_showing_fall_after_month), 2, "0", STR_PAD_LEFT) : str_pad(strval($start_showing_fall_from_month), 2, "0", STR_PAD_LEFT)) . "/" . (intval(date('n')) < $stop_showing_fall_after_month ? date('Y') : strval(intval(date('Y') + 1))); ?> eller dagene er fyldt. Det gør du ved først at afmelde dig din nuværende dag <a href="/for-beboere/havedage/mine-tilmeldinger/">her</a>, og derefter tilmelde dig en anden dag i stedet.

I <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? "efteråret" : "foråret"); ?> bliver havedagenes overordnede emner følgende:
- 1. <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? $stop_showing_fall_after_month_name_da : $start_showing_fall_from_month_name_da); ?>: EMNE
- 2. <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? $stop_showing_fall_after_month_name_da : $start_showing_fall_from_month_name_da); ?>: EMNE
- 3. <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? $stop_showing_fall_after_month_name_da : $start_showing_fall_from_month_name_da); ?>: EMNE

Hvis du har nogen spørgsmål eller kommentarer er du velkommen til at kontakte bestyrelsen på <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.</textarea></td>
				</tr>
				<tr>
					<td><label>Engelsk navn</label></td>
					<td><input type="text" name="english_name" value="Gardenday - <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? "Fall " . date('Y') : "Spring " . (intval(date('n')) < $stop_showing_fall_after_month ? date('Y') : strval(intval(date('Y') + 1)))); ?>" style="width:100%;" /></td>
				</tr>
				<tr>
					<td><label>Engelsk tekst</label></td>
					<td><textarea rows="35" name="english_post_content" style="width:100%;">Only sign up for one garden day. You are welcome to join other days as well, but in that case please send a message to the board at <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.
You can change which date you want to attend later, until <?php echo "01" . "/" . (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? str_pad(strval($stop_showing_fall_after_month), 2, "0", STR_PAD_LEFT) : str_pad(strval($start_showing_fall_from_month), 2, "0", STR_PAD_LEFT)) . "/" . (intval(date('n')) < $stop_showing_fall_after_month ? date('Y') : strval(intval(date('Y') + 1))); ?> at 12 am. or the dates are full. This is done by cancelling your current selection <a href="/en/for-residents/garden-days/my-signups/">here</a>, and afterwards selecting a different day instead.

In the <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? "fall" : "spring"); ?>, the garden days will have the following overall topics:
- <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? $stop_showing_fall_after_month_name_en : $start_showing_fall_from_month_name_en); ?> 1st: EMNE
- <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? $stop_showing_fall_after_month_name_en : $start_showing_fall_from_month_name_en); ?> 2nd: EMNE
- <?php echo (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? $stop_showing_fall_after_month_name_en : $start_showing_fall_from_month_name_en); ?> 3rd: EMNE

If you have any questions or comments, you are welcome to contact the Board on <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.</textarea></td>
				</tr>
				<tr>
					<td><label>Havedage, åååå-mm-dd, adskilt af komma</label></td>
					<td><textarea rows="2" name="gardenday_dates" style="width:100%;"><?php echo (intval(date('n')) <= $stop_showing_fall_after_month ? date('Y') : strval(intval(date('Y') + 1))) . "-" . (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? str_pad(strval($stop_showing_fall_after_month), 2, "0", STR_PAD_LEFT) : str_pad(strval($start_showing_fall_from_month), 2, "0", STR_PAD_LEFT)) . "-" . "01"; ?>, <?php echo (intval(date('n')) <= $stop_showing_fall_after_month ? date('Y') : strval(intval(date('Y') + 1))) . "-" . (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? str_pad(strval($stop_showing_fall_after_month), 2, "0", STR_PAD_LEFT) : str_pad(strval($start_showing_fall_from_month), 2, "0", STR_PAD_LEFT)) . "-" . "02"; ?>, <?php echo (intval(date('n')) <= $stop_showing_fall_after_month ? date('Y') : strval(intval(date('Y') + 1))) . "-" . (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? str_pad(strval($stop_showing_fall_after_month), 2, "0", STR_PAD_LEFT) : str_pad(strval($start_showing_fall_from_month), 2, "0", STR_PAD_LEFT)) . "-" . "03"; ?></textarea></td>
				</tr>
				<tr>
					<td><label>Start-tidspunkt for havedage</label></td>
					<td><input type="time" name="start_time" value="10:00:00" /></td>
				</tr>
				<tr>
					<td><label>Slut-tidspunkt for havedage</label></td>
					<td><input type="time" name="end_time" value="15:00:00" /></td>
				</tr>
				<tr>
					<td><label>Seneste tidspunkt for tilmelding</label></td>
					<td><input type="datetime-local" name="latest_signup" value="<?php echo (intval(date('n')) <= $stop_showing_fall_after_month ? date('Y') : strval(intval(date('Y') + 1))) . "-" . (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? str_pad(strval($stop_showing_fall_after_month), 2, "0", STR_PAD_LEFT) : str_pad(strval($start_showing_fall_from_month), 2, "0", STR_PAD_LEFT)) . "-" . "01" . "T" . "00" . ":" . "00"; ?>" style="width:100%;" /></td>
				</tr>
				<tr>
					<td><label>Antal pladser pr. havedag</label></td>
					<td><input type="number" name="spaces" value="24" style="width:100%;" /></td>
				</tr>
				<tr>
					<td><label>Antal pladser pr. lejlighed</label></td>
					<td><input type="number" name="max_spaces" value="1" style="width:100%;" /></td>
				</tr>
				<tr>
					<td><label>Tidspunkt for offentliggørelse</label></td>
					<td><input type="datetime-local" name="publish_date" value="<?php echo (intval(date('n')) <= $stop_showing_fall_after_month ? date('Y') : strval(intval(date('Y') + 1))) . "-" . (intval(date('n')) >= $start_showing_fall_from_month && intval(date('n')) <= $stop_showing_fall_after_month ? str_pad(strval($stop_showing_fall_after_month), 2, "0", STR_PAD_LEFT) : str_pad(strval($start_showing_fall_from_month), 2, "0", STR_PAD_LEFT)) . "-" . "01" . "T" . "00" . ":" . "00"; ?>" style="width:100%;" /></td>
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