<?php

/**
 * @file Cron-job responsible for sending information about the changing of the password to the router in the common house
 */

/**
 * Cron-job responsible for sending information about the changing of the password to the router in the common house
 * 
 * @param bool $debug Flag, for whether the users should actually be deleted (false), or if this is a test run to show sample results (true)
 * @param bool $force_update Flag, for whether to force updating the password
 */
function send_opdater_fælleshus_internet($debug = false, $force_update = false) {
	# Cron-job frequency
	$run_every_hours_amount = 24; # How many hours go between each run of the cron-job

	# Date formats, Danish
	$date_da = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
	$date_da->setPattern('dd. MMMM YYYY');
	$time_da = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
	$time_da->setPattern('HH:mm');
	
	# Date formats, English
	$date_en = new IntlDateFormatter('en_US', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
	$date_en->setPattern('dd. MMMM YYYY');
	$time_en = new IntlDateFormatter('en_US', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
	$time_en->setPattern('HH:mm');

	# Get struct containing info about the password to the router
	$password_struct = generate_password_info($run_every_hours_amount);

	# Extract values from struct
	$new_password = $password_struct['password'];
	$password_should_be_changed = $password_struct['should_be_changed'] || $force_update;
	$event = $password_struct['event'];
	$send_mail_to_renter = $password_struct['send_mail_to_renter'];
	$rented_state = $password_struct['rented_state'];

	# Check if this is a real run, and the password should be changed
	if (!$debug && $password_should_be_changed) {
		# This is a real run

		# Update the password according to the struct
		$status = set_fælleshus_password($new_password);

		# Check if successful
		if ($status['password'] != $new_password) {
			# Password was NOT updated successfully. Send error message to the network group
			wp_mail("netgruppen@akdtu.dk", "Ændring af adgangskode til fælleshusets internet fejlet", "Ændring af adgangskode til fælleshusets internet fejlet. Ingen yderligere mails er sendt. Dette skal undersøges nærmere.");
			return;
		}
	} else {
		# This is NOT a real run. Set status as if it was a successful real run
		$status = array(
			'password' => $new_password,
			'ssid' => get_router_settings()['ssid']
		);
	}

	# Check if an email should be sent to the Board or echoed
	if ($debug || ($password_should_be_changed && FÆLLESHUS_INTERNET_TO != '')) {
		# Replacements for email subject
		$subject_replaces = array();

		# Replacements for email content
		$content_replaces = array(
			'#RENTER' => ($rented_state == 0 ? "Ingen" : "") . ($rented_state == 1 ? "Lejlighed " . apartment_number_from_id($event->owner) : "") . ($rented_state == 2 ? "Bestyrelsen" : ""),
			'#SSID' => $status["ssid"],
			'#NEWPASS' => $status["password"],
			'#UPDATETIME' => (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s'),
		);

		# Write header if this is a test-run
		if ($debug) {
			echo '<h3>Mail til bestyrelsen:</h3>';
		}
		
		# Send or echo email
		send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FÆLLESHUS_INTERNET');
	}

	# Check if an email should be sent to a renter or echoed
	if ($debug || ($password_should_be_changed && $send_mail_to_renter)) {
		# Get the language of the event (Assumed the same as the language of the renter)
		$lang = pll_get_post_language($event->post_id);

		# Check if an email in Danish should be sent or echoed
		if ($debug || ($lang == "da" && FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE)) {
			# Get owner of the event
			$event_owner = get_user_by('ID', $event->owner);

			# Replacements for email subject
			$subject_replaces_da = array();

			# Replacements for email content
			$content_replaces_da = array(
				'#SSID' => $status["ssid"],
				'#NEWPASS' => $status["password"],
				'#RENTALSTARTDATE' => $date_da->format(new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('Europe/Copenhagen'))),
				'#RENTALENDDATE' => $date_da->format(new DateTime($event->event_end_date . " " . $event->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
				'#RENTALSTARTTIME' => $time_da->format(new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('Europe/Copenhagen'))),
				'#RENTALENDTIME' => $time_da->format(new DateTime($event->event_end_date . " " . $event->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
				'#FIRSTNAME' => $event_owner->first_name,
				'#LASTNAME' => $event_owner->last_name,
			);

			# Write header if this is a test-run
			if ($debug) {
				echo '<hr><h3>Mail til beboer, dansk: sendes' . (FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE ? "" : " ikke") . '</h3>';
			}

			# Send or echo email
			send_AKDTU_email($debug, $subject_replaces_da, $content_replaces_da, 'FÆLLESHUS_INTERNET_BRUGER_DA', $event_owner->user_email);
		}

		# Check if an email in English should be sent or echoed
		if ($debug || ($lang == "en" && FÆLLESHUS_INTERNET_BRUGER_EN_TOGGLE)) {
			# Get owner of the event
			$event_owner = get_user_by('ID', $event->owner);
			
			# Replacements for email subject
			$subject_replaces_en = array();

			# Replacements for email content
			$content_replaces_en = array(
				'#SSID' => $status["ssid"],
				'#NEWPASS' => $status["password"],
				'#RENTALSTARTDATE' => $date_en->format(new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('Europe/Copenhagen'))),
				'#RENTALENDDATE' => $date_en->format(new DateTime($event->event_end_date . " " . $event->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
				'#RENTALSTARTTIME' => $time_en->format(new DateTime($event->event_start_date . " " . $event->event_start_time, new DateTimeZone('Europe/Copenhagen'))),
				'#RENTALENDTIME' => $time_en->format(new DateTime($event->event_end_date . " " . $event->event_end_time, new DateTimeZone('Europe/Copenhagen'))),
				'#FIRSTNAME' => $event_owner->first_name,
				'#LASTNAME' => $event_owner->last_name,
			);

			# Write header if this is a test-run
			if ($debug) {
				echo '<hr><h3>Mail til beboer, engelsk: sendes' . (FÆLLESHUS_INTERNET_BRUGER_EN_TOGGLE ? "" : " ikke") . '</h3>';
			}

			# Send or echo email
			send_AKDTU_email($debug, $subject_replaces_en, $content_replaces_en, 'FÆLLESHUS_INTERNET_BRUGER_EN', $event_owner->user_email);
		}
	}
}

?>