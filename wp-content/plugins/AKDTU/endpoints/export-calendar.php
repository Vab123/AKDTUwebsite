<?php

# Check if we are on the correct page
if (strpos($_SERVER["REQUEST_URI"], 'export-calendar.php') == 1) {
	add_action('plugins_loaded', 'AKDTU_export_calendar');
}

/**
 * Exports the AKDTU calendar to ICS format, allowing users to subscribe to the calendar with Google Calendar, Outlook, etc. Ends further code execution.
 */
function AKDTU_export_calendar() {
	global $wpdb;

	if (isset($_GET['user']) && isset($_GET['auth']) && username_exists($_GET['user']) && md5(get_user_by('login', ($_GET['user']))->user_pass) == $_GET['auth']) {
		# Start empty calendar string
		$calendar_export_string = "";

		# Current date
		$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

		# Start date for calendar
		$calendar_start_date = $now->modify('-1 year');

		# Names for output calendar, in ics
		$calendar_names = array(
			"da" => "AKDTU Fælleshus kalender",
			"en" => "AKDTU Common house calendar"
		);

		# Descriptions for output calendar, in ics
		$calendar_descriptions = array(
			"da" => "Kalenderen viser hvornår fælleshuset hos Andelskollegiet ved DTU - Akademivej er optaget.",
			"en" => "The calendar shows when the common house at Andelskollegiet ved DTU - Akademivej is booked."
		);

		# Get the desired calendar-language
		$calendar_language = (isset($_GET['lang']) && in_array($_GET['lang'], array_keys($calendar_names)) ? $_GET['lang'] : "da");

		# Get a list of all active events
		$events = $wpdb->get_results('SELECT event_id,post_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_status IN (0,1) AND event_start_date >= "' . $calendar_start_date->format('Y-m-d') . '" ORDER BY event_start_date ASC');

		# Only use events which are in the desired language, or where no translation exists
		$events = array_filter($events, function ($event) use ($calendar_language) {
			return pll_get_post_language($event->post_id) == $calendar_language || count(pll_get_post_translations($event->post_id)) == 1;
		});

		# Get array of event objects
		$events = array_map(function ($event) {
			return em_get_event($event->event_id);
		}, $events);

		# Start calendar export string with info about the calendar
		$calendar_export_string .= "BEGIN:VCALENDAR\r\n";
		$calendar_export_string .= "PRODID:AKDTU//" . $calendar_language . "\r\n";
		$calendar_export_string .= "DTSTAMP:" . $now->format("Ymd\THis") . "\r\n";
		$calendar_export_string .= "VERSION:2.0\r\n";
		$calendar_export_string .= "METHOD:PUBLISH\r\n";
		$calendar_export_string .= "NAME:" . $calendar_names[$calendar_language] . "\r\n";
		$calendar_export_string .= "X-WR-CALNAME:" . $calendar_names[$calendar_language] . "\r\n";
		$calendar_export_string .= join("\r\n ", str_split("DESCRIPTION:" . $calendar_descriptions[$calendar_language], 74)) . "\r\n";
		$calendar_export_string .= join("\r\n ", str_split("X-WR-CALDESC:" . $calendar_descriptions[$calendar_language], 74)) . "\r\n";
		$calendar_export_string .= "TIMEZONE-ID:Europe/Copenhagen\r\n";
		$calendar_export_string .= "X-WR-TIMEZONE:Europe/Copenhagen\r\n";
		$calendar_export_string .= "REFRESH-INTERVAL;VALUE=DURATION:PT12H\r\n";
		$calendar_export_string .= "X-PUBLISHED-TTL:PT12H\r\n";

		# Define timezone
		$calendar_export_string .= "BEGIN:VTIMEZONE\r\n";
		$calendar_export_string .= "TZID:Europe/Copenhagen\r\n";
		$calendar_export_string .= "BEGIN:DAYLIGHT\r\n";
		$calendar_export_string .= "TZNAME:CEST\r\n";
		$calendar_export_string .= "TZOFFSETFROM:+0100\r\n";
		$calendar_export_string .= "TZOFFSETTO:+0200\r\n";
		$calendar_export_string .= "DTSTART:19700329T020000\r\n";
		$calendar_export_string .= "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU\r\n";
		$calendar_export_string .= "END:DAYLIGHT\r\n";
		$calendar_export_string .= "BEGIN:STANDARD\r\n";
		$calendar_export_string .= "TZNAME:CET\r\n";
		$calendar_export_string .= "TZOFFSETFROM:+0200\r\n";
		$calendar_export_string .= "TZOFFSETTO:+0100\r\n";
		$calendar_export_string .= "DTSTART:19701025T030000\r\n";
		$calendar_export_string .= "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU\r\n";
		$calendar_export_string .= "END:STANDARD\r\n";
		$calendar_export_string .= "END:VTIMEZONE\r\n";

		# Go through all events and export them
		$calendar_export_string .= join('', array_map(function ($EM_Event) use($calendar_language) {
			return event_as_ics($EM_Event, $calendar_language);
		}, $events));

		# End calendar export string
		$calendar_export_string .= "END:VCALENDAR";

		# Set relevant header information
		header('Content-Type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename=AKDTU calendar ' . $calendar_language . '.ics');
		header('Content-Length: ' . strlen($calendar_export_string));
		header('Connection: close');

		# Export calendar info
		echo $calendar_export_string;
	} else {
		# User was not authenticated. Exit
		header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 401 Unauthorized');
	}

	# Stop further execution
	die();
}

/**
 * Returns a string representation of an event, following the ICS format (https://icalendar.org/RFC-Specifications/iCalendar-RFC-5545/)
 * 
 * @param EM_Event $EM_Event The event to output as a string
 * @param string $calendar_language Language code used when outputting the event
 * 
 * @return string Event formatted as ICS string
 */
function event_as_ics($EM_Event, $calendar_language) {
	# Prepare empty string for event
	$event_as_ics_string = '';

	# Translation of status codes
	$status_codes = array(
		0 => "CONFIRMED",
		1 => "CONFIRMED"
	);

	$apartment_string = array(
		'da' => 'Lejlighed ',
		'en' => 'Apartment ',
	);
	$board_string = array(
		'da' => 'Bestyrelsen',
		'en' => 'The Board',
	);

	# Time info about the event
	$starttime = new DateTime($EM_Event->event_start_date . " " . $EM_Event->event_start_time);
	$endtime = new DateTime($EM_Event->event_end_date . " " . $EM_Event->event_end_time);

	# Replacements in event title for rentals of the common house
	$event_name = format_common_house_rental_name($EM_Event, $calendar_language);

	# Replacements in event description for rentals of the common house
	$event_description = format_common_house_rental_description($EM_Event, $calendar_language, true);

	# Write info about calendar event
	$event_as_ics_string .= "BEGIN:VEVENT\r\n";
	$event_as_ics_string .= "SUMMARY:" . $event_name . "\r\n";
	$event_as_ics_string .= "ORGANIZER;CN=" . (is_apartment_user_from_id($EM_Event->owner) ? $apartment_string[$calendar_language] . padded_apartment_number_from_id($EM_Event->owner) : $board_string[$calendar_language]) . ":\r\n";
	$event_as_ics_string .= "UID:" . $EM_Event->owner . "//" . $starttime->format("Ymd") . "\r\n";
	$event_as_ics_string .= "DTSTAMP;TZID=Europe/Copenhagen:" . $starttime->format("Ymd\THis") . "\r\n";
	$event_as_ics_string .= "DTSTART;TZID=Europe/Copenhagen:" . $starttime->format("Ymd\THis") . "\r\n";
	$event_as_ics_string .= "DTEND;TZID=Europe/Copenhagen:" . $endtime->format("Ymd\THis") . "\r\n";
	$event_as_ics_string .= "LOCATION:Kollegiebakken 19, 2800 Kongens Lyngby, Denmark\r\n";
	$event_as_ics_string .= join("\r\n ", str_split("DESCRIPTION:" . $event_description, 74)) . "\r\n";
	$event_as_ics_string .= "STATUS:" . $status_codes[$EM_Event->get_status()] . "\r\n";
	$event_as_ics_string .= "END:VEVENT\r\n";

	return $event_as_ics_string;
}