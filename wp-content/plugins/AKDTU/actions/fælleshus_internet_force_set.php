<?php

/**
 * @file Action to forcefully update the password on the router in the common house to a specific value
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_internet_force_set') {
		# Update password to the router and send email to Board and renter
		$password_change_status = set_fælleshus_password($_REQUEST['new_password']);

		if ($password_change_status['password'] == $_REQUEST['new_password']) {
			# Update successful. Write success message to admin interface
			new AKDTU_notice('success', 'Adgangskoden til internettet blev opdateret.');
		}
		else {
			# Update failed. Write error message to admin interface
			new AKDTU_notice('error', 'Adgangskoden til internettet kunne ikke opdateres.');
		}
	}
}
