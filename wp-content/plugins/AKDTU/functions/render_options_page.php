<?php

function save_settings($OPTION_INFO) {
	if (isset($_REQUEST['action'])) {
		foreach ($OPTION_INFO['tabs'] as $tab => $tab_info) {
			if ($tab_info['tab-type'] == "settings" && $_REQUEST['action'] == $tab_info['save-action']) {
				foreach ($tab_info['settings'] as $group) {
					foreach ($group['content'] as $content) {
						update_option($content['name'], stripcslashes($_REQUEST[$content['name']]));
					}
				}

				# Form info saved. Write success message to admin interface
				new AKDTU_notice('success', $tab_info['save-success-message']);
			}
		}
	}
}

function render_options_page($OPTION_INFO) {
	$current_tab = isset($_GET['tab']) ? $_GET['tab'] : $OPTION_INFO['default-tab'];
	if (!isset($OPTION_INFO['tabs'][$current_tab])) {
		(new AKDTU_notice('error', "This tab does not exist ..."))->render();

		return false;
	}

	echo '<div class="wrap">';

	if ($OPTION_INFO['h1'] != '') {
		echo '<h1>' . $OPTION_INFO['h1'] . '</h1>';
	}
	if ($OPTION_INFO['h2'] != '') {
		echo '<h2>' . $OPTION_INFO['h2'] . '</h2>';
	}

	echo '<nav class="nav-tab-wrapper">';
	foreach ($OPTION_INFO['tabs'] as $tab => $tab_info) {
		echo '<a href="?page=' . $OPTION_INFO['menu-slug'] . '&tab=' . $tab . '" class="nav-tab' . ($current_tab == $tab ? ' nav-tab-active' : '') . '">' . $OPTION_INFO['tabs'][$tab]['tab-title'] . '</a>';
	}
	echo '</nav>';

	if ($OPTION_INFO['tabs'][$current_tab]['tab-type'] == "settings") {
		render_settings_tab($OPTION_INFO['tabs'][$current_tab]);
	}
	if ($OPTION_INFO['tabs'][$current_tab]['tab-type'] == "test") {
		render_test_tab($OPTION_INFO['tabs'][$current_tab]);
	}

	echo '</div>';
}

function render_settings_tab($TAB_INFO) {
	echo '<form method="post" action="">';
	echo '<input type="hidden" name="page" value="' . $_GET['menu-slug'] . '" />';
	echo '<input type="hidden" name="action" value="' . $TAB_INFO["save-action"] . '" />';

	echo join('<hr>', array_map(function($settings_group) use($TAB_INFO) {
		$settings_group_as_string = "";

		if ($settings_group['h3'] != "") {
			$settings_group_as_string .= '<h3>' . $settings_group['h3'] . '</h3>';
		}

		$settings_group_as_string .= '<table class="form-table" role="presentation">';
		$settings_group_as_string .= '<tbody>';
		foreach ($settings_group['content'] as $setting) {
			$settings_group_as_string .= '<tr><th scope="row">' . $setting['headline'] . '</th>';

			$settings_group_as_string .= '<td>';
			if ($setting['tag'] == "input") {
				$settings_group_as_string .= '<input type="' . $setting['type'] . '" name="' . $setting['name'] . '" style="' . $setting['style'] . '" ' . ($setting['type'] == 'checkbox' ? (get_option('AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE') ? ' checked' : '') : ' value="' . stripcslashes(get_option($setting['name'])) . '"') . '/>';
			}
			if ($setting['tag'] == "textarea") {
				$settings_group_as_string .= '<textarea type="' . $setting['type'] . '" name="' . $setting['name'] . '" rows="' . $setting['rows'] . '" cols="' . $setting['cols'] . '" style="' . $setting['style'] . '">' . stripcslashes(get_option($setting['name'])) . '</textarea>';
			}

			foreach ($setting['comments'] as $comment) {
				$settings_group_as_string .= '<p>' . $comment . '</p>';
			}
			
			$settings_group_as_string .= '</td>';
			$settings_group_as_string .= '</tr>';
		}
		$settings_group_as_string .= '<tr><th></th><td><input type="submit" class="button-primary" value="' . $TAB_INFO['save-button-text'] . '" /></td>';
		$settings_group_as_string .= '</tbody>';
		$settings_group_as_string .= '</table>';

		return $settings_group_as_string;
	}, $TAB_INFO['settings']));

	echo '</form>';

}

function render_test_tab($TAB_INFO) {
	foreach ($TAB_INFO['includes'] as $include) {
		include_once $include;
	}

	foreach ($TAB_INFO['function-calls'] as $function_call) {
		eval($function_call);
	}
}

?>