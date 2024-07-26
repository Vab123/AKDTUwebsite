<?php

/**
 * @file Action to allow a resident to create a user on the website
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'delete_move' && isset($_REQUEST['user'])) {
		if (delete_move($_REQUEST['user'], new DateTime($_REQUEST['move_date'], new DateTimeZone('Europe/Copenhagen')))) {
			new AKDTU_notice('success', 'Overdragelsen blev slettet.');
			return true;
		} else {
			new AKDTU_notice('error', 'Tilladelsen til brugeroprettelse blev ikke slettet.');
			return false;
		}
	}
}
