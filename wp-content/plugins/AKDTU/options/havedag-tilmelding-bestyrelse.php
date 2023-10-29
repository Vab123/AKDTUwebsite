<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'AKDTU_save_havedag_tilmelding_bestyrelse_mail_settings') {
		add_action('admin_menu', 'AKDTU_save_havedag_tilmelding_bestyrelse_mail_settings');
	}
}

function AKDTU_save_havedag_tilmelding_bestyrelse_mail_settings() {
	update_option('dbem_bookings_notify_admin', stripcslashes($_REQUEST['dbem_bookings_notify_admin']));

	update_option('dbem_bookings_contact_email_confirmed_subject', stripcslashes($_REQUEST['dbem_bookings_contact_email_confirmed_subject']));
	update_option('dbem_bookings_contact_email_confirmed_body', stripcslashes($_REQUEST['dbem_bookings_contact_email_confirmed_body']));

	update_option('dbem_bookings_contact_email_cancelled_subject', stripcslashes($_REQUEST['dbem_bookings_contact_email_cancelled_subject']));
	update_option('dbem_bookings_contact_email_cancelled_body', stripcslashes($_REQUEST['dbem_bookings_contact_email_cancelled_body']));

	new AKDTU_notice('success', 'Indstillingerne blev gemt');
}

function AKDTU_havedag_tilmelding_bestyrelse_mail_settings() {
	$default_tab = 'settings';
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
	<div class="wrap">
		<!-- Print the page title -->
		<h1>Mailindstillinger</h1>
		<h2>Mail der sendes til bestyrelsen når en beboer tilmelder eller afmelder sig en havedag.</h2>
		<hr>
		<nav class="nav-tab-wrapper">
			<a href="?page=akdtu-plugin-havedag-tilmelding-bestyrelse-mail-settings&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Indstillinger</a>
			<a href="?page=akdtu-plugin-havedag-tilmelding-bestyrelse-mail-settings&tab=test" class="nav-tab <?php if ($tab === 'test') : ?>nav-tab-active<?php endif; ?>">Afprøv</a>
		</nav>
		<?php if ($tab == 'settings') : ?>
			<hr>
			<form method="post" action="">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<input type="hidden" name="action" value="AKDTU_save_havedag_tilmelding_bestyrelse_mail_settings" />
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Email-adresse</th>
							<td>
								<input type="text" name="dbem_bookings_notify_admin" value="<?php echo stripcslashes(get_option('dbem_bookings_notify_admin')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan KUN skrives ind som <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>

						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem (Alt)" /></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<h3>Tilmelding</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="dbem_bookings_contact_email_confirmed_subject" value="<?php echo stripcslashes(get_option('dbem_bookings_contact_email_confirmed_subject')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_bookings_contact_email_confirmed_body"><?php echo stripcslashes(get_option('dbem_bookings_contact_email_confirmed_body')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
								<p><code>#_BOOKINGNAME</code> erstattes med navnet på havedagen.</p>
								<p><code>#_BOOKINGTICKETNAME</code> erstattes med datoen på havedagen.</p>
								<p><code>#_BOOKINGCOMMENT</code> erstattes med en eventuel kommentar til tilmeldingen.</p>
								<p><code>#_BOOKEDSPACES</code> erstattes med det samlede antal af tilmeldinger.</p>
								<p><code>#_AVAILABLESPACES</code> erstattes med det samlede antal ledige pladser.</p>
								<p><code>#_EVENTINFO</code> erstattes med et overblik over antal tilmeldinger og antal mulige tilmeldinger til alle de planlagte havedage.</p>
							</td>
						</tr>

						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem (Alt)" /></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<h3>Afmelding</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="dbem_bookings_contact_email_cancelled_subject" value="<?php echo stripcslashes(get_option('dbem_bookings_contact_email_cancelled_subject')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_bookings_contact_email_cancelled_body"><?php echo stripcslashes(get_option('dbem_bookings_contact_email_cancelled_body')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
								<p><code>#_BOOKINGNAME</code> erstattes med navnet på havedagen.</p>
								<p><code>#_BOOKINGTICKETNAME</code> erstattes med datoen på havedagen.</p>
								<p><code>#_BOOKINGCOMMENT</code> erstattes med en eventuel kommentar til tilmeldingen.</p>
								<p><code>#_BOOKEDSPACES</code> erstattes med det samlede antal af tilmeldinger.</p>
								<p><code>#_AVAILABLESPACES</code> erstattes med det samlede antal ledige pladser.</p>
								<p><code>#_EVENTINFO</code> erstattes med et overblik over antal tilmeldinger og antal mulige tilmeldinger til alle de planlagte havedage.</p>
							</td>
						</tr>

						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem (Alt)" /></td>
						</tr>
					</tbody>
				</table>
			</form>
		<?php elseif ($tab == 'test') :
			include_once get_home_path() . 'wp-content/plugins/AKDTU/functions/send_mail.php';

			$events = EM_Events::get(array('scope' => 'past', 'limit' => 1, 'offset' => 0, 'order' => 'ASC', 'orderby' => 'event_start', 'bookings' => true, 'owner' => false, 'pagination' => 0));

			if (count($events) > 0) {
				$EM_Event = $events[array_keys($events)[0]];

				$bookings = $EM_Event->get_bookings()->bookings;

				if (count($bookings) > 0) {

					$EM_Booking = $bookings[array_keys($bookings)[0]];

					$to_email = get_option('dbem_bookings_notify_admin');
					$from_email = get_option('dbem_event_submitted_email_admin');

					echo '<h3>Mail ved tilmelding</h3>';
					echo_AKDTU_email_as_table(
						$to_email,
						$from_email,
						'',
						'',
						array(),
						$EM_Booking->output(get_option('dbem_bookings_contact_email_confirmed_subject'), 'raw'),
						nl2br($EM_Booking->output(get_option('dbem_bookings_contact_email_confirmed_body'), 'html'))
					);

					echo '<br><hr><h3>Mail ved afmelding</h3>';
					echo_AKDTU_email_as_table(
						$to_email,
						$from_email,
						'',
						'',
						array(),
						$EM_Booking->output(get_option('dbem_bookings_contact_email_cancelled_subject'), 'raw'),
						nl2br($EM_Booking->output(get_option('dbem_bookings_contact_email_cancelled_body'), 'html'))
					);
				} else {
					echo 'Der er ingen tilmeldinger på den seneste begivenhed der kan bruges til test...';
				}
			} else {
				echo 'Der er ingen begivenheder der kan bruges til test...';
			}
		endif; ?>
	</div>
<?php
}
