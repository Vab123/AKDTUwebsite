<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'AKDTU_save_ny_bruger_mail_settings') {
		add_action('admin_menu', 'AKDTU_save_ny_bruger_mail_settings');
	}
}

function AKDTU_save_ny_bruger_mail_settings() {
	update_option('AKDTU_NYBRUGER_BESTYRELSE_TO', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BESTYRELSE_TO']));
	update_option('AKDTU_NYBRUGER_BESTYRELSE_FROM', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BESTYRELSE_FROM']));
	update_option('AKDTU_NYBRUGER_BESTYRELSE_CC', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BESTYRELSE_CC']));
	update_option('AKDTU_NYBRUGER_BESTYRELSE_REPLYTO', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BESTYRELSE_REPLYTO']));
	update_option('AKDTU_NYBRUGER_BESTYRELSE_SUBJECT', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BESTYRELSE_SUBJECT']));
	update_option('AKDTU_NYBRUGER_BESTYRELSE_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BESTYRELSE_MAILCONTENT']));
	update_option('AKDTU_NYBRUGER_BESTYRELSE_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BESTYRELSE_ATTACHMENTS']));

	update_option('AKDTU_NYBRUGER_BRUGER_TOGGLE', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_TOGGLE']));

	update_option('AKDTU_NYBRUGER_BRUGER_DA_FROM', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_FROM']));
	update_option('AKDTU_NYBRUGER_BRUGER_DA_CC', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_CC']));
	update_option('AKDTU_NYBRUGER_BRUGER_DA_REPLYTO', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_REPLYTO']));
	update_option('AKDTU_NYBRUGER_BRUGER_DA_SUBJECT', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_DA_SUBJECT']));
	update_option('AKDTU_NYBRUGER_BRUGER_DA_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_DA_MAILCONTENT']));
	update_option('AKDTU_NYBRUGER_BRUGER_DA_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_DA_ATTACHMENTS']));

	update_option('AKDTU_NYBRUGER_BRUGER_EN_FROM', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_FROM']));
	update_option('AKDTU_NYBRUGER_BRUGER_EN_CC', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_CC']));
	update_option('AKDTU_NYBRUGER_BRUGER_EN_REPLYTO', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_REPLYTO']));
	update_option('AKDTU_NYBRUGER_BRUGER_EN_SUBJECT', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_EN_SUBJECT']));
	update_option('AKDTU_NYBRUGER_BRUGER_EN_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_EN_MAILCONTENT']));
	update_option('AKDTU_NYBRUGER_BRUGER_EN_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_NYBRUGER_BRUGER_EN_ATTACHMENTS']));

	new AKDTU_notice('success', 'Indstillingerne blev gemt');
}

function AKDTU_ny_bruger_mail_settings() {
	$default_tab = 'settings';
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
	<div class="wrap">
		<!-- Print the page title -->
		<h1>Mailindstillinger</h1>
		<h2>Mail der sendes når adgang fjernes til en lejer, der er flyttet</h2>
		<hr>
		<nav class="nav-tab-wrapper">
			<a href="?page=akdtu-plugin-mail-ny-bruger&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Indstillinger</a>
			<a href="?page=akdtu-plugin-mail-ny-bruger&tab=test" class="nav-tab <?php if ($tab === 'test') : ?>nav-tab-active<?php endif; ?>">Afprøv</a>
		</nav>
		<?php if ($tab == 'settings') : ?>
			<hr>
			<form method="post" action="">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<input type="hidden" name="action" value="AKDTU_save_ny_bruger_mail_settings" />
				<h3>Mail til bestyrelse</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Modtager adresse</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BESTYRELSE_TO" value="<?php echo get_option('AKDTU_NYBRUGER_BESTYRELSE_TO'); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BESTYRELSE_FROM" value="<?php echo get_option('AKDTU_NYBRUGER_BESTYRELSE_FROM'); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BESTYRELSE_CC" value="<?php echo get_option('AKDTU_NYBRUGER_BESTYRELSE_CC'); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BESTYRELSE_REPLYTO" value="<?php echo get_option('AKDTU_NYBRUGER_BESTYRELSE_REPLYTO'); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BESTYRELSE_SUBJECT" value="<?php echo get_option('AKDTU_NYBRUGER_BESTYRELSE_SUBJECT'); ?>" style="width: 300px" />
								<p><code>#APT</code> erstattes med lejlighedsnummeret.</p>
								<p><code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.</p>
								<p><code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.</p>
								<p><code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.</p>
								<p><code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Vedhæftede filer</th>
							<td>
								<textarea type="text" name="AKDTU_NYBRUGER_BESTYRELSE_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BESTYRELSE_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Mail indhold</th>
							<td>
								<textarea name="AKDTU_NYBRUGER_BESTYRELSE_MAILCONTENT" rows="5" , cols="50"><?php echo get_option('AKDTU_NYBRUGER_BESTYRELSE_MAILCONTENT'); ?></textarea>
								<p><code>#APT</code> erstattes med lejlighedsnummeret.</p>
								<p><code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.</p>
								<p><code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.</p>
								<p><code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.</p>
								<p><code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.</p>
							</td>
						</tr>
						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem (Alt)" /></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<h3>Mail til ny beboer</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Send email?</th>
							<td>
								<input type="checkbox" name="AKDTU_NYBRUGER_BRUGER_TOGGLE" <?php if (get_option('AKDTU_NYBRUGER_BRUGER_TOGGLE')) {
																								echo " checked";
																							}; ?> />
								<p>Afgør om der sendes bekræftelsesmail til den nyoprettede bruger.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BRUGER_FROM" value="<?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BRUGER_DA_FROM')); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BRUGER_CC" value="<?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BRUGER_DA_CC')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BRUGER_REPLYTO" value="<?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BRUGER_DA_REPLYTO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Emne (Dansk)</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BRUGER_DA_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BRUGER_DA_SUBJECT')); ?>" style="width: 300px" />
								<p><code>#APT</code> erstattes med lejlighedsnummeret.</p>
								<p><code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.</p>
								<p><code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.</p>
								<p><code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.</p>
								<p><code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Vedhæftede filer (Dansk)</th>
							<td>
								<textarea type="text" name="AKDTU_NYBRUGER_BRUGER_DA_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BRUGER_DA_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Mail indhold (Dansk)</th>
							<td>
								<textarea name="AKDTU_NYBRUGER_BRUGER_DA_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BRUGER_DA_MAILCONTENT')); ?></textarea>
								<p><code>#APT</code> erstattes med lejlighedsnummeret.</p>
								<p><code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.</p>
								<p><code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.</p>
								<p><code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.</p>
								<p><code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Emne (Engelsk)</th>
							<td>
								<input type="text" name="AKDTU_NYBRUGER_BRUGER_EN_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BRUGER_EN_SUBJECT')); ?>" style="width: 300px" />
								<p><code>#APT</code> erstattes med lejlighedsnummeret.</p>
								<p><code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.</p>
								<p><code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.</p>
								<p><code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.</p>
								<p><code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Vedhæftede filer (Engelsk)</th>
							<td>
								<textarea type="text" name="AKDTU_NYBRUGER_BRUGER_EN_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BRUGER_EN_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Mail indhold (Engelsk)</th>
							<td>
								<textarea name="AKDTU_NYBRUGER_BRUGER_EN_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_NYBRUGER_BRUGER_EN_MAILCONTENT')); ?></textarea>
								<p><code>#APT</code> erstattes med lejlighedsnummeret.</p>
								<p><code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.</p>
								<p><code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.</p>
								<p><code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.</p>
								<p><code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.</p>
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
			require_once WP_PLUGIN_DIR . '/AKDTU/functions/send_mail.php';
			require_once WP_PLUGIN_DIR . '/AKDTU/definitions.php';

			$_POST['apartment_number'] = 2;
			$user_login = 'lejl002';
			$_POST['email'] = 'victor2@akdtu.dk';
			$_POST['first_name'] = 'Victor';
			$_POST['last_name'] = 'Brandsen';

			$content_replaces = array(
				'#APT' => $_POST['apartment_number'],
				'#NEWLOGIN' => $user_login,
				'#NEWEMAIL' => $_POST['email'],
				'#NEWFIRSTNAME' => $_POST['first_name'],
				'#NEWLASTNAME' => $_POST['last_name']
			);

			$subject_replaces = array(
				'#APT' => $_POST['apartment_number'],
				'#NEWLOGIN' => $user_login,
				'#NEWEMAIL' => $_POST['email'],
				'#NEWFIRSTNAME' => $_POST['first_name'],
				'#NEWLASTNAME' => $_POST['last_name']
			);
		?>
			<h3>Mail til bestyrelse:</h3>
			<?php
			send_AKDTU_email(true, $subject_replaces, $content_replaces, 'NYBRUGER_BESTYRELSE');

			$content_replaces = array(
				'#APT' => $_POST['apartment_number'],
				'#NEWLOGIN' => $user_login,
				'#NEWEMAIL' => $_POST['email'],
				'#NEWFIRSTNAME' => $_POST['first_name'],
				'#NEWLASTNAME' => $_POST['last_name']
			);

			$subject_replaces = array(
				'#APT' => $_POST['apartment_number'],
				'#NEWLOGIN' => $user_login,
				'#NEWEMAIL' => $_POST['email'],
				'#NEWFIRSTNAME' => $_POST['first_name'],
				'#NEWLASTNAME' => $_POST['last_name']
			);
			?>
			<hr>
			<h3>Mail til ny beboer (dansk):</h3>
			<?php
			send_AKDTU_email(true, $subject_replaces, $content_replaces, 'NYBRUGER_BRUGER_DA', $_POST['email']);
			?>
			<hr>
			<h3>Mail til ny beboer (engelsk):</h3>
		<?php
			send_AKDTU_email(true, $subject_replaces, $content_replaces, 'NYBRUGER_BRUGER_EN', $_POST['email']);
		endif; ?>
	</div>
<?php } ?>