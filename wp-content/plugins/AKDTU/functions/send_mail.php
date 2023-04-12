<?php

function prepend_attachments_string($ATTACHMENTS = '') {
	if (strlen($ATTACHMENTS) > 0) {
		$attachments = explode(',', $ATTACHMENTS);
		$attachments = array_map(function ($key) {
			return '/var/www/akdtu.dk/public_html' . trim($key);
		}, $attachments);
	} else {
		$attachments = array();
	}

	return $attachments;
}

function echo_AKDTU_email_as_table($TO, $FROM, $REPLYTO, $CC, $attachments, $mailsubject, $mailcontent) {
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
				echo ($row % 2 == 0 ? 'class="alternate"' : '');
				?>>
				<td><b>Til:</b></td>
				<td><?php echo htmlentities($TO); ?></td>
			</tr>
			<tr <?php $row++;
				echo ($row % 2 == 0 ? 'class="alternate"' : ''); ?>>
				<td><b>Fra:</b></td>
				<td><?php echo htmlentities($FROM); ?></td>
			</tr>
			<?php if ($REPLYTO != '') : ?>
				<tr <?php $row++;
					echo ($row % 2 == 0 ? 'class="alternate"' : ''); ?>>
					<td><b>Svar-adresse:</b></td>
					<td><?php echo htmlentities($REPLYTO); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ($CC != '') : ?>
				<tr <?php $row++;
					echo ($row % 2 == 0 ? 'class="alternate"' : ''); ?>>
					<td><b>Cc:</b></td>
					<td><?php echo htmlentities($CC); ?></td>
				</tr>
			<?php endif; ?>
			<tr <?php $row++;
				echo ($row % 2 == 0 ? 'class="alternate"' : ''); ?>>
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
				echo ($row % 2 == 0 ? 'class="alternate"' : '');
				?>>
				<td><b>Emne:</b></td>
				<td><?php echo $mailsubject; ?></td>
			</tr>
			<tr <?php $row++;
				echo ($row % 2 == 0 ? 'class="alternate"' : ''); ?>>
				<td><b>Indhold:</b></td>
				<td><?php echo stripcslashes($mailcontent); ?></td>
			</tr>
		</tbody>
	</table>
<?php }

function send_AKDTU_email($debug = true, $subject_replaces = array(), $content_replaces = array(), $CONSTANT_ROOT = '', $override_TO = false) {
	$SUBJECT = constant($CONSTANT_ROOT . '_SUBJECT'); # Mail subject
	$MAILCONTENT = stripcslashes(constant($CONSTANT_ROOT . '_MAILCONTENT')); # Mail content, strip slashes
	$REPLYTO = constant($CONSTANT_ROOT . '_REPLYTO'); # Reply-To address
	$CC = constant($CONSTANT_ROOT . '_CC'); # CC address
	$FROM = constant($CONSTANT_ROOT . '_FROM'); # From address
	$TO = ($override_TO ? $override_TO : constant($CONSTANT_ROOT . '_TO')); # To address
	$ATTACHMENTS = constant($CONSTANT_ROOT . '_ATTACHMENTS');

	$mailsubject = (count($subject_replaces) > 0 ? str_replace(array_keys($subject_replaces), $subject_replaces, nl2br($SUBJECT)) : nl2br($SUBJECT));

	$mailcontent = (count($content_replaces) > 0 ? str_replace(array_keys($content_replaces), $content_replaces, nl2br($MAILCONTENT)) : nl2br($MAILCONTENT));

	$headers = array();
	if ($REPLYTO != '') {
		$headers[] = 'Reply-to: ' . $REPLYTO;
	}
	if ($CC != '') {
		$headers[] = 'Cc: ' . $CC;
	}
	if ($FROM != '') {
		$headers[] = 'From: ' . $FROM;
	}

	$attachments = prepend_attachments_string($ATTACHMENTS);

	if ($debug) {
		echo_AKDTU_email_as_table($TO, $FROM, $REPLYTO, $CC, $attachments, $mailsubject, $mailcontent);
	} else {
		add_filter('wp_mail_content_type', function ($content_type) {
			return 'text/html';
		});
		wp_mail($TO, $mailsubject, $mailcontent, $headers, $attachments);
	}
}
