<?php

save_settings($AKDTU_OPTIONS['fælleshus-internet.php']);

# Write settings interface
function AKDTU_fælleshus_internet_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['fælleshus-internet.php']);
}

?>