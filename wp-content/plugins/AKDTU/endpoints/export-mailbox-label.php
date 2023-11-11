<?php

add_action('plugins_loaded', 'AKDTU_export_mailbox_label');

/**
 * Exports the AKDTU calendar to ICS format, allowing users to subscribe to the calendar with Google Calendar, Outlook, etc. Ends further code execution.
 */
function AKDTU_export_mailbox_label() {
	global $wpdb;

	# Check if we are on the correct page
	if (strpos($_SERVER["REQUEST_URI"], 'export-mailbox-label.php') == 1) {

		if (is_user_logged_in() && isset($_GET['user_name'])) {
			require_once WP_PLUGIN_DIR . '/AKDTU/mpdf.php';

			$user = wp_get_current_user();

			$aptnum = (is_apartment_from_username($user->user_login) ? apartment_number_from_username($user->user_login) : "XXX");

			$mpdf = new \AKDTUpdf([210, 297], 'landscape', 32, 10, 11, 11, 11);

			$html = '<html style="margin:0; padding:0;"><body style="margin:0; padding:0;"><table style="border-top: 4px dashed lightgrey; border-bottom: 4px dashed lightgrey; font-family: \'calibri\'; height: 62px; width: 100%;"><tbody><tr><td style="width: 8.3cm"><div style="font-size: 24pt;">Kollegiebakken 19. Lejl:</div></td><td style="width: 1.5cm"><div style="font-size: 24pt;">' . $aptnum . '</div></td><td style="width: 17.8cm" align="right"><div style="font-size: 36pt; overflow: hidden;">' . $_GET['user_name'] . '</div></td></tr></tbody></table></body></html>';

			$mpdf->WriteHTML($html);

			$mpdf->Output('AKDTU - Mailbox label - apt. ' . $aptnum . '.pdf', 'd');

		} else {
			$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
			header($protocol . ' 401 Unauthorized');
		}
		die();
	}
}
