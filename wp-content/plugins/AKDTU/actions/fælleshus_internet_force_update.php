<?php

/**
 * @file Action to forcefully update the password on the router in the common house
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_internet_force_update') {
		force_update_fælleshus_internet();
	}
}

/**
 * Forcefully updates the password on the router in the common house
 * 
 * @return bool True if the common house internet was updated and infomails sent successfully
 */
function force_update_fælleshus_internet() {
	# Update password to the router and send email to Board and renter
	if (send_opdater_fælleshus_internet(false, true)) { # Defined in "wp-content\plugins\AKDTU\cronjobs\Opdater_fælleshus_internet.php"
		# Write success message to admin interface
		new AKDTU_notice('success', 'Adgangskoden til internettet blev opdateret.');

		return true;
	}
	else {
		# Write error message to admin interface
		new AKDTU_notice('error', 'Adgangskoden til internettet blev ikke opdateret.');

		return false;
	}
}
