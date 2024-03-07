<?php

/**
 * @file Action to forcefully update the password on the router in the common house to a specific value
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_internet_force_set') {
		set_fælleshus_internet($_REQUEST['new_password']);
	}
}

/**
 * Update the password on the router in the common house to a specific value
 * 
 * @param string $new_password New password
 * 
 * @return bool True if the password was set successfully
 */
function set_fælleshus_internet($new_password) {
	# Update password to the router and send email to Board and renter
	if (set_fælleshus_password($new_password)['password'] == $new_password) {
		# Update successful. Write success message to admin interface
		new AKDTU_notice('success', 'Adgangskoden til internettet blev opdateret.');

		return true;
	}
	else {
		# Update failed. Write error message to admin interface
		new AKDTU_notice('error', 'Adgangskoden til internettet kunne ikke opdateres.');

		return false;
	}
}
