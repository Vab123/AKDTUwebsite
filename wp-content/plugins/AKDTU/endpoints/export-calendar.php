<?php

add_action('plugins_loaded', 'AKDTU_export_calendar');

/**
 * Exports the AKDTU calendar to ICS format, allowing users to subscribe to the calendar with Google Calendar, Outlook, etc. Ends further code execution.
 */
function AKDTU_export_calendar() {
	global $wpdb;

	# Check if we are on the correct page
	if (strpos($_SERVER["REQUEST_URI"], 'export-calendar.php') == 1) {

		if (isset($_GET['user']) && isset($_GET['auth']) && username_exists($_GET['user']) && md5(get_user_by('login', ($_GET['user']))->user_pass) == $_GET['auth']) {

			# Names for output calendar, in ics
			$calendar_names =  array(
				"da" => "AKDTU Fælleshus kalender",
				"en" => "AKDTU Common house calendar"
			);

			# Descriptions for output calendar, in ics
			$calendar_descriptions =  array(
				"da" => "Kalenderen viser hvornår fælleshuset hos Andelskollegiet ved DTU - Akademivej er optaget.",
				"en" => "The calendar shows when the common house at Andelskollegiet ved DTU - Akademivej is booked."
			);

			# List of languages, the calendar comes in
			$accepted_languages = array_keys($calendar_names);

			# Get the desired calendar-language
			$calendar_language = "da";
			if (isset($_GET['lang']) && in_array($_GET['lang'], $accepted_languages)) {
				$calendar_language = $_GET['lang'];
			}

			# Get a list of all active events
			if (user_can(get_user_by('login', $_GET['user']), 'edit_others_events')) {
				$events = $wpdb->get_results('SELECT event_id,post_id,event_status FROM ' . EM_EVENTS_TABLE . ' WHERE event_status IN (0,1)');
			} else {
				$events = $wpdb->get_results('SELECT event_id,post_id,event_status FROM ' . EM_EVENTS_TABLE . ' WHERE event_status = 1');
			}



			# Only use events which are in the desired language, or where no translation exists
			$events = array_filter($events, function ($event) use ($calendar_language) {
				return pll_get_post_language($event->post_id) == $calendar_language || count(pll_get_post_translations($event->post_id)) == 1;
			});

			# Get array of event objects
			$events = array_map(function ($event) {
				return em_get_event($event->event_id);
			}, $events);

			# Start calendar export string with info about the calendar
			$calendar_export_string = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nNAME:" . $calendar_names[$calendar_language] . "\nX-WR-CALNAME:" . $calendar_names[$calendar_language] . "\nDESCRIPTION:" . $calendar_descriptions[$calendar_language] . "\nX-WR-CALDESC:" . $calendar_descriptions[$calendar_language] . "\nTIMEZONE-ID:Europe/Copenhagen\nX-WR-TIMEZONE:Europe/Copenhagen\nREFRESH-INTERVAL;VALUE=DURATION:PT12H\nX-PUBLISHED-TTL:PT12H\n";

			# Go through all events and export them
			foreach ($events as $EM_Event) {
				# Time info about the event
				$starttime = new DateTime($EM_Event->event_start_date . " " . $EM_Event->event_start_time);
				$endtime = new DateTime($EM_Event->event_end_date . " " . $EM_Event->event_end_time);

				# Replacements in event title for rentals of the common house
				$event_name_replaces = array(
					'#_RENTAL_BEFORE_APARTMENTNUM' => pll_translate_string(($EM_Event->event_status == 0 ? 'RENTAL_BEFORE_APARTMENTNUM_NOTAPPROVED' : 'RENTAL_BEFORE_APARTMENTNUM_APPROVED'), $calendar_language),
					'#_RENTAL_AFTER_APARTMENTNUM' => pll_translate_string(($EM_Event->event_status == 0 ? 'RENTAL_AFTER_APARTMENTNUM_NOTAPPROVED' : 'RENTAL_AFTER_APARTMENTNUM_APPROVED'), $calendar_language),
				);
				$event_name = str_replace(array_keys($event_name_replaces), $event_name_replaces, $EM_Event->event_name);

				# Write info about calendar event
				$calendar_export_string .= "BEGIN:VEVENT\n";
				$calendar_export_string .= "SUMMARY:" . $event_name . "\n";
				$calendar_export_string .= "DTSTART;TZID=Europe/Copenhagen:" . $starttime->format("Ymd") . "T" . $starttime->format("His") . "\n";
				$calendar_export_string .= "DTEND;TZID=Europe/Copenhagen:" . $endtime->format("Ymd") . "T" . $endtime->format("His") . "\n";
				$calendar_export_string .= "LOCATION:Kollegiebakken 19, 2800 Kongens Lyngby, Denmark\n";
				// $calendar_export_string .= "DESCRIPTION:" . $event_name . "\n";
				$calendar_export_string .= "STATUS:CONFIRMED\n";
				$calendar_export_string .= "END:VEVENT\n";
			}

			# End calendar export string
			$calendar_export_string .= "END:VCALENDAR";

			# Set relevant header information
			header('Content-Type: text/calendar; charset=utf-8');
			header('Content-Disposition: attachment; filename=AKDTU calendar ' . $calendar_language . '.ics');
			Header('Content-Length: ' . strlen($calendar_export_string));
			Header('Connection: close');

			# Export calendar info
			echo $calendar_export_string;
		} else {
			$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
			header($protocol . ' 401 Unauthorized');
		}
		die();
	}
}
