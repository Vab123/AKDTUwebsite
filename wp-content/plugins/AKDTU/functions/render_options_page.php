<?php

/**
 * Saves settings related to a given options tab, based on its defined save-action
 * 
 * @param array $option Array containing information about the settings page to save
 */
function save_settings($option) {
	if (isset($_REQUEST['action'])) {
		foreach ($option['tabs'] as $tab => $tab_info) {
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

/**
 * Echos an entire options page
 * 
 * @param array $option Array containing information about the options page to echo
 */
function render_options_page($option) {
	$current_tab = isset($_GET['tab']) ? $_GET['tab'] : $option['default-tab'];
	if (!isset($option['tabs'][$current_tab])) {
		(new AKDTU_notice('error', "This tab does not exist ..."))->render();

		return false;
	}

	echo '<div class="wrap">';

	if ($option['h1'] != '') {
		echo '<h1>' . $option['h1'] . '</h1>';
	}
	if ($option['h2'] != '') {
		echo '<h2>' . $option['h2'] . '</h2>';
	}

	echo '<nav class="nav-tab-wrapper">';
	foreach ($option['tabs'] as $tab => $tab_info) {
		echo '<a href="?page=' . $option['menu-slug'] . '&tab=' . $tab . '" class="nav-tab' . ($current_tab == $tab ? ' nav-tab-active' : '') . '">' . $option['tabs'][$tab]['tab-title'] . '</a>';
	}
	echo '</nav>';

	if ($option['tabs'][$current_tab]['tab-type'] == "settings") {
		render_settings_tab($option['tabs'][$current_tab]);
	}
	if ($option['tabs'][$current_tab]['tab-type'] == "test") {
		render_test_tab($option['tabs'][$current_tab]);
	}

	echo '</div>';
}

/**
 * Echos an individual settings tab
 * 
 * @param array $tab Array containing information about the settings tab to echo
 */
function render_settings_tab($tab) {
	echo '<form method="post" action="">';
	echo '<input type="hidden" name="page" value="' . $_GET['menu-slug'] . '" />';
	echo '<input type="hidden" name="action" value="' . $tab["save-action"] . '" />';

	echo join('<hr>', array_map(function($settings_group) use($tab) {
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
		$settings_group_as_string .= '<tr><th></th><td><input type="submit" class="button-primary" value="' . $tab['save-button-text'] . '" /></td>';
		$settings_group_as_string .= '</tbody>';
		$settings_group_as_string .= '</table>';

		return $settings_group_as_string;
	}, $tab['settings']));

	echo '</form>';

}

/**
 * Echos an individual test tab
 * 
 * @param array $tab Array containing information about the test tab to echo.
 * 
 * Required keys:
 * - 'includes': Array of files to include.
 * - 'function-calls': Array of function calls to execute.
 */
function render_test_tab($tab) {
	foreach ($tab['includes'] as $include) {
		include_once $include;
	}

	foreach ($tab['function-calls'] as $function_call) {
		eval($function_call);
	}
}

?>