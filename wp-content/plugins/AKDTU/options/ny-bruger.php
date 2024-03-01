<?php

save_settings($AKDTU_OPTIONS['ny-bruger.php']);

# Write settings interface
function AKDTU_ny_bruger_mail_settings() {
	global $AKDTU_OPTIONS;

	render_options_page($AKDTU_OPTIONS['ny-bruger.php']);
}

function test_ny_bruger_mail() {
	$_POST['apartment_number'] = 2;
	$user_login = 'lejl002';
	$_POST['email'] = 'victor2@akdtu.dk';
	$_POST['first_name'] = 'Victor';
	$_POST['last_name'] = 'Brandsen';
?>
	<h3>Mail til bestyrelse:</h3>
	<?php
	$content_replaces = array(
		'#APT' => $_POST['apartment_number'],
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $_POST['email'],
		'#NEWFIRSTNAME' => $_POST['first_name'],
		'#NEWLASTNAME' => $_POST['last_name']
	);

	$subject_replaces = array(
		'#APT' => $_POST['apartment_number'],
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $_POST['email'],
		'#NEWFIRSTNAME' => $_POST['first_name'],
		'#NEWLASTNAME' => $_POST['last_name']
	);

	send_AKDTU_email(true, $subject_replaces, $content_replaces, 'NYBRUGER_BESTYRELSE');
	?>
	<hr>
	<h3>Mail til ny beboer (dansk):</h3>
	<?php
	$content_replaces = array(
		'#APT' => $_POST['apartment_number'],
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $_POST['email'],
		'#NEWFIRSTNAME' => $_POST['first_name'],
		'#NEWLASTNAME' => $_POST['last_name']
	);

	$subject_replaces = array(
		'#APT' => $_POST['apartment_number'],
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $_POST['email'],
		'#NEWFIRSTNAME' => $_POST['first_name'],
		'#NEWLASTNAME' => $_POST['last_name']
	);

	send_AKDTU_email(true, $subject_replaces, $content_replaces, 'NYBRUGER_BRUGER_DA', $_POST['email']);
	?>
	<hr>
	<h3>Mail til ny beboer (engelsk):</h3>
	<?php
	$content_replaces = array(
		'#APT' => $_POST['apartment_number'],
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $_POST['email'],
		'#NEWFIRSTNAME' => $_POST['first_name'],
		'#NEWLASTNAME' => $_POST['last_name']
	);

	$subject_replaces = array(
		'#APT' => $_POST['apartment_number'],
		'#NEWLOGIN' => $user_login,
		'#NEWEMAIL' => $_POST['email'],
		'#NEWFIRSTNAME' => $_POST['first_name'],
		'#NEWLASTNAME' => $_POST['last_name']
	);

	send_AKDTU_email(true, $subject_replaces, $content_replaces, 'NYBRUGER_BRUGER_EN', $_POST['email']);
}

?>