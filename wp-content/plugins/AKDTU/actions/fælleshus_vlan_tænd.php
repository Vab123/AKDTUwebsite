<?php

/**
 * @file Action to turn on the vlan for the internet connection in the common house
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_vlan_tænd') {
		try {
			# Attempt to turn on the vlan
			$result = set_fælleshus_vlan(1);
		} catch (Exception $e) {
			# An exception was thrown. Turning on the vlan failed
			new AKDTU_notice('error', "Det lykkedes ikke at tænde for netværket. Fejlinfo: " . $e->getMessage());
		}

		if ($result->state) {
			# The network reported itself as turned on. Write error message to admin interface
			new AKDTU_notice('success', 'Internet-forbindelsen i fælleshuset er nu tændt.');
		} else {
			# The network reported itself as turned off. Write success message to admin interface
			new AKDTU_notice('error', 'Internet-forbindelsen i fælleshuset er stadig slukket.');
		}
	}
}
