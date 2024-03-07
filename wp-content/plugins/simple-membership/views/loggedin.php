<?php
if (isset($_GET['visit'])) {
	wp_redirect(get_site_url() . urldecode($_REQUEST['visit']));
}
$auth = SwpmAuth::get_instance();
$login_notice = pll_e('Loggedin notice', 'simple-membership');
if ($login_notice != "") {
	echo '<p>' . $login_notice . '</p>';
}
?>
<div class="swpm-login-widget-logged">
	<div class="swpm-logged-username">
		<div class="swpm-logged-username-label swpm-logged-label"><?php echo pll_e('Logged in as', 'simple-membership') ?></div>
		<div class="swpm-logged-username-value swpm-logged-value"><?php echo $auth->get('user_name'); ?></div>
	</div>
	<!-- <div class="swpm-logged-status">
        <div class="swpm-logged-status-label swpm-logged-label"><?php echo SwpmUtils::_('Account Status') ?></div>
        <div class="swpm-logged-status-value swpm-logged-value"><?php echo SwpmUtils::_(ucfirst($auth->get('account_state'))); ?></div>
    </div> -->
	<div class="swpm-logged-membership">
		<div class="swpm-logged-membership-label swpm-logged-label"><?php pll_e('Membership', 'simple-membership') ?></div>
		<div class="swpm-logged-membership-value swpm-logged-value"><?php echo $auth->get('alias'); ?></div>
	</div>
	<!-- <div class="swpm-logged-expiry">
        <div class="swpm-logged-expiry-label swpm-logged-label"><?php echo SwpmUtils::_('Account Expiry') ?></div>
        <div class="swpm-logged-expiry-value swpm-logged-value"><?php echo $auth->get_expire_date(); ?></div>
    </div> -->
	<?php
	$edit_profile_page_url = SwpmSettings::get_instance()->get_value('profile-page-url');
	if (pll_current_language() == "en") {
		$edit_profile_page_url = SwpmSettings::get_instance()->get_value('profile-page-url-english');
	}
	if (!empty($edit_profile_page_url)) {
		//Show the edit profile link
		echo '<div class="swpm-edit-profile-link">';
		echo '<a href="' . $edit_profile_page_url . '">';
		pll_e("Edit Profile", 'simple-membership');
		echo '</a>';
		echo '</div>';
	}
	?>
	<div class="swpm-logged-logout-link">
		<a href="?swpm-logout=true"><?php pll_e('Logout', 'simple-membership') ?></a>
	</div>
</div>