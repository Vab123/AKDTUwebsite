<?php

/**
 * @file Widget to display all current board members
 */

function show_board_widget() {
	$board_members = array_map(function($username) { return SwpmMemberUtils::get_user_by_user_name($username);}, all_boardmember_usernames());
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	if (count($board_members) > 0) : ?>
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 20%" />
				<col span="1" style="width: 30%" />
				<col span="1" style="width: 30%" />
				<col span="1" style="width: 0%" />
			</colgroup>
			<thead>
				<tr>
					<th>Lejlighed</th>
					<th>Navn</th>
					<th>Type</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;
				foreach ($board_members as $board_member) : ?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						};
						$row++; ?>>
						<td style="vertical-align:middle"><?php echo padded_apartment_number_from_username($board_member->user_name); ?></td>
						<td style="vertical-align:middle"><?php echo $board_member->first_name . ' '  . $board_member->last_name; ?></td>
						<td style="vertical-align:middle"><?php echo user_type_name_from_apartment_number(apartment_number_from_username($board_member->user_name), $now); ?></td>
						<td>
							<form action="" method="post" style="text-align:center">
								<input type="hidden" name="user" value="<?php echo $board_member->member_id; ?>" />
								<input type="hidden" name="action" value="remove_boardmember" />
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