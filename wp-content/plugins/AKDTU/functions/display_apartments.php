<?php function apartments_dropdown() { ?>
	<select name="user">
		<?php for ($floor = 0; $floor < 3; $floor++) {
			for ($apartment = 1; $apartment < 25; $apartment++) {
				echo '<option value="' . ($apartment + 100 * $floor) . '">' . ($apartment + 100 * $floor) . '</option>';
			}
		} ?>
	</select>
<?php } ?>