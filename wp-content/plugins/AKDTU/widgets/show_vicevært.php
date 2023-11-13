<?php

/**
 * @file Widget to display all current vicevært users
 */

function show_vicevært_widget() {
	$vicevært_role = 'vicevaert';

	$vicevært_users = get_users( array('role' => $vicevært_role) );

	if (count($vicevært_users) > 0) : ?>
		<table class="widefat">
			<colgroup>
				<col span="1" style="width: 20%" />
				<col span="1" style="width: <?php if (!is_admin()) : ?>60%<?php else : ?>80%<?php endif; ?>" />
				<col span="1" style="width: 20%" />
			</colgroup>
			<thead>
				<tr>
					<th>Brugernavn</th>
					<th>Navn</th>
					<th>Handlinger</th>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;
				foreach ($vicevært_users as $vicevært) : ?>
					<tr <?php if ($row % 2 == 0) {
							echo 'class="alternate"';
						};
						$row++; ?>>
						<td style="vertical-align:middle"><?php echo $vicevært->display_name; ?></td>
						<td style="vertical-align:middle"><?php echo $vicevært->first_name . ' '  . $vicevært->last_name; ?></td>
						<td>
							<form action="" method="post" style="text-align:center">
								<input type="hidden" name="user" value="<?php echo $vicevært->ID; ?>" />
								<input type="hidden" name="action" value="remove_vicevært" />
								<input type="submit" class="button-secondary" value="Fjern">
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		Ingen viceværter
<?php endif;
}

?>