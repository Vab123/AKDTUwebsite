<?php

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'fælleshus_internet_force_update') {
		send_opdater_fælleshus_internet();

		new AKDTU_notice('success', 'Adgangskoden til internettet blev opdateret.');
	}
}
