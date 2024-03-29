<?php

/**
 * @file Widget to list all currently allowed sign ups from new residents
 */

function allowed_user_signups_widget() {
	global $wpdb;

	$query = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'swpm_allowed_membercreation WHERE initial_takeover = 0 ORDER BY allow_creation_date ASC, apartment_number ASC');
	$allowed_users = $wpdb->get_results($query);

	if (count($allowed_users) > 0) : ?>
		<table id='dbem-bookings-table' class='widefat post ' style="max-width:75em">
			<colgroup>
				<col span="1" style="width: 10%" />
				<col span="1" style="width: 15%" />
				<col span="1" style="width: 40%" />
				<col span="1" style="width: 20%" />
				<col span="1" style="width: 15%" />
			</colgroup>
			<thead>
				<tr>
					<th class='manage-column' scope='col'>Lejl.</th>
					<th class='manage-column' scope='col'>Telefon</th>
					<th class='manage-column' scope='col'>Tilladt fra</th>
					<th class='manage-column' scope='col'>Klargjort</th>
					<th class='manage-column' scope='col'>Handlinger</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$row = 0;
				$event_count = (!empty($event_count)) ? $event_count : 0;
				foreach ($allowed_users as $user) {
				?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						};
						$row++; ?>>
						<td style="vertical-align:middle"><?php echo padded_apartment_number_from_apartment_number($user->apartment_number); ?></td>
						<td style="vertical-align:middle"><?php echo $user->phone_number; ?></td>
						<td style="vertical-align:middle"><?php echo (new DateTime($user->allow_creation_date))->format('d-m-Y H:i'); ?></td>
						<td style="vertical-align:middle"><?php echo ($user->initial_reset == 1 ? "Ja" : "Nej"); ?></td>
						<td style="vertical-align:middle;text-align:center">
							<form action="" method="post">
								<input type="hidden" name="user" value="<?php echo $user->apartment_number; ?>" />
								<input type="hidden" name="phone" value="<?php echo $user->phone_number; ?>" />
								<input type="hidden" name="takeover_time" value="<?php echo $user->allow_creation_date; ?>" />
								<input type="hidden" name="action" value="delete_user_signup" />
								<input type="submit" class="button-secondary" value="Slet">
							</form>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	<?php else : ?>
		Ingen tilladte brugeroprettelser.
	<?php endif; ?>
<?php
}

?>