<?php

/**
 * @file Widget to display all current board deputies
 */

function show_deputies_widget() {
	$board_deputies = array_map(function($username) { return SwpmMemberUtils::get_user_by_user_name($username);}, all_board_deputies_usernames());

	if (count($board_deputies) > 0) : ?>
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 20%" />
				<col span="1" style="width: 60%" />
				<col span="1" style="width: 0%" />
			</colgroup>
			<thead>
				<tr>
					<th>Lejlighed</th>
					<th>Navn</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;
				foreach ($board_deputies as $board_deputy) : ?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						};
						$row++; ?>>
						<td style="vertical-align:middle"><?php echo padded_apartment_number_from_username($board_deputy->user_name); ?></td>
						<td style="vertical-align:middle"><?php echo $board_deputy->first_name . ' '  . $board_deputy->last_name; ?></td>
						<td>
							<form action="" method="post" style="text-align:center">
								<input type="hidden" name="user" value="<?php echo $board_deputy->member_id; ?>" />
								<input type="hidden" name="action" value="remove_board_deputy" />
								<input type="submit" class="button-secondary" value="Fjern">
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		Ingen bestyrelsessuppleanter
<?php endif;
}

?>