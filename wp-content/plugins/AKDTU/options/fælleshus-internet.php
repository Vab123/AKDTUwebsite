<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'AKDTU_save_fælleshus_internet_mail_settings') {
		add_action('admin_menu', 'AKDTU_save_fælleshus_internet_mail_settings');
	}
}

function AKDTU_save_fælleshus_internet_mail_settings() {
	update_option('AKDTU_FÆLLESHUS_INTERNET_TO', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_TO']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_FROM', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_FROM']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_CC', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_CC']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_REPLYTO', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_REPLYTO']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_SUBJECT', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_SUBJECT']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_MAILCONTENT']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_ATTACHMENTS']));

	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_FROM', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_FROM']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_CC', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_CC']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_REPLYTO', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_REPLYTO']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_SUBJECT', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_SUBJECT']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_MAILCONTENT']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_ATTACHMENTS']));
	
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_TOGGLE', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_TOGGLE']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_FROM', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_FROM']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_CC', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_CC']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_REPLYTO', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_REPLYTO']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_SUBJECT', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_SUBJECT']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_MAILCONTENT']));
	update_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_ATTACHMENTS']));

	new AKDTU_notice('success', 'Indstillingerne blev gemt');
}

function AKDTU_fælleshus_internet_mail_settings() {
	$default_tab = 'settings';
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
	<div class="wrap">
		<!-- Print the page title -->
		<h1>Mailindstillinger</h1>
		<h2>Mail der sendes når adgangskoden til internettet i fælleshuset ændres</h2>
		<hr>
		<nav class="nav-tab-wrapper">
			<a href="?page=akdtu-plugin-mail-fælleshus-internet&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Indstillinger</a>
			<a href="?page=akdtu-plugin-mail-fælleshus-internet&tab=test" class="nav-tab <?php if ($tab === 'test') : ?>nav-tab-active<?php endif; ?>">Afprøv</a>
		</nav>
		<?php if ($tab == 'settings') : ?>
			<hr>
			<form method="post" action="">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<input type="hidden" name="action" value="AKDTU_save_fælleshus_internet_mail_settings" />
				<h3>Mail til netgruppen</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Modtager adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_TO" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_TO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_FROM" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_FROM')); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_CC" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_CC')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_REPLYTO" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_REPLYTO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_SUBJECT')); ?>" style="width: 300px" />
							</td>
						</tr>
						<tr>
							<th scope="row">Vedhæftede filer</th>
							<td>
								<textarea type="text" name="AKDTU_FÆLLESHUS_INTERNET_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Mail indhold</th>
							<td>
								<textarea name="AKDTU_FÆLLESHUS_INTERNET_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_MAILCONTENT')); ?></textarea>
								<p><code>#RENTER</code> erstattes med info om hvem fælleshuset er lejet af.</p>
								<p><code>#SSID</code> erstattes med navnet på internetforbindelsen.</p>
								<p><code>#NEWPASS</code> erstattes med adgangskoden til internetforbindelsen.</p>
							</td>
						</tr>
						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem (Alt)" /></td>
						</tr>
					</tbody>
				</table>

				<hr>
				<h3>Mail til lejer, dansk</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Modtager adresse</th>
							<td>
								<input type="checkbox" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE" <?php if (get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE')) {
																								echo " checked";
																							}; ?> />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_FROM" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_FROM')); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_CC" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_CC')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_REPLYTO" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_REPLYTO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_SUBJECT')); ?>" style="width: 300px" />
							</td>
						</tr>
						<tr>
							<th scope="row">Vedhæftede filer</th>
							<td>
								<textarea type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Mail indhold</th>
							<td>
								<textarea name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_MAILCONTENT')); ?></textarea>
								<p><code>#RENTALSTARTDATETIME</code> erstattes med start-dato på lejeperioden.</p>
								<p><code>#RENTALENDDATETIME</code> erstattes med slut-dato på lejeperioden.</p>
								<p><code>#RENTALSTARTDATETIME</code> erstattes med start-tidpunkt på lejeperioden.</p>
								<p><code>#RENTALENDDATETIME</code> erstattes med slut-tidpunkt på lejeperioden.</p>
								<p><code>#SSID</code> erstattes med navnet på internetforbindelsen.</p>
								<p><code>#NEWPASS</code> erstattes med adgangskoden til internetforbindelsen.</p>
								<p><code>#FIRSTNAME</code> erstattes med lejerens fornavn.</p>
								<p><code>#LASTNAME</code> erstattes med lejerens efternavn.</p>
							</td>
						</tr>
						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem (Alt)" /></td>
						</tr>
					</tbody>
				</table>

				<hr>
				<h3>Mail til lejer, engelsk</h3>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Modtager adresse</th>
							<td>
								<input type="checkbox" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_TOGGLE" <?php if (get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_TOGGLE')) {
																								echo " checked";
																							}; ?> />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_FROM" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_FROM')); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_CC" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_CC')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_REPLYTO" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_REPLYTO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Emne</th>
							<td>
								<input type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_SUBJECT')); ?>" style="width: 300px" />
							</td>
						</tr>
						<tr>
							<th scope="row">Vedhæftede filer</th>
							<td>
								<textarea type="text" name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Mail indhold</th>
							<td>
								<textarea name="AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_MAILCONTENT')); ?></textarea>
								<p><code>#RENTALSTARTDATETIME</code> erstattes med start-dato på lejeperioden.</p>
								<p><code>#RENTALENDDATETIME</code> erstattes med slut-dato på lejeperioden.</p>
								<p><code>#RENTALSTARTDATETIME</code> erstattes med start-tidpunkt på lejeperioden.</p>
								<p><code>#RENTALENDDATETIME</code> erstattes med slut-tidpunkt på lejeperioden.</p>
								<p><code>#SSID</code> erstattes med navnet på internetforbindelsen.</p>
								<p><code>#NEWPASS</code> erstattes med adgangskoden til internetforbindelsen.</p>
								<p><code>#FIRSTNAME</code> erstattes med lejerens fornavn.</p>
								<p><code>#LASTNAME</code> erstattes med lejerens efternavn.</p>
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
			include_once get_home_path() . 'wp-content/plugins/AKDTU/cronjobs/Opdater_fælleshus_internet.php';
			send_opdater_fælleshus_internet(true);
		endif; ?>
	</div>
<?php } ?>