<?php

# Add action to save changed settings
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'AKDTU_save_brugeradgang_mail_settings') {
		add_action('admin_menu', 'AKDTU_save_brugeradgang_mail_settings');
	}
}

function AKDTU_save_brugeradgang_mail_settings() {
	# Save form info
	update_option('AKDTU_FJERNBRUGERADGANG_TO', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_TO']));
	update_option('AKDTU_FJERNBRUGERADGANG_FROM', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_FROM']));
	update_option('AKDTU_FJERNBRUGERADGANG_CC', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_CC']));
	update_option('AKDTU_FJERNBRUGERADGANG_REPLYTO', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_REPLYTO']));
	update_option('AKDTU_FJERNBRUGERADGANG_SUBJECT', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_SUBJECT']));
	update_option('AKDTU_FJERNBRUGERADGANG_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_MAILCONTENT']));
	update_option('AKDTU_FJERNBRUGERADGANG_FORMAT_RENTALS', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_FORMAT_RENTALS']));
	update_option('AKDTU_FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS']));
	update_option('AKDTU_FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS']));
	update_option('AKDTU_FJERNBRUGERADGANG_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_FJERNBRUGERADGANG_ATTACHMENTS']));

	# Form info saved. Write success message to admin interface
	new AKDTU_notice('success', 'Indstillingerne blev gemt');
}

# Write settings interface
function AKDTU_brugeradgang_mail_settings() {
	$default_tab = 'settings';
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
	<div class="wrap">
		<!-- Print the page title -->
		<h1>Mailindstillinger</h1>
		<h2>Mail der sendes når adgang fjernes til en beboer, der er flyttet</h2>
		<hr>
		<nav class="nav-tab-wrapper">
			<a href="?page=akdtu-plugin-mail-bruger-adgang&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Indstillinger</a>
			<a href="?page=akdtu-plugin-mail-bruger-adgang&tab=test" class="nav-tab <?php if ($tab === 'test') : ?>nav-tab-active<?php endif; ?>">Afprøv</a>
		</nav>
		<?php if ($tab == 'settings') : ?>
			<hr>
			<form method="post" action="">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<input type="hidden" name="action" value="AKDTU_save_brugeradgang_mail_settings" />
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Modtager adresse</th>
							<td>
								<input type="text" name="AKDTU_FJERNBRUGERADGANG_TO" value="<?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_TO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_FJERNBRUGERADGANG_FROM" value="<?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_FROM')); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_FJERNBRUGERADGANG_CC" value="<?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_CC')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_FJERNBRUGERADGANG_REPLYTO" value="<?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_REPLYTO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="AKDTU_FJERNBRUGERADGANG_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_SUBJECT')); ?>" style="width: 300px" />
								<p><code>#APT</code> erstattes med lejlighedsnummeret</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Vedhæftede filer</th>
							<td>
								<textarea type="text" name="AKDTU_FJERNBRUGERADGANG_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_ATTACHMENTS')); ?></textarea>
								<p>Skal være relativt til <code><?php echo website_root_folder(); ?></code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Mail indhold</th>
							<td>
								<textarea name="AKDTU_FJERNBRUGERADGANG_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_MAILCONTENT')); ?></textarea>
								<p><code>#APT</code> erstattes med lejlighedsnummeret.</p>
								<p><code>#NEWMAIL</code> erstattes med brugerens nye emailadresse.</p>
								<p><code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.</p>
								<p><code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.</p>
								<p><code>#OLDMAIL</code> erstattes med brugerens gamle emailadresse.</p>
								<p><code>#OLDFIRSTNAME</code> erstattes med brugerens gamle fornavn.</p>
								<p><code>#OLDLASTNAME</code> erstattes med brugerens gamle efternavn.</p>
								<p><code>#RENTALS</code> erstattes med info omkring udlejninger af fælleshus.</p>
								<p><code>#PREVIOUS_GARDENDAYS</code> erstattes med info om tidligere havedage.</p>
								<p><code>#FUTURE_GARDENDAYS</code> erstattes med info om fremtidige havedage.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Format for info omkring udlejninger af fælleshus</th>
							<td>
								<textarea type="text" name="AKDTU_FJERNBRUGERADGANG_FORMAT_RENTALS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_FORMAT_RENTALS')); ?></textarea>
								<p>Dette gentages for hver leje af fælleshuset, brugeren har foretaget, med linjeskift mellem.</p>
								<p><code>#NAME</code> erstattes med navnet på begivenheden.</p>
								<p><code>#START_DATE_SECOND</code> erstattes med sekundet for starten på begivenheden.</p>
								<p><code>#START_DATE_MINUTE</code> erstattes med minuttet for starten på begivenheden.</p>
								<p><code>#START_DATE_HOUR</code> erstattes med timetallet for starten på begivenheden.</p>
								<p><code>#START_DATE_DAY</code> erstattes med dagen for starten på begivenheden.</p>
								<p><code>#START_DATE_MONTH</code> erstattes med måneden for starten på begivenheden.</p>
								<p><code>#START_DATE_YEAR</code> erstattes med året for starten på begivenheden.</p>
								<p><code>#END_DATE_SECOND</code> erstattes med sekundet for slutningen på begivenheden.</p>
								<p><code>#END_DATE_MINUTE</code> erstattes med minuttet for slutningen på begivenheden.</p>
								<p><code>#END_DATE_HOUR</code> erstattes med timetallet for slutningen på begivenheden.</p>
								<p><code>#END_DATE_DAY</code> erstattes med dagen for slutningen på begivenheden.</p>
								<p><code>#END_DATE_MONTH</code> erstattes med måneden for slutningen på begivenheden.</p>
								<p><code>#END_DATE_YEAR</code> erstattes med året for slutningen på begivenheden.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Format for info omkring tidligere havedage</th>
							<td>
								<textarea type="text" name="AKDTU_FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS')); ?></textarea>
								<p>Dette gentages for hver fremtidig havedag, brugeren er tilmeldt, med linjeskift mellem.</p>
								<p><code>#NAME</code> erstattes med navnet på havedagen.</p>
								<p><code>#DATE_SECOND</code> erstattes med sekundet for havedagen.</p>
								<p><code>#DATE_MINUTE</code> erstattes med minuttet for havedagen.</p>
								<p><code>#DATE_HOUR</code> erstattes med timen for havedagen.</p>
								<p><code>#DATE_DAY</code> erstattes med dagen for havedagen.</p>
								<p><code>#DATE_MONTH</code> erstattes med måneden for havedagen.</p>
								<p><code>#DATE_YEAR</code> erstattes med året for havedagen.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Format for info omkring fremtidige havedage</th>
							<td>
								<textarea type="text" name="AKDTU_FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS')); ?></textarea>
								<p>Dette gentages for hver fremtidig havedag, brugeren er tilmeldt, med linjeskift mellem.</p>
								<p><code>#NAME</code> erstattes med navnet på havedagen.</p>
								<p><code>#DATE_SECOND</code> erstattes med sekundet for havedagen.</p>
								<p><code>#DATE_MINUTE</code> erstattes med minuttet for havedagen.</p>
								<p><code>#DATE_HOUR</code> erstattes med timen for havedagen.</p>
								<p><code>#DATE_DAY</code> erstattes med dagen for havedagen.</p>
								<p><code>#DATE_MONTH</code> erstattes med måneden for havedagen.</p>
								<p><code>#DATE_YEAR</code> erstattes med året for havedagen.</p>
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
			include_once get_home_path() . 'wp-content/plugins/AKDTU/cronjobs/Fjern_brugeradgang.php';
			send_fjern_brugeradgang(true);
		endif; ?>
	</div>
<?php } ?>