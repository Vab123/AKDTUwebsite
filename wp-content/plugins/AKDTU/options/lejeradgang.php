<?php

save_settings($AKDTU_OPTIONS['lejeradgang.php']);

# Write settings interface
function AKDTU_lejeradgang_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['lejeradgang.php']);
}

?>