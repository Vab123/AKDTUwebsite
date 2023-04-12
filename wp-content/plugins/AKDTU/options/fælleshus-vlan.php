<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'AKDTU_save_fælleshus_vlan_mail_settings') {
		add_action('admin_menu', 'AKDTU_save_fælleshus_vlan_mail_settings');
	}
}

function AKDTU_save_fælleshus_vlan_mail_settings() {
	update_option('AKDTU_FÆLLESHUS_VLAN_TO', $_REQUEST['AKDTU_FÆLLESHUS_VLAN_TO']);
	update_option('AKDTU_FÆLLESHUS_VLAN_FROM', $_REQUEST['AKDTU_FÆLLESHUS_VLAN_FROM']);
	update_option('AKDTU_FÆLLESHUS_VLAN_CC', $_REQUEST['AKDTU_FÆLLESHUS_VLAN_CC']);
	update_option('AKDTU_FÆLLESHUS_VLAN_REPLYTO', $_REQUEST['AKDTU_FÆLLESHUS_VLAN_REPLYTO']);
	update_option('AKDTU_FÆLLESHUS_VLAN_SUBJECT', $_REQUEST['AKDTU_FÆLLESHUS_VLAN_SUBJECT']);
	update_option('AKDTU_FÆLLESHUS_VLAN_MAILCONTENT', $_REQUEST['AKDTU_FÆLLESHUS_VLAN_MAILCONTENT']);
	update_option('AKDTU_FÆLLESHUS_VLAN_ATTACHMENTS', $_REQUEST['AKDTU_FÆLLESHUS_VLAN_ATTACHMENTS']);

	new AKDTU_notice('success', 'Indstillingerne blev gemt');
}

function AKDTU_fælleshus_vlan_mail_settings() {
	$default_tab = 'settings';
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
	<div class="wrap">
		<!-- Print the page title -->
		<h1>Mailindstillinger</h1>
		<h2>Mail der sendes når VLAN status ændres i fælleshuset</h2>
		<hr>
		<nav class="nav-tab-wrapper">
			<a href="?page=akdtu-plugin-mail-fælleshus-vlan&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Indstillinger</a>
			<a href="?page=akdtu-plugin-mail-fælleshus-vlan&tab=test" class="nav-tab <?php if ($tab === 'test') : ?>nav-tab-active<?php endif; ?>">Afprøv</a>
		</nav>
		<?php if ($tab == 'settings') : ?>
			<hr>
			<form method="post" action="">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<input type="hidden" name="action" value="AKDTU_save_fælleshus_vlan_mail_settings" />
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Modtager adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_VLAN_TO" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_VLAN_TO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_VLAN_FROM" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_VLAN_FROM')); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_VLAN_CC" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_VLAN_CC')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_VLAN_REPLYTO" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_VLAN_REPLYTO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_VLAN_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_VLAN_SUBJECT')); ?>" style="width: 300px" />
							</td>
						</tr>
						<tr>
							<th scope="row">Vedhæftede filer</th>
							<td>
								<textarea type="text" name="AKDTU_FÆLLESHUS_VLAN_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_VLAN_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Mail indhold</th>
							<td>
								<textarea name="AKDTU_FÆLLESHUS_VLAN_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_VLAN_MAILCONTENT')); ?></textarea>
								<p><code>#OLDSTATUS</code> erstattes med hvilken status VLAN var i inden ændringen.</p>
								<p><code>#DESIREDSTATUS</code> erstattes med hvilken status VLAN ønskes sat i.</p>
								<p><code>#NEWSTATUS</code> erstattes med hvilken status VLAN er i efter ændringen.</p>
								<p><code>#CURRENTRENTER</code> erstattes med navnet på lejeren af fælleshuset, eller "Ingen" hvis det er ledigt.</p>
								<p><code>#CURRENTRENTALSTATUS</code> erstattes med "lejet af <code>#CURRENTRENTER</code>" hvis fælleshuset er lejet og "ledigt" ellers.</p>
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
			include_once get_home_path() . 'wp-content/plugins/AKDTU/cronjobs/Opdater_fælleshus_VLAN.php';
			send_opdater_fælleshus_vlan(true);
		endif; ?>
	</div>
<?php } ?>