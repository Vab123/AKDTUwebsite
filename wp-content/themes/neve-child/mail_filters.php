<?php

/**
 * Restrict sending emails when changing to and from default user
 */
## Dont send email change notifications when changing to or from @akdtu.dk
if (!function_exists('send_email_change_email')) {
	function send_email_change_email($return, $user, $userdata) {
		return $return && strtolower(explode("@", $userdata['user_email'])[1]) != 'akdtu.dk' && strtolower(explode("@", $user['user_email'])[1]) != 'akdtu.dk';
	}
	add_filter('send_email_change_email', 'send_email_change_email', 10, 3);
}
## Dont send password change notifications when changing to or from @akdtu.dk
if (!function_exists('send_password_change_email')) {
	function send_password_change_email($return, $user, $userdata) {
		return $return && strtolower(explode("@", $userdata['user_email'])[1]) != 'akdtu.dk' && strtolower(explode("@", $user['user_email'])[1]) != 'akdtu.dk';
	}
	add_filter('send_password_change_email', 'send_password_change_email', 10, 3);
}