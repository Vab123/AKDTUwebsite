<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'AKDTU_save_leje_af_fælleshus_bestyrelse_besluttet_mail_settings') {
		add_action('admin_menu', 'AKDTU_save_leje_af_fælleshus_bestyrelse_besluttet_mail_settings');
	}
}

function AKDTU_save_leje_af_fælleshus_bestyrelse_besluttet_mail_settings() {
	update_option('dbem_event_approved_confirmation_email_subject_da', $_REQUEST['dbem_event_approved_confirmation_email_subject_da']);
	update_option('dbem_event_approved_confirmation_email_subject_en', $_REQUEST['dbem_event_approved_confirmation_email_subject_en']);
	update_option('dbem_event_approved_confirmation_email_body_da', $_REQUEST['dbem_event_approved_confirmation_email_body_da']);
	update_option('dbem_event_approved_confirmation_email_body_en', $_REQUEST['dbem_event_approved_confirmation_email_body_en']);
	update_option('dbem_event_approved_confirmation_email_attachments_da', $_REQUEST['dbem_event_approved_confirmation_email_attachments_da']);
	update_option('dbem_event_approved_confirmation_email_attachments_en', $_REQUEST['dbem_event_approved_confirmation_email_attachments_en']);

	update_option('dbem_event_reapproved_confirmation_email_subject_da', $_REQUEST['dbem_event_reapproved_confirmation_email_subject_da']);
	update_option('dbem_event_reapproved_confirmation_email_subject_en', $_REQUEST['dbem_event_reapproved_confirmation_email_subject_en']);
	update_option('dbem_event_reapproved_confirmation_email_body_da', $_REQUEST['dbem_event_reapproved_confirmation_email_body_da']);
	update_option('dbem_event_reapproved_confirmation_email_body_en', $_REQUEST['dbem_event_reapproved_confirmation_email_body_en']);
	update_option('dbem_event_reapproved_confirmation_email_attachments_da', $_REQUEST['dbem_event_reapproved_confirmation_email_attachments_da']);
	update_option('dbem_event_reapproved_confirmation_email_attachments_en', $_REQUEST['dbem_event_reapproved_confirmation_email_attachments_en']);

	update_option('dbem_event_rejected_confirmation_email_subject_da', $_REQUEST['dbem_event_rejected_confirmation_email_subject_da']);
	update_option('dbem_event_rejected_confirmation_email_subject_en', $_REQUEST['dbem_event_rejected_confirmation_email_subject_en']);
	update_option('dbem_event_rejected_confirmation_email_body_da', $_REQUEST['dbem_event_rejected_confirmation_email_body_da']);
	update_option('dbem_event_rejected_confirmation_email_body_en', $_REQUEST['dbem_event_rejected_confirmation_email_body_en']);
	update_option('dbem_event_rejected_confirmation_email_attachments_da', $_REQUEST['dbem_event_rejected_confirmation_email_attachments_da']);
	update_option('dbem_event_rejected_confirmation_email_attachments_en', $_REQUEST['dbem_event_rejected_confirmation_email_attachments_en']);

	new AKDTU_notice('success', 'Indstillingerne blev gemt');
}

function AKDTU_leje_af_fælleshus_bestyrelse_besluttet_mail_settings() {
	$default_tab = 'settings';
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
	<div class="wrap">
		<!-- Print the page title -->
		<h1>Mailindstillinger</h1>
		<h2>Mail der sendes til bestyrelsen når ansøgninger om leje af fælleshus besvares.</h2>
		<hr>
		<nav class="nav-tab-wrapper">
			<a href="?page=akdtu-plugin-mail-leje-af-fælleshus-bestyrelse-besluttet&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Indstillinger</a>
			<a href="?page=akdtu-plugin-mail-leje-af-fælleshus-bestyrelse-besluttet&tab=test" class="nav-tab <?php if ($tab === 'test') : ?>nav-tab-active<?php endif; ?>">Afprøv</a>
		</nav>
		<?php if ($tab == 'settings') : ?>
			<hr>
			<form method="post" action="">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<input type="hidden" name="action" value="AKDTU_save_leje_af_fælleshus_bestyrelse_besluttet_mail_settings" />
				<h3>Mail ved godkendt leje</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Emne (Dansk)</th>
							<td>
								<input type="text" name="dbem_event_approved_confirmation_email_subject_da" value="<?php echo stripcslashes(get_option('dbem_event_approved_confirmation_email_subject_da')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold (Dansk)</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_event_approved_confirmation_email_body_da"><?php echo stripcslashes(get_option('dbem_event_approved_confirmation_email_body_da')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
							</td>
						</tr>
						<tr>
							<th scope=" row">Vedhæftede filer (Dansk)</th>
							<td>
								<input type="text" name="dbem_event_approved_confirmation_email_attachments_da" value="<?php echo stripcslashes(get_option('dbem_event_approved_confirmation_email_attachments_da')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at vedhæfte noget.</p>
								<p>Skal være relativt til <code>/var/www/akdtu.dk/public_html</code></p>
								<p>Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.</p>
							</td>
						</tr>

						<tr>
							<th scope="row">Emne (Engelsk)</th>
							<td>
								<input type="text" name="dbem_event_approved_confirmation_email_subject_en" value="<?php echo stripcslashes(get_option('dbem_event_approved_confirmation_email_subject_en')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold (Engelsk)</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_event_approved_confirmation_email_body_en"><?php echo stripcslashes(get_option('dbem_event_approved_confirmation_email_body_en')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
							</td>
						</tr>
						<tr>
							<th scope=" row">Vedhæftede filer (Engelsk)</th>
							<td>
								<input type="text" name="dbem_event_approved_confirmation_email_attachments_en" value="<?php echo stripcslashes(get_option('dbem_event_approved_confirmation_email_attachments_en')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at vedhæfte noget.</p>
								<p>Skal være relativt til <code>/var/www/akdtu.dk/public_html</code></p>
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
				<h3>Mail ved godkendt ændring leje</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Emne (Dansk)</th>
							<td>
								<input type="text" name="dbem_event_reapproved_confirmation_email_subject_da" value="<?php echo stripcslashes(get_option('dbem_event_reapproved_confirmation_email_subject_da')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold (Dansk)</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_event_reapproved_confirmation_email_body_da"><?php echo stripcslashes(get_option('dbem_event_reapproved_confirmation_email_body_da')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
							</td>
						</tr>
						<tr>
							<th scope=" row">Vedhæftede filer (Dansk)</th>
							<td>
								<input type="text" name="dbem_event_reapproved_confirmation_email_attachments_da" value="<?php echo stripcslashes(get_option('dbem_event_reapproved_confirmation_email_attachments_da')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at vedhæfte noget.</p>
								<p>Skal være relativt til <code>/var/www/akdtu.dk/public_html</code></p>
								<p>Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.</p>
							</td>
						</tr>

						<tr>
							<th scope="row">Emne (Engelsk)</th>
							<td>
								<input type="text" name="dbem_event_reapproved_confirmation_email_subject_en" value="<?php echo stripcslashes(get_option('dbem_event_reapproved_confirmation_email_subject_en')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold (Engelsk)</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_event_reapproved_confirmation_email_body_en"><?php echo stripcslashes(get_option('dbem_event_reapproved_confirmation_email_body_en')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
							</td>
						</tr>
						<tr>
							<th scope=" row">Vedhæftede filer (Dansk)</th>
							<td>
								<input type="text" name="dbem_event_reapproved_confirmation_email_attachments_en" value="<?php echo stripcslashes(get_option('dbem_event_reapproved_confirmation_email_attachments_en')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at vedhæfte noget.</p>
								<p>Skal være relativt til <code>/var/www/akdtu.dk/public_html</code></p>
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
							<th scope="row">Emne (Dansk)</th>
							<td>
								<input type="text" name="dbem_event_rejected_confirmation_email_subject_da" value="<?php echo stripcslashes(get_option('dbem_event_rejected_confirmation_email_subject_da')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold (Dansk)</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_event_rejected_confirmation_email_body_da"><?php echo stripcslashes(get_option('dbem_event_rejected_confirmation_email_body_da')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
							</td>
						</tr>
						<tr>
							<th scope=" row">Vedhæftede filer (Dansk)</th>
							<td>
								<input type="text" name="dbem_event_rejected_confirmation_email_attachments_da" value="<?php echo stripcslashes(get_option('dbem_event_rejected_confirmation_email_attachments_da')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at vedhæfte noget.</p>
								<p>Skal være relativt til <code>/var/www/akdtu.dk/public_html</code></p>
								<p>Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.</p>
							</td>
						</tr>

						<tr>
							<th scope="row">Emne (Engelsk)</th>
							<td>
								<input type="text" name="dbem_event_rejected_confirmation_email_subject_en" value="<?php echo stripcslashes(get_option('dbem_event_rejected_confirmation_email_subject_en')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Indhold (Engelsk)</th>
							<td>
								<textarea rows="5" style="width:95%" name="dbem_event_rejected_confirmation_email_body_en"><?php echo stripcslashes(get_option('dbem_event_rejected_confirmation_email_body_en')); ?></textarea>
								<p>Understøtter <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders" target="_blank">Tilmeldingsrelaterede pladsholdere</a>, <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders" target="_blank">Event relateret pladsholdere</a> og <a href="/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders" target="_blank">Lokationsrelateret pladsholdere</a>.</p>
							</td>
						</tr>
						<tr>
							<th scope=" row">Vedhæftede filer (Dansk)</th>
							<td>
								<input type="text" name="dbem_event_rejected_confirmation_email_attachments_en" value="<?php echo stripcslashes(get_option('dbem_event_rejected_confirmation_email_attachments_en')); ?>" style="width: 95%" />
								<p>Efterlad tom for ikke at vedhæfte noget.</p>
								<p>Skal være relativt til <code>/var/www/akdtu.dk/public_html</code></p>
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

				echo '<h3>Mail ved godkendt leje</h3><h4>Dansk:</h4>';
				echo_AKDTU_email_as_table(
					$admin_email,
					$admin_email,
					$admin_email,
					'',
					prepend_attachments_string(get_option('dbem_event_approved_confirmation_email_attachments_da')),
					$EM_Event->output(get_option('dbem_event_approved_confirmation_email_subject_da'), 'raw'),
					nl2br($EM_Event->output(get_option('dbem_event_approved_confirmation_email_body_da'), 'html'))
				);
				echo '<h4>Engelsk:</h4>';
				echo_AKDTU_email_as_table(
					$admin_email,
					$admin_email,
					$admin_email,
					'',
					prepend_attachments_string(get_option('dbem_event_approved_confirmation_email_attachments_en')),
					$EM_Event->output(get_option('dbem_event_approved_confirmation_email_subject_en'), 'raw'),
					nl2br($EM_Event->output(get_option('dbem_event_approved_confirmation_email_body_en'), 'html'))
				);

				echo '<br><hr><h3>Mail ved godkendt ændring af leje</h3><h4>Dansk:</h4>';
				echo_AKDTU_email_as_table(
					$admin_email,
					$admin_email,
					$admin_email,
					'',
					prepend_attachments_string(get_option('dbem_event_reapproved_confirmation_email_attachments_da')),
					$EM_Event->output(get_option('dbem_event_reapproved_confirmation_email_subject_da'), 'raw'),
					nl2br($EM_Event->output(get_option('dbem_event_reapproved_confirmation_email_body_da'), 'html'))
				);
				echo '<h4>Engelsk:</h4>';
				echo_AKDTU_email_as_table(
					$admin_email,
					$admin_email,
					$admin_email,
					'',
					prepend_attachments_string(get_option('dbem_event_reapproved_confirmation_email_attachments_en')),
					$EM_Event->output(get_option('dbem_event_reapproved_confirmation_email_subject_en'), 'raw'),
					nl2br($EM_Event->output(get_option('dbem_event_reapproved_confirmation_email_body_en'), 'html'))
				);

				echo '<br><hr><h3>Mail ved afvist leje</h3><h4>Dansk:</h4>';
				echo_AKDTU_email_as_table(
					$admin_email,
					$admin_email,
					$admin_email,
					'',
					prepend_attachments_string(get_option('dbem_event_rejected_confirmation_email_attachments_da')),
					$EM_Event->output(get_option('dbem_event_rejected_confirmation_email_subject_da'), 'raw'),
					nl2br($EM_Event->output(get_option('dbem_event_rejected_confirmation_email_body_da'), 'html'))
				);
				echo '<h4>Engelsk:</h4>';
				echo_AKDTU_email_as_table(
					$admin_email,
					$admin_email,
					$admin_email,
					'',
					prepend_attachments_string(get_option('dbem_event_rejected_confirmation_email_attachments_en')),
					$EM_Event->output(get_option('dbem_event_rejected_confirmation_email_subject_en'), 'raw'),
					nl2br($EM_Event->output(get_option('dbem_event_rejected_confirmation_email_body_en'), 'html'))
				);
			} else {
				echo 'Der er ingen begivenheder der kan bruges til test...';
			}
		endif; ?>
	</div>
<?php
}
