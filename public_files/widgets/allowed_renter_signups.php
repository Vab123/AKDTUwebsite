<?php

function allowed_renter_signups_widget(){
	global $wpdb;

	$query = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'swpm_allowed_rentercreation ORDER BY start_time ASC, apartment_number ASC');
	$allowed_users = $wpdb->get_results($query);

	if( count($allowed_users) > 0 ): ?>
	<table id='dbem-bookings-table' class='widefat post ' style="max-width:75em">
		<colgroup>
			<col span="1" style="width: 10%" />
			<col span="1" style="width: 20%" />
			<col span="1" style="width: 35%" />
			<col span="1" style="width: 35%" />
		</colgroup>
		<thead>
			<tr>
				<th class='manage-column' scope='col'>Lejl.</th>
				<th class='manage-column' scope='col'>Telefon</th>
				<th class='manage-column' scope='col'>Start</th>
				<th class='manage-column' scope='col'>Slut</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$row = 0;
			$event_count = (!empty($event_count)) ? $event_count:0;
			foreach ($allowed_users as $user) {
				?>
				<tr <?php if ($row % 2 == 0){echo 'class="alternate"';}; $row++; ?>>
					<td style="vertical-align:middle"><?php echo $user->apartment_number; ?></td>
					<td style="vertical-align:middle"><?php echo $user->phone_number; ?></td>
					<td style="vertical-align:middle"><?php echo ($user->has_been_reset == 1 ? 'Nu' : (new DateTime($user->start_time))->format('d-m-Y H:i')); ?></td>
					<td style="vertical-align:middle"><?php echo ($user->has_been_reset == 1 ? 'Nu' : (new DateTime($user->end_time))->format('d-m-Y H:i')); ?></td>
					<!-- <td style="vertical-align:middle;text-align:center"><form action="" method="post">
						<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
						<input type="hidden" name="user" value="<?php echo $user->apartment_number; ?>" />
						<input type="hidden" name="phone" value="<?php echo $user->phone_number; ?>" />
						<input type="hidden" name="start_time" value="<?php echo $user->start_time; ?>" />
						<input type="hidden" name="end_time" value="<?php echo $user->end_time; ?>" />
						<input type="hidden" name="action" value="delete_renter_signup" />
						<input type="submit" class="button-secondary" value="Slet">
					</form></td> -->
				</tr>
				<?php
				}
			?>
		</tbody>
	</table>
	<?php else: ?>
		Ingen tilladte brugeroprettelser.
	<?php endif; ?>
<?php
}

?>