<?php function apartments_dropdown() { ?>
	<select name="user">
		<?php foreach (all_apartments() as $apartment) {
			echo '<option value="' . ($apartment) . '">' . ($apartment) . '</option>';
		} ?>
	</select>
<?php }

function all_apartments() {
	$apartments = array();

	for ($floor = 0; $floor < 3; $floor++) {
		for ($apartment = 100 * $floor + 1; $apartment < 100 * $floor + 25; $apartment++) {
			$apartments[] = $apartment;
		}
	}

	return $apartments;
}
?>