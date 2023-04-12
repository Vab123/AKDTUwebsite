<?php

function show_board_widget() {
	$board_members = array();

	for ($floor = 0; $floor <= 2; $floor++) {
		for ($apartment = 1; $apartment <= 24; $apartment++) {

			$user = SwpmMemberUtils::get_user_by_user_name('lejl' . str_pad(($floor * 100 + $apartment), 3, "0", STR_PAD_LEFT));
			$level = SwpmMembershipLevelUtils::get_membership_level_name_by_level_id($user->membership_level);
			if ($level == "Beboerprofil til bestyrelsesmedlem") {
				$board_members[] = $user;
			}
		}
	}

	if (count($board_members) > 0) : ?>
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 20%" />
				<col span="1" style="width: <?php if (!is_admin()) : ?>60%<?php else : ?>80%<?php endif; ?>" />
				<?php if (is_admin()) : ?>
					<col span="1" style="width: 20%" /><?php endif; ?>
			</colgroup>
			<thead>
				<tr>
					<th>Lejlighed</th>
					<th>Navn</th>
					<?php if (is_admin()) : ?><th>Handlinger</th><?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;
				foreach ($board_members as $board_member) : ?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						};
						$row++; ?>>
						<td style="vertical-align:middle"><?php echo ltrim(substr($board_member->user_name, 4, 3), "0"); ?></td>
						<td style="vertical-align:middle"><?php echo $board_member->first_name . ' '  . $board_member->last_name; ?></td>
						<?php if (is_admin()) : ?><td>
								<form action="" method="post" style="text-align:center">
									<input type="hidden" name="user" value="<?php echo $board_member->member_id; ?>" />
									<input type="hidden" name="action" value="remove_boardmember" />
									<input type="submit" class="button-secondary" value="Fjern">
								</form>
							</td><?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		Ingen bestyrelsesmedlemmer
<?php endif;
}

?>