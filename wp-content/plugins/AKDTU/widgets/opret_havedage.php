<?php

/**
 * @file Widget to create all events and signup structure for all garden days for a season
 */

function opret_havedag_dashboard_widget() {
	$start_showing_fall_from_month = 3;		# Month in which to start displaying fall garden day information (Including this month)
	$stop_showing_fall_after_month = 9;		# Month in which to stop displaying fall garden day information (After this month)

	$total_spaces = 72;
	$dates_to_show = [1,2,3];

	$months_da = new IntlDateFormatter("da_DK", IntlDateFormatter::NONE, IntlDateFormatter::NONE, "Europe/Copenhagen", null, "dd. MMMM");
	$months_en = new IntlDateFormatter("en_US", IntlDateFormatter::NONE, IntlDateFormatter::NONE, "Europe/Copenhagen", null, "MMMM dd");
	
	$date_formatter = new IntlDateFormatter("da_DK", IntlDateFormatter::NONE, IntlDateFormatter::NONE, "Europe/Copenhagen", null, "YYYY-MM-dd");
	$datetime_formatter = new IntlDateFormatter("da_DK", IntlDateFormatter::NONE, IntlDateFormatter::NONE, "Europe/Copenhagen", null, "YYYY-MM-dd'T'HH:mm");
	$time_formatter = new IntlDateFormatter("da_DK", IntlDateFormatter::NONE, IntlDateFormatter::NONE, "Europe/Copenhagen", null, "HH:mm:ss");

	$full_formatter_da = new IntlDateFormatter("da_DK", IntlDateFormatter::NONE, IntlDateFormatter::NONE, "Europe/Copenhagen", null, "dd/MM/YYYY");
	$full_formatter_en = new IntlDateFormatter("da_DK", IntlDateFormatter::NONE, IntlDateFormatter::NONE, "Europe/Copenhagen", null, "dd/MM/YYYY");

	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));
	$t_start = (new DateTime('first day of this month', new DateTimeZone("Europe/Copenhagen")))->setDate(date('Y'), $start_showing_fall_from_month, 1)->setTime(0, 0, 0);
	$t_end = (new DateTime('first day of this month', new DateTimeZone("Europe/Copenhagen")))->setDate(date('Y'), $stop_showing_fall_after_month, 1)->modify('last day of')->setTime(23, 59, 59);

	$time = $now->getTimestamp() > $t_start->getTimestamp() && $now->getTimestamp() < $t_end->getTimestamp() ? (clone $t_end) : (clone $t_start)->add(new DateInterval('P1Y'));

	$signup_deadline = (clone $time)->setDate($time->format('Y'), $time->format('m'), $dates_to_show[0])->setTime(23, 59, 59)->sub(new DateInterval('P1D'));
	$publish_date = (clone $time)->setDate($time->format('Y'), $time->format('m'), $dates_to_show[0])->setTime(0, 0, 0)->sub(new DateInterval('P1M'));
	$start_time = (clone $time)->setTime(10, 0, 0);
	$end_time = (clone $time)->setTime(15, 0, 0);

	$season_da = $now->getTimestamp() > $t_start->getTimestamp() && $now->getTimestamp() < $t_end->getTimestamp() ? "efteråret" : "foråret";
	$season_en = $now->getTimestamp() > $t_start->getTimestamp() && $now->getTimestamp() < $t_end->getTimestamp() ? "fall" : "spring";

	$event_title_da = ($now->getTimestamp() > $t_start->getTimestamp() && $now->getTimestamp() < $t_end->getTimestamp() ? "Efterår " : "Forår ") . $time->format('Y');
	$event_title_en = ($now->getTimestamp() > $t_start->getTimestamp() && $now->getTimestamp() < $t_end->getTimestamp() ? "Fall " : "Spring ") . $time->format('Y');

	?>
	<form action="" method="post">
		<input type="hidden" name="action" value="add_havedag" />
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead></thead>
			<tbody>
				<tr class="alternate">
					<td style="vertical-align:middle;"><label>Dansk navn</label></td>
					<td style="vertical-align:middle;"><input type="text" name="danish_name" value="Havedag - <?php echo $event_title_da; ?>" style="width:100%;" /></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;"><label>Dansk tekst</label></td>
					<td style="vertical-align:middle;"><textarea rows="35" name="danish_post_content" style="width:100%;">Tilmeld dig kun havedag én dag. Man er velkommen til at deltage flere dage end den man har tilmeldt sig, men i så fald bedes man sende en besked til bestyrelsen på <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.
Du kan ændre hvilken dato du har tilmeldt dig senere, indtil midnat efter d. <?php echo $full_formatter_da->format($signup_deadline); ?> eller dagene er fyldt. Det gør du ved først at afmelde dig din nuværende dag <a href="/for-beboere/havedage/mine-tilmeldinger/">her</a>, og derefter tilmelde dig en anden dag i stedet.

I <?php echo $season_da; ?> bliver havedagenes overordnede emner følgende:
<?php echo join(array_map(function ($day) use ($months_da, $time) {return " - " . $months_da->format($time->setDate($time->format('Y'), $time->format('m'), $day)) . ": EMNE\n";}, $dates_to_show), ""); ?>

Hvis du har nogen spørgsmål eller kommentarer er du velkommen til at kontakte bestyrelsen på <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.</textarea></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle;"><label>Engelsk navn</label></td>
					<td style="vertical-align:middle;"><input type="text" name="english_name" value="Gardenday - <?php echo $event_title_en; ?>" style="width:100%;" /></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;"><label>Engelsk tekst</label></td>
					<td style="vertical-align:middle;"><textarea rows="35" name="english_post_content" style="width:100%;">Only sign up for one garden day. You are welcome to join other days as well, but in that case please send a message to the board at <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.
You can change which date you want to attend later, until midnight after <?php echo $full_formatter_en->format($signup_deadline); ?> or the dates are full. This is done by cancelling your current selection <a href="/en/for-residents/garden-days/my-signups/">here</a>, and afterwards selecting a different day instead.

In the <?php echo $season_en; ?>, the garden days will have the following overall topics:
<?php echo join(array_map(function ($day) use ($months_en, $time) {return " - " . $months_en->format((clone $time)->setDate($time->format('Y'), $time->format('m'), $day)) . ": EMNE\n";}, $dates_to_show), ""); ?>

If you have any questions or comments, you are welcome to contact the Board on <a href="mailto:bestyrelsen@akdtu.dk">bestyrelsen@akdtu.dk</a>.</textarea></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle;"><label>Havedage, åååå-mm-dd, adskilt af komma</label></td>
					<td style="vertical-align:middle;"><textarea rows="2" name="gardenday_dates" style="width:100%;"><?php echo join(array_map( function ($day) use ($time, $date_formatter) { return $date_formatter->format((clone $time)->setDate($time->format('Y'), $time->format('m'), $day)); }, $dates_to_show), ","); ?></textarea></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;"><label>Start-tidspunkt for havedage</label></td>
					<td style="vertical-align:middle;"><input type="time" name="start_time" value="<?php echo $time_formatter->format($start_time); ?>" /></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle;"><label>Slut-tidspunkt for havedage</label></td>
					<td style="vertical-align:middle;"><input type="time" name="end_time" value="<?php echo $time_formatter->format($end_time); ?>" /></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;"><label>Seneste tidspunkt for tilmelding</label></td>
					<td style="vertical-align:middle;"><input type="datetime-local" name="latest_signup" value="<?php echo $datetime_formatter->format($signup_deadline); ?>" style="width:100%;" /></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle;"><label>Antal pladser pr. havedag</label></td>
					<td style="vertical-align:middle;"><input type="number" name="spaces" value="<?php echo ceil($total_spaces/count($dates_to_show)); ?>" style="width:100%;" /></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;"><label>Antal pladser pr. lejlighed</label></td>
					<td style="vertical-align:middle;"><input type="number" name="max_spaces" value="1" style="width:100%;" /></td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle;"><label>Tidspunkt for offentliggørelse</label></td>
					<td style="vertical-align:middle;"><input type="datetime-local" name="publish_date" value="<?php echo $datetime_formatter->format($publish_date); ?>" style="width:100%;" /></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;"></td>
					<td style="vertical-align:middle;"><input type="submit" class="button-secondary" value="Opret havedage" /></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
}

?>