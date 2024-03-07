<?php

save_settings($AKDTU_OPTIONS['brugeradgang.php']);

# Write settings interface
function AKDTU_brugeradgang_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['brugeradgang.php']);
}

?>