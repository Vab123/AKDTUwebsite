<?php

save_settings($AKDTU_OPTIONS['fælleshus.php']);

# Write settings interface
function AKDTU_fælleshus_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['fælleshus.php']);
}

?>