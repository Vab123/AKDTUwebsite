<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'AKDTU_save_havedag_mail_settings') {
		add_action('admin_menu', 'AKDTU_save_havedag_mail_settings');
	}
}

function AKDTU_save_havedag_mail_settings() {
	update_option('AKDTU_HAVEDAG_DAYS', stripcslashes($_REQUEST['AKDTU_HAVEDAG_DAYS']));
	update_option('AKDTU_HAVEDAG_TO', stripcslashes($_REQUEST['AKDTU_HAVEDAG_TO']));
	update_option('AKDTU_HAVEDAG_FROM', stripcslashes($_REQUEST['AKDTU_HAVEDAG_FROM']));
	update_option('AKDTU_HAVEDAG_CC', stripcslashes($_REQUEST['AKDTU_HAVEDAG_CC']));
	update_option('AKDTU_HAVEDAG_REPLYTO', stripcslashes($_REQUEST['AKDTU_HAVEDAG_REPLYTO']));
	update_option('AKDTU_HAVEDAG_SUBJECT', stripcslashes($_REQUEST['AKDTU_HAVEDAG_SUBJECT']));
	update_option('AKDTU_HAVEDAG_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_HAVEDAG_MAILCONTENT']));
	update_option('AKDTU_HAVEDAG_FORMAT', stripcslashes($_REQUEST['AKDTU_HAVEDAG_FORMAT']));
	update_option('AKDTU_HAVEDAG_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_HAVEDAG_ATTACHMENTS']));

	update_option('AKDTU_HAVEDAG_WARNING_DAYS', stripcslashes($_REQUEST['AKDTU_HAVEDAG_WARNING_DAYS']));
	update_option('AKDTU_HAVEDAG_WARNING_TO', stripcslashes($_REQUEST['AKDTU_HAVEDAG_WARNING_TO']));
	update_option('AKDTU_HAVEDAG_WARNING_FROM', stripcslashes($_REQUEST['AKDTU_HAVEDAG_WARNING_FROM']));
	update_option('AKDTU_HAVEDAG_WARNING_CC', stripcslashes($_REQUEST['AKDTU_HAVEDAG_WARNING_CC']));
	update_option('AKDTU_HAVEDAG_WARNING_REPLYTO', stripcslashes($_REQUEST['AKDTU_HAVEDAG_WARNING_REPLYTO']));
	update_option('AKDTU_HAVEDAG_WARNING_SUBJECT', stripcslashes($_REQUEST['AKDTU_HAVEDAG_WARNING_SUBJECT']));
	update_option('AKDTU_HAVEDAG_WARNING_MAILCONTENT', stripcslashes($_REQUEST['AKDTU_HAVEDAG_WARNING_MAILCONTENT']));
	update_option('AKDTU_HAVEDAG_WARNING_FORMAT', stripcslashes($_REQUEST['AKDTU_HAVEDAG_WARNING_FORMAT']));
	update_option('AKDTU_HAVEDAG_WARNING_ATTACHMENTS', stripcslashes($_REQUEST['AKDTU_HAVEDAG_WARNING_ATTACHMENTS']));

	new AKDTU_notice('success', 'Indstillingerne blev gemt');
}

function AKDTU_havedag_opkrævning_mail_settings() {
	$default_tab = 'settings';
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
	<div class="wrap">
		<!-- Print the page title -->
		<h1>Mailindstillinger</h1>
		<h2>Mail der sendes med opkrævninger for leje af havedaget</h2>
		<hr>
		<nav class="nav-tab-wrapper">
			<a href="?page=akdtu-plugin-havedag-opkrævning-settings&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>">Indstillinger</a>
			<a href="?page=akdtu-plugin-havedag-opkrævning-settings&tab=test" class="nav-tab <?php if ($tab === 'test') : ?>nav-tab-active<?php endif; ?>">Afprøv</a>
		</nav>
		<?php if ($tab == 'settings') : ?>
			<hr>
			<form method="post" action="">
				<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
				<input type="hidden" name="action" value="AKDTU_save_havedag_mail_settings" />
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Opkrævningsmail - Afsendelsestidspunkt (dage efter sidste havedag)</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_DAYS" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_DAYS')); ?>" style="width: 300px" />
								<p>Antallet af dage efter sidste havedag, hvor der skal sendes en opkrævningsmail. Minimum er <code>1</code>. Skriv <code>-1</code> for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Opkrævningsmail - Modtager adresse</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_TO" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_TO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Opkrævningsmail - Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_FROM" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_FROM')); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Opkrævningsmail - Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_CC" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_CC')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Opkrævningsmail - Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_REPLYTO" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_REPLYTO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Opkrævningsmail - Emne</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_SUBJECT')); ?>" style="width: 300px" />
								<p><code>#SEASON</code> erstattes med <code>"forår"</code> eller <code>"efterår"</code>.</p>
								<p><code>#YEAR</code> erstattes med året.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Opkrævningsmail - Vedhæftede filer</th>
							<td>
								<textarea type="text" name="AKDTU_HAVEDAG_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_HAVEDAG_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Opkrævningsmail - Mail indhold</th>
							<td>
								<textarea name="AKDTU_HAVEDAG_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_HAVEDAG_MAILCONTENT')); ?></textarea>
								<p><code>#PAYMENT_INFO</code> erstattes med info omkring hvem der skal betale for ikke at have mødt op til havedagene.</p>
								<p><code>#SEASON</code> erstattes med <code>"forår"</code> eller <code>"efterår"</code>.</p>
								<p><code>#YEAR</code> erstattes med år.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Opkrævningsmail - Format for info om betaling</th>
							<td>
								<textarea type="text" name="AKDTU_HAVEDAG_FORMAT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_HAVEDAG_FORMAT')); ?></textarea>
								<p>Dette gentages for hver lejlighed, der har lejet havedaget den foregående måned, med linjeskift mellem.</p>
								<p><code>#APT</code> erstattes med lejlighedsnummer. Hvis der efterfølgende er flyttet en ny beboer ind står dette også herefter som <code>(Tidligere beboer)</code>, hvis relevant.</p>
								<p><code>#PRICE</code> erstattes med pris.</p>
							</td>
						</tr>
						<tr>
							<th></th>
							<td><input type="submit" class="button-primary" value="Gem (Alt)" /></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Varselsmail - Afsendelsestidspunkt (dage før opkrævningsmail)</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_WARNING_DAYS" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_WARNING_DAYS')); ?>" style="width: 300px" />
								<p>Antallet af dage før opkrævningsmailen (ovenfor), der skal sendes en varselsmail. Minimum er <code>0</code>. Skriv <code>-1</code> for ikke at sende nogen mail</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Varselsmail - Modtager adresse</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_WARNING_TO" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_WARNING_TO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sende nogen mail</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Varselsmail - Afsender adresse</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_WARNING_FROM" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_WARNING_FROM')); ?>" style="width: 300px" />
								<p>Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code></p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Varselsmail - Cc adresse</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_WARNING_CC" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_WARNING_CC')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen cc</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Varselsmail - Svaradresse</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_WARNING_REPLYTO" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_WARNING_REPLYTO')); ?>" style="width: 300px" />
								<p>Efterlad tom for ikke at sætte nogen svaradresse</p>
								<p>Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Varselsmail - Emne</th>
							<td>
								<input type="text" name="AKDTU_HAVEDAG_WARNING_SUBJECT" value="<?php echo stripcslashes(get_option('AKDTU_HAVEDAG_WARNING_SUBJECT')); ?>" style="width: 300px" />
								<p><code>#SEASON</code> erstattes med <code>"forår"</code> eller <code>"efterår"</code>.</p>
								<p><code>#YEAR</code> erstattes med året.</p>
								<p><code>#DAYS</code> erstattes med antal dage der går mellem afsendelse af varselsmail og opkrævningsmail.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Varselsmail - Vedhæftede filer</th>
							<td>
								<textarea type="text" name="AKDTU_HAVEDAG_WARNING_ATTACHMENTS" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_HAVEDAG_WARNING_ATTACHMENTS')); ?></textarea>
								<p>Stinavn, relativt til <code>/var/www/akdtu.dk/public_html</code>. Skal starte med <code>/</code></p>
								<p>Flere vedhæftede filer adskilles med <code>,</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row">Varselsmail - Mail indhold</th>
							<td>
								<textarea name="AKDTU_HAVEDAG_WARNING_MAILCONTENT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_HAVEDAG_WARNING_MAILCONTENT')); ?></textarea>
								<p><code>#PAYMENT_INFO</code> erstattes med info omkring hvem der skal betale for ikke at have mødt op til havedagene.</p>
								<p><code>#SEASON</code> erstattes med <code>"forår"</code> eller <code>"efterår"</code>.</p>
								<p><code>#YEAR</code> erstattes med år.</p>
								<p><code>#DAYS</code> erstattes med antal dage der går mellem afsendelse af varselsmail og opkrævningsmail.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Varselsmail - Format for info om betaling</th>
							<td>
								<textarea type="text" name="AKDTU_HAVEDAG_WARNING_FORMAT" rows="5" , cols="50"><?php echo stripcslashes(get_option('AKDTU_HAVEDAG_WARNING_FORMAT')); ?></textarea>
								<p>Dette gentages for hver lejlighed, der har lejet havedaget den foregående måned, med linjeskift mellem.</p>
								<p><code>#APT</code> erstattes med lejlighedsnummer. Hvis der efterfølgende er flyttet en ny beboer ind står dette også herefter som <code>(Tidligere beboer)</code>, hvis relevant.</p>
								<p><code>#PRICE</code> erstattes med pris.</p>
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
			include_once get_home_path() . 'wp-content/plugins/AKDTU/cronjobs/Opkrævning_havedag.php';
			send_opkrævning_havedag(true);
		endif; ?>
	</div>
<?php } ?>