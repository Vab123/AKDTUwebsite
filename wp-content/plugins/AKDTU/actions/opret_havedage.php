<?php

/**
 * @file Action to programmatically create the events for all of the garden days for a season
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'add_havedag' && isset($_REQUEST['danish_name']) && isset($_REQUEST['danish_post_content']) && isset($_REQUEST['english_name']) && isset($_REQUEST['english_post_content']) && isset($_REQUEST['gardenday_dates']) && isset($_REQUEST['latest_signup']) && isset($_REQUEST['spaces']) && isset($_REQUEST['max_spaces']) && isset($_REQUEST['publish_date']) && isset($_REQUEST['start_time']) && isset($_REQUEST['end_time'])) {
		create_gardendays(
			$_REQUEST['danish_name'],
			$_REQUEST['danish_post_content'],
			$_REQUEST['english_name'],
			$_REQUEST['english_post_content'],
			array_map('trim', explode(",", $_REQUEST['gardenday_dates'])),
			new DateTime($_REQUEST['latest_signup']),
			$_REQUEST['spaces'],
			$_REQUEST['max_spaces'],
			$_REQUEST['publish_date'],
			$_REQUEST['start_time'],
			$_REQUEST['end_time']
		);
	}
}

function opret_havedage($danish_name, $danish_post_content, $english_name, $english_post_content, $gardendays, $latest_signup, $spaces, $max_spaces, $publish_date, $start_time, $end_time)
{
	create_gardendays($danish_name, $danish_post_content, $english_name, $english_post_content, $gardendays, $latest_signup, $spaces, $max_spaces, $publish_date, $start_time, $end_time);
}