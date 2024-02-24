<?php

/**
 * @file Action to forcefully update the password on the router in the common house
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_internet_force_update') {
		# Update password to the router and send email to Board and renter
		if (send_opdater_fælleshus_internet(false, true)) { # Defined in "wp-content\plugins\AKDTU\cronjobs\Opdater_fælleshus_internet.php"
			# Write success message to admin interface
			new AKDTU_notice('success', 'Adgangskoden til internettet blev opdateret.');
		}
		else {
			# Write error message to admin interface
			new AKDTU_notice('error', 'Adgangskoden til internettet blev ikke opdateret.');
		}
	}
}
