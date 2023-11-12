<?php

/**
 * @file Action to turn off the vlan for the internet connection in the common house
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_vlan_sluk') {
		try {
			# Attempt to turn off the vlan
			$result = set_fælleshus_vlan(0);
		} catch (Exception $e) {
			# An exception was thrown. Turning off the vlan failed
			new AKDTU_notice('error', "Det lykkedes ikke at slukke for netværket. Fejlinfo: " . $e->getMessage());
		}

		# Check the resulting state of the network
		if (!$result->state) {
			# The network reported itself as turned off. Write success message to admin interface
			new AKDTU_notice('success', 'Internet-forbindelsen i fælleshuset er nu slukket.');
		} else {
			# The network reported itself as turned on. Write error message to admin interface
			new AKDTU_notice('error', 'Internet-forbindelsen i fælleshuset er stadig tændt.');
		}
	}
}
