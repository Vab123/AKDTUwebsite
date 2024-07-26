<?php

/**
 * @file Widget to list all currently allowed sign ups from temporary renters
 */

function allowed_renter_signups_widget() {
	# Get all not-finished renters
	$allowed_users = get_current_renters();

	if (count($allowed_users) > 0) : ?>
		<table id='dbem-bookings-table' class='widefat post ' style="max-width:75em">
			<colgroup>
				<col span="1" style="width: 10%" />
				<col span="1" style="width: 35%" />
				<col span="1" style="width: 40%" />
				<col span="1" style="width: 0%" />
			</colgroup>
			<thead>
				<tr>
					<th class='manage-column' scope='col'>Lejl.</th>
					<th class='manage-column' scope='col'>Email</th>
					<th class='manage-column' scope='col'>Periode</th>
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
						<td style="vertical-align:middle"><?php echo $user->email; ?></td>
						<td style="vertical-align:middle"><?php echo (new DateTime($user->start_time))->format('d-m-Y H:i') . ' - ' . (new DateTime($user->end_time))->format('d-m-Y H:i'); ?></td>
						<td style="vertical-align:middle;text-align:center">
							<form action="" method="post">
								<input type="hidden" name="user" value="<?php echo $user->apartment_number; ?>" />
								<input type="hidden" name="email" value="<?php echo $user->email; ?>" />
								<input type="hidden" name="start_time" value="<?php echo $user->start_time; ?>" />
								<input type="hidden" name="end_time" value="<?php echo $user->end_time; ?>" />
								<input type="hidden" name="action" value="delete_renter_signup" />
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
		Ingen tilladte lejeroprettelser.
	<?php endif; ?>
<?php
}

?>