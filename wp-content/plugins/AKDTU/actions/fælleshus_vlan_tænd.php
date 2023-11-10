<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_vlan_tænd') {
		try {
			$result = set_fælleshus_vlan(1);
		} catch (Exception $e) {
			new AKDTU_notice('error', "Det lykkedes ikke at tænde for netværket. Fejlinfo: " . $e->getMessage());
		}

		if ($result->state) {
			new AKDTU_notice('success', 'Internet-forbindelsen i fælleshuset er nu tændt.');
		} else {
			new AKDTU_notice('error', 'Internet-forbindelsen i fælleshuset er stadig slukket.');
		}
	}
}
