<?php

# Add action to save changed settings
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'AKDTU_save_leje_af_fælleshus_bestyrelse_modtaget_mail_settings') {
		add_action('admin_menu', 'AKDTU_save_leje_af_fælleshus_bestyrelse_modtaget_mail_settings');
	}
}

function AKDTU_save_leje_af_fælleshus_bestyrelse_modtaget_mail_settings() {
	# Save form info
	update_option('dbem_event_submitted_email_subject', stripcslashes($_REQUEST['dbem_event_submitted_email_subject']));
	update_option('dbem_event_submitted_email_body', stripcslashes($_REQUEST['dbem_event_submitted_email_body']));
	update_option('dbem_event_submitted_email_attachments', stripcslashes($_REQUEST['dbem_event_submitted_email_attachments']));

	update_option('dbem_event_resubmitted_email_subject', stripcslashes($_REQUEST['dbem_event_resubmitted_email_subject']));
	update_option('dbem_event_resubmitted_email_body', stripcslashes($_REQUEST['dbem_event_resubmitted_email_body']));
	update_option('dbem_event_resubmitted_email_attachments', stripcslashes($_REQUEST['dbem_event_resubmitted_email_attachments']));

	update_option('dbem_event_deleted_email_subject', stripcslashes($_REQUEST['dbem_event_deleted_email_subject']));
	update_option('dbem_event_deleted_email_body', stripcslashes($_REQUEST['dbem_event_deleted_email_body']));
	update_option('dbem_event_deleted_email_attachments', stripcslashes($_REQUEST['dbem_event_deleted_email_attachments']));

	# Form info saved. Write success message to admin interface
	new AKDTU_notice('success', 'Indstillingerne blev gemt');
}

# Write settings interface
function AKDTU_leje_af_fælleshus_bestyrelse_modtaget_mail_settings() {
	$default_tab = 'settings';
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
	<div class="wrap">
		<!-- Print the page title -->
		<h1>Mailindstillinger</h1>
		<h2>Mail der sendes til bestyrelsen når ansøgninger om leje af fælleshus modtages.</h2>
		<hr>
		<nav class="nav-tab-wrapper">
			<a href="?page=akdtu-plugin-leje-af-fælleshus-bestyrelse-modtaget-mail-settings&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Indstillinger</a>
			<a href="?page=akdtu-plugin-leje-af-fælleshus-bestyrelse-modtaget-mail-settings&tab=test" class="nav-tab <?php if ($tab === 'test') : ?>nav-tab-active<?php endif; ?>">Afprøv</a>
		</nav>
		<?php if ($tab == 'settings') : ?>
			<hr>
			<form method="post" action="">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<input type="hidden" name="action" value="AKDTU_save_leje_af_fælleshus_bestyrelse_modtaget_mail_settings" />
				<h3>Mail ved ny ansøgning</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="dbem_event_submitted_email_subject" value="<?php echo stripcslashes(get_option('dbem_event_submitted_email_subject')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_event_submitted_email_body"><?php echo stripcslashes(get_option('dbem_event_submitted_email_body')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
							</td>
						</tr>
						<tr>
							<th scope=" row">Vedhæftede filer</th>
							<td>
								<input type="text" name="dbem_event_submitted_email_attachments" value="<?php echo stripcslashes(get_option('dbem_event_submitted_email_attachments')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at vedhæfte noget.</p>
								<p>Skal være relativt til <code><?php echo website_root_folder(); ?></code></p>
								<p>Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.</p>
							</td>
						</tr>

						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem (Alt)" /></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<h3>Mail ved ændret ansøgning</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="dbem_event_resubmitted_email_subject" value="<?php echo stripcslashes(get_option('dbem_event_resubmitted_email_subject')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_event_resubmitted_email_body"><?php echo stripcslashes(get_option('dbem_event_resubmitted_email_body')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
							</td>
						</tr>
						<tr>
							<th scope=" row">Vedhæftede filer</th>
							<td>
								<input type="text" name="dbem_event_resubmitted_email_attachments" value="<?php echo stripcslashes(get_option('dbem_event_resubmitted_email_attachments')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at vedhæfte noget.</p>
								<p>Skal være relativt til <code><?php echo website_root_folder(); ?></code></p>
								<p>Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.</p>
							</td>
						</tr>

						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem (Alt)" /></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<h3>Mail ved afvist leje</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="dbem_event_deleted_email_subject" value="<?php echo stripcslashes(get_option('dbem_event_deleted_email_subject')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_event_deleted_email_body"><?php echo stripcslashes(get_option('dbem_event_deleted_email_body')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
							</td>
						</tr>
						<tr>
							<th scope=" row">Vedhæftede filer</th>
							<td>
								<input type="text" name="dbem_event_deleted_email_attachments" value="<?php echo stripcslashes(get_option('dbem_event_deleted_email_attachments')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at vedhæfte noget.</p>
								<p>Skal være relativt til <code><?php echo website_root_folder(); ?></code></p>
								<p>Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.</p>
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

			$events = array_filter(EM_Events::get(array('scope' => 'future', 'limit' => 10, 'offset' => 0, 'order' => 'ASC', 'orderby' => 'event_start', 'bookings' => false, 'owner' => false, 'pagination' => 0)), function ($event) {
				return substr($event->event_name, 0, 28) == "#_RENTAL_BEFORE_APARTMENTNUM";
			});

			if (count($events) > 0) {
				$EM_Event = $events[array_keys($events)[0]];

				$admin_email = get_option('dbem_event_submitted_email_admin');
				$owner_email = get_userdata($EM_Event->event_owner)->user_confirmation_email;

				echo '<h3>Mail ved ny ansøgning</h3>';
				echo_AKDTU_email_as_table(
					$admin_email,
					$admin_email,
					$admin_email,
					'',
					prepend_attachments_string(get_option('dbem_event_submitted_email_attachments')),
					$EM_Event->output(get_option('dbem_event_submitted_email_subject'), 'raw'),
					nl2br($EM_Event->output(get_option('dbem_event_submitted_email_body'), 'html'))
				);

				echo '<br><hr><h3>Mail ved ændret ansøgning</h3>';
				echo_AKDTU_email_as_table(
					$admin_email,
					$admin_email,
					$admin_email,
					'',
					prepend_attachments_string(get_option('dbem_event_resubmitted_email_attachments')),
					$EM_Event->output(get_option('dbem_event_resubmitted_email_subject'), 'raw'),
					nl2br($EM_Event->output(get_option('dbem_event_resubmitted_email_body'), 'html'))
				);

				echo '<br><hr><h3>Mail ved godkendt ændring af leje</h3>';
				echo_AKDTU_email_as_table(
					$admin_email,
					$admin_email,
					$admin_email,
					'',
					prepend_attachments_string(get_option('dbem_event_deleted_email_attachments')),
					$EM_Event->output(get_option('dbem_event_deleted_email_subject'), 'raw'),
					nl2br($EM_Event->output(get_option('dbem_event_deleted_email_body'), 'html'))
				);
			} else {
				echo 'Der er ingen begivenheder der kan bruges til test...';
			}
		endif; ?>
	</div>
<?php
}
