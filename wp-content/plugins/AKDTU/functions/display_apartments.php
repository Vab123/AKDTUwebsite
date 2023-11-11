<?php

/**
 * @file Functionality related to the display of all apartment numbers
 */

/**
 * Echos a html-dropdown element containing an element for each apartment
 */
function apartments_dropdown() { ?>
	<select name="user">
		<?php foreach (all_apartments() as $apartment) {
			echo '<option value="' . ($apartment) . '">' . ($apartment) . '</option>';
		} ?>
	</select>
<?php }

/**
 * Returns an array with the apartment numbers for all apartments
 * 
 * @return array[int] Array of apartment numbers for all apartments
 */
function all_apartments() {
	# Prepare array for apartment numbers
	$apartments = array();

	# Go through all floors
	for ($floor = 0; $floor < 3; $floor++) {
		# Go through all apartment numbers on this floor
		for ($apartment = 100 * $floor + 1; $apartment < 100 * $floor + 25; $apartment++) {
			# Add apartment number to array
			$apartments[] = $apartment;
		}
	}

	# Return array of apartment numbers
	return $apartments;
}

?>