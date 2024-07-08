<?php

/**
 * @file Widget to display all current networkgroup members
 */

function show_networkgroup_widget() {
	$networkgroup_members = all_networkgroup_ids();

	if (count($networkgroup_members) > 0) : ?>
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 10%" />
				<col span="1" style="width: 45%" />
				<col span="1" style="width: 45%" />
				<col span="1" style="width: 0%" />
			</colgroup>
			<thead>
				<tr>
					<th>Lejl.</th>
					<th>Navn</th>
					<th>Type</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;
				foreach ($networkgroup_members as $networkgroup_member_id) : ?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						};
						$row++; ?>>
						<td style="vertical-align:middle"><?php echo padded_apartment_number_from_id($networkgroup_member_id); ?></td>
						<td style="vertical-align:middle"><?php echo name_from_id($networkgroup_member_id); ?></td>
						<td style="vertical-align:middle"><?php echo KNet_type_name_from_id($networkgroup_member_id, new DateTime('now', new DateTimeZone('Europe/Copenhagen'))); ?></td>
						<td style="vertical-align:middle">
							<form action="" method="post" style="text-align:center">
								<input type="hidden" name="user" value="<?php echo $networkgroup_member_id; ?>" />
								<input type="hidden" name="action" value="remove_networkgroupmember" />
								<input type="submit" class="button-secondary" value="Fjern">
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		Ingen bestyrelsesmedlemmer
<?php endif;
}

?>