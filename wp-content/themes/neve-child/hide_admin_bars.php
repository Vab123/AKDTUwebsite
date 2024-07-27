<?php

/**
 * Remove admin bar from certain user roles
 */
$allowed_roles = ['administrator', 'editor', 'board_member', 'deputy'];
if (is_user_logged_in() && empty(array_intersect(wp_get_current_user()->roles, $allowed_roles))) {
	add_filter('show_admin_bar', '__return_false');
}