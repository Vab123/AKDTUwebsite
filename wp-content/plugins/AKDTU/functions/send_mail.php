<?php

/**
 * @file Functionality related to the sending of emails from the website to the board, residents, etc.
 */

/**
 * Returns the global file-path to the root of the website document folder.
 * 
 * @return string Global file-path
 */
function website_root_folder()
{
	return '/var/www/akdtu.dk/public_html';
}

/**
 * Creates a string of file-paths from a comma-seperated string of attachments.
 * 
 * Prepends global file-path
 * 
 * @param string $ATTACHMENTS comma-seperated string of attachments, relative to website_root_folder, starting with "/"
 * 
 * @return array[string] Key-value array, with the global path to each file
 */
function prepend_attachments_string($ATTACHMENTS = '')
{
	# Check if the attachment string is empty or not
	if (strlen($ATTACHMENTS) > 0) {
		# Attachment string contains something. Split them up and prepend global path to each element
		return array_map(function ($key) {
			return website_root_folder() . trim($key);
		}, explode(',', $ATTACHMENTS));
	}

	# Attachment string was empty. Return empty array
	return [];
}

/**
 * Echos a table representing the email. Email is NOT sent
 * 
 * @param string $TO Who the email should be sent to
 * @param string $FROM Who the email should be sent from
 * @param string $REPLYTO What the email reply-to address should be
 * @param string $CC Who the email should be sent to, cc
 * @param array[string] $attachments Array of global paths to the files that should be added as an attachment
 * @param string $mailsubject Subject of the email
 * @param string $mailcontent Content of the email
 * 
 * @return void Echos table
 */
function echo_AKDTU_email_as_table($TO, $FROM, $REPLYTO, $CC, $attachments, $mailsubject, $mailcontent)
{
	# Row counter, to make each other row a different color
	$row = 0; ?>
	<table class="widefat" style="margin-top:2em;">
		<colgroup>
			<col span="1" style="width: 10em">
			<col span="1" style="width: auto">
		</colgroup>
		<thead>
			<tr>
				<th>Datapunkt</th>
				<th>Værdi</th>
			</tr>
		</thead>
		<tbody>
			<tr <?php
			$row++;
			echo $row % 2 == 0 ? 'class="alternate"' : '';
			?>>
				<td><b>Til:</b></td>
				<td><?php echo $TO == "" ? "(Ingen)" : htmlentities($TO); ?></td>
			</tr>
			<tr <?php $row++;
			echo $row % 2 == 0 ? 'class="alternate"' : ''; ?>>
				<td><b>Fra:</b></td>
				<td><?php echo htmlentities($FROM); ?></td>
			</tr>
			<?php if ($REPLYTO != ''): ?>
				<tr <?php $row++;
				echo $row % 2 == 0 ? 'class="alternate"' : ''; ?>>
					<td><b>Svar-adresse:</b></td>
					<td><?php echo htmlentities($REPLYTO); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ($CC != ''): ?>
				<tr <?php $row++;
				echo $row % 2 == 0 ? 'class="alternate"' : ''; ?>>
					<td><b>Cc:</b></td>
					<td><?php echo htmlentities($CC); ?></td>
				</tr>
			<?php endif; ?>
			<tr <?php $row++;
			echo $row % 2 == 0 ? 'class="alternate"' : ''; ?>>
				<td><b>Vedhæftede filer:</b></td>
				<td><?php
				if (count($attachments) > 0) {
					for ($i = 0; $i < count($attachments); $i++) {
						echo $attachments[$i] . ' - Fil eksisterer' . (file_exists($attachments[$i]) ? "" : " ikke!") . ($i < count($attachments) - 1 ? "<br>" : "");
					}
				} else {
					echo '(Ingen)';
				}
				?></td>
			</tr>
			<tr <?php $row++;
			echo $row % 2 == 0 ? 'class="alternate"' : '';
			?>>
				<td><b>Emne:</b></td>
				<td><?php echo $mailsubject; ?></td>
			</tr>
			<tr <?php $row++;
			echo $row % 2 == 0 ? 'class="alternate"' : ''; ?>>
				<td><b>Indhold:</b></td>
				<td><?php echo stripcslashes($mailcontent); ?></td>
			</tr>
		</tbody>
	</table>
<?php }

/**
 * Either sends a formatted email or echos it as a table
 * 
 * Performs replaces on the subject and content of the email before sending
 * 
 * @param bool $debug Flag, if the email should be sent (false) or echoed as a table (true)
 * @param string[] $subject_replaces Key-value array of replaces, where the keys should be replaced with the values in the subject of the email
 * @param string[] $content_replaces Key-value array of replaces, where the keys should be replaced with the values in the content of the email
 * @param string $CONSTANT_ROOT Root of the PHP constant, which contains info about the email. Defined in register_options.php and register_settings.php
 * @param bool|string $override_TO If not false, the recipient address of the email is overwritten with the value of this parameter
 */
function send_AKDTU_email($debug = true, $subject_replaces = array(), $content_replaces = array(), $CONSTANT_ROOT = '', $override_TO = false)
{
	$REPLYTO = constant("{$CONSTANT_ROOT}_REPLYTO"); # Reply-To address
	$CC = constant("{$CONSTANT_ROOT}_CC"); # CC address
	$FROM = constant("{$CONSTANT_ROOT}_FROM"); # From address
	$TO = $override_TO ?? constant("{$CONSTANT_ROOT}_TO"); # To address

	# Perform replaces in the subject of the email
	$mailsubject = AKDTU_email_subject($subject_replaces, $CONSTANT_ROOT);

	# Perform replaces in the content of the email
	$mailcontent = AKDTU_email_content($content_replaces, $CONSTANT_ROOT);

	# Prepare attachments
	$attachments = AKDTU_email_attachments($CONSTANT_ROOT);

	# Check if the email should be sent or echoed as a table
	if ($debug) {
		# Echo as a table
		echo_AKDTU_email_as_table($TO, $FROM, $REPLYTO, $CC, $attachments, $mailsubject, $mailcontent);
	} else {
		# Sent email

		# Prepare the correct headers
		$headers = array();
		if ($REPLYTO != '') {
			# Set Reply-to address
			$headers[] = "Reply-to: {$REPLYTO}";
		}
		if ($CC != '') {
			# Set CC address
			$headers[] = "Cc: {$CC}";
		}
		if ($FROM != '') {
			# Set From address
			$headers[] = "From: {$FROM}";
		}

		# Email should be sent as an html-email
		add_filter('wp_mail_content_type', function ($content_type) {
			return 'text/html';
		});

		# Send email
		wp_mail($TO, $mailsubject, $mailcontent, $headers, $attachments);
	}
}

/**
 * Performs replaces on the subject of an email before sending
 * 
 * @param string[] $subject_replaces Key-value array of replaces, where the keys should be replaced with the values in the subject of the email
 * @param string $CONSTANT_ROOT Root of the PHP constant, which contains info about the email. Defined in register_options.php and register_settings.php
 * 
 * @return string Formatted subject of the email
 */
function AKDTU_email_subject($subject_replaces = [], $CONSTANT_ROOT = '')
{
	$SUBJECT = constant("{$CONSTANT_ROOT}_SUBJECT"); # Mail subject

	return count($subject_replaces) > 0 ? str_replace(array_keys($subject_replaces), $subject_replaces, nl2br($SUBJECT)) : nl2br($SUBJECT);
}

/**
 * Performs replaces on the content of the email before sending
 * 
 * @param string[] $content_replaces Key-value array of replaces, where the keys should be replaced with the values in the content of the email
 * @param string $CONSTANT_ROOT Root of the PHP constant, which contains info about the email. Defined in register_options.php and register_settings.php
 * 
 * @return string Formatted content of the email
 */
function AKDTU_email_content($content_replaces = [], $CONSTANT_ROOT = '')
{
	$MAILCONTENT = stripcslashes(constant("{$CONSTANT_ROOT}_MAILCONTENT")); # Mail content, strip slashes

	return count($content_replaces) > 0 ? str_replace(array_keys($content_replaces), $content_replaces, nl2br($MAILCONTENT)) : nl2br($MAILCONTENT);
}

/**
 * Performs replaces on the attachments of the email before sending
 * 
 * @param string $CONSTANT_ROOT Root of the PHP constant, which contains info about the email. Defined in register_options.php and register_settings.php
 * 
 * @return array[string] Array of paths to attachments for the email
 */
function AKDTU_email_attachments($CONSTANT_ROOT = '')
{
	$ATTACHMENTS = constant("{$CONSTANT_ROOT}_ATTACHMENTS"); # Attachment string

	return prepend_attachments_string($ATTACHMENTS);
}
