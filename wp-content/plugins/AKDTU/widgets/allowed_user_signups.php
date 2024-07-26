<?php

/**
 * @file Widget to list all currently allowed sign ups from new residents
 */

function allowed_user_signups_widget() {
	$allowed_users = get_current_user_permits(['email', 'apartment_number', 'allow_creation_date']);

	if (count($allowed_users) > 0) : ?>
		<table id='dbem-bookings-table' class='widefat post ' style="max-width:75em">
			<colgroup>
				<col span="1" style="width: 10%" />
				<col span="1" style="width: 62%" />
				<col span="1" style="width: 28%" />
				<col span="1" style="width: 0%" />
			</colgroup>
			<thead>
				<tr>
					<th class='manage-column' scope='col'>Lejl.</th>
					<th class='manage-column' scope='col'>Email</th>
					<th class='manage-column' scope='col'>Tilladt fra</th>
					<th class='manage-column' scope='col'></th>
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
						<td style="vertical-align:middle; word-break: break-word;"><?php echo $user->email; ?></td>
						<td style="vertical-align:middle"><?php echo (new DateTime($user->allow_creation_date))->format('d-m-Y H:i'); ?></td>
						<td style="vertical-align:middle;text-align:center">
							<form action="" method="post">
								<input type="hidden" name="user" value="<?php echo $user->apartment_number; ?>" />
								<input type="hidden" name="email" value="<?php echo $user->email; ?>" />
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