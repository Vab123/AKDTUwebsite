<?php
require_once WP_PLUGIN_DIR . '/AKDTU/functions/notice.php';

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_vlan_sluk') {
		require_once WP_PLUGIN_DIR . '/AKDTU/functions/vlan.php';
		try {
			$result = set_fælleshus_vlan(0);
		} catch (Exception $e) {
			new AKDTU_notice('error', "Det lykkedes ikke at slukke for netværket. Fejlinfo: " . $e->getMessage());
		}

		new AKDTU_notice('warning', 'Bemærk: Dette er stadig en test, og afventer at internettet i fælleshuset bliver sat ordentligt op.');
		if (!$result->state) {
			new AKDTU_notice('success', 'Internet-forbindelsen i fælleshuset er nu slukket.');
		} else {
			new AKDTU_notice('error', 'Internet-forbindelsen i fælleshuset er stadig tændt.');
		}
	}
}
