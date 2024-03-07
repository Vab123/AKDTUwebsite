<?php

save_settings($AKDTU_OPTIONS['havedag.php']);

# Write settings interface
function AKDTU_havedag_opkrævning_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['havedag.php']);
}

?>