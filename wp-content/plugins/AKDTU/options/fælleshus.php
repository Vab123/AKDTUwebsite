<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'AKDTU_save_fælleshus_mail_settings') {
		add_action('admin_menu', 'AKDTU_save_fælleshus_mail_settings');
	}
}

function AKDTU_save_fælleshus_mail_settings() {
	update_option('AKDTU_FÆLLESHUS_TO', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_TO']));
	update_option('AKDTU_FÆLLESHUS_FROM', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_FROM']));
	update_option('AKDTU_FÆLLESHUS_CC', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_CC']));
	update_option('AKDTU_FÆLLESHUS_REPLYTO', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_REPLYTO']));
	update_option('AKDTU_FÆLLESHUS_SUBJECT', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_SUBJECT']));
	update_option('AKDTU_FÆLLESHUS_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_MAILCONTENT']));
	update_option('AKDTU_FÆLLESHUS_FORMAT', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_FORMAT']));
	update_option('AKDTU_FÆLLESHUS_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_ATTACHMENTS']));

	new AKDTU_notice('success', 'Indstillingerne blev gemt');
}

function AKDTU_fælleshus_mail_settings() {
	$default_tab = 'settings';
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
	<div class="wrap">
		<!-- Print the page title -->
		<h1>Mailindstillinger</h1>
		<h2>Mail der sendes med opkrævninger for leje af fælleshuset</h2>
		<hr>
		<nav class="nav-tab-wrapper">
			<a href="?page=akdtu-plugin-fælleshus-opkrævning&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Indstillinger</a>
			<a href="?page=akdtu-plugin-fælleshus-opkrævning&tab=test" class="nav-tab <?php if ($tab === 'test') : ?>nav-tab-active<?php endif; ?>">Afprøv</a>
		</nav>
		<?php if ($tab == 'settings') : ?>
			<hr>
			<form method="post" action="">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<input type="hidden" name="action" value="AKDTU_save_fælleshus_mail_settings" />
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Modtager adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_TO" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_TO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_FROM" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_FROM')); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_CC" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_CC')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_REPLYTO" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_REPLYTO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_SUBJECT')); ?>" style="width: 300px" />
								<p><code>#MONTH</code> erstattes med måneden som tekst.</p>
								<p><code>#MONTHNUM</code> erstattes med måneden som tal.</p>
								<p><code>#YEAR</code> erstattes med året.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Vedhæftede filer</th>
							<td>
								<textarea type="text" name="AKDTU_FÆLLESHUS_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Mail indhold</th>
							<td>
								<textarea name="AKDTU_FÆLLESHUS_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_MAILCONTENT')); ?></textarea>
								<p><code>#PAYMENT_INFO</code> erstattes med info omkring hvem der skal betale for leje. Formatet for dette kan rettes nedenunder.</p>
								<p><code>#MONTH</code> erstattes med måned.</p>
								<p><code>#YEAR</code> erstattes med år.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Format for info om betaling</th>
							<td>
								<textarea type="text" name="AKDTU_FÆLLESHUS_FORMAT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_FORMAT')); ?></textarea>
								<p>Dette gentages for hver lejlighed, der har lejet fælleshuset den foregående måned, med linjeskift mellem.</p>
								<p><code>#APT</code> erstattes med lejlighedsnummer. Hvis der nyligt er flyttet en ny beboer ind står dette også herefter som <code>(Ny beboer)</code> eller <code>(Tidligere beboer)</code></p>
								<p><code>#PRICE</code> erstattes med pris.</p>
							</td>
						</tr>
						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem" /></td>
						</tr>
					</tbody>
				</table>
			</form>
		<?php elseif ($tab == 'test') :
			include_once get_home_path() . 'wp-content/plugins/AKDTU/cronjobs/Opkrævning_fælleshus.php';
			send_opkrævning_fælleshus(true);
		endif; ?>
	</div>
<?php } ?>