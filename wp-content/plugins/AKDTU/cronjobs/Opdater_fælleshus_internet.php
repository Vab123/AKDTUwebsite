<?php

function send_opdater_fælleshus_internet($debug = false) {
	if (FÆLLESHUS_INTERNET_TO != '' || $debug) {
		## Cron-job frequency
		$run_every_hours_amount = 24; # How many hours go between each run of the cron-job

		## Date formats
		$date_da = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$date_da->setPattern('dd. MMMM YYYY');
		$time_da = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$time_da->setPattern('hh:mm');
		
		$date_en = new IntlDateFormatter('en_US', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$date_en->setPattern('dd. MMMM YYYY');
		$time_en = new IntlDateFormatter('en_US', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
		$time_en->setPattern('hh:mm');

		$password_struct = generate_password_info($run_every_hours_amount);

		$new_password = $password_struct['password'];
		$password_should_be_changed = $password_struct['should_be_changed'];
		$event = $password_struct['event'];
		$send_mail_to_renter = $password_struct['send_mail_to_renter'];
		$rented_state = $password_struct['rented_state'];

		if (!$debug && $password_should_be_changed) {
			$status = update_common_house_internet_password($password_struct);

			if ($status['password'] != $new_password) {
				wp_mail("netgruppen@akdtu.dk", "Ændring af adgangskode til fælleshusets internet fejlet", "Ændring af adgangskode til fælleshusets internet fejlet. Ingen yderligere mails er sendt. Dette skal undersøges nærmere.");
				return;
			}
		} else {
			$status = array(
				'password' => $new_password,
				'ssid' => get_router_settings()['ssid']
			);
		}

		if ($debug || $password_should_be_changed) {
			$subject_replaces = array();

			$content_replaces = array(
				'#RENTER' => ($rented_state == 0 ? "Ingen" : "") . ($rented_state == 1 ? "Lejlighed " . apartment_number_from_id($event->owner) : "") . ($rented_state == 2 ? "Bestyrelsen" : ""),
				'#SSID' => $status["ssid"],
				'#NEWPASS' => $status["password"],
			);

			if ($debug) {
				echo '<h3>Mail til bestyrelsen:</h3>';
			}
			send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FÆLLESHUS_INTERNET');

			if ($debug || $send_mail_to_renter) {
				$lang = pll_get_post_language($event->post_id);

				if ($debug || ($lang == "da" && FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE)) {
					if ($debug) {
						echo '<hr><h3>Mail til beboer, dansk: sendes' . (FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE ? "" : " ikke") . '</h3>';
					}

					$event_owner = get_user_by('ID', $event->owner);

					$subject_replaces_da = array();

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

					send_AKDTU_email($debug, $subject_replaces_da, $content_replaces_da, 'FÆLLESHUS_INTERNET_BRUGER_DA', $event_owner->user_email);
					send_AKDTU_email(false, $subject_replaces_da, $content_replaces_da, 'FÆLLESHUS_INTERNET_BRUGER_DA', "victor2@akdtu.dk");
				}

				if ($debug || ($lang == "en" && FÆLLESHUS_INTERNET_BRUGER_EN_TOGGLE)) {
					if ($debug) {
						echo '<hr><h3>Mail til beboer, engelsk: sendes' . (FÆLLESHUS_INTERNET_BRUGER_EN_TOGGLE ? "" : " ikke") . '</h3>';
					}

					$event_owner = get_user_by('ID', $event->owner);
					
					$subject_replaces_en = array();

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

					send_AKDTU_email($debug, $subject_replaces_en, $content_replaces_en, 'FÆLLESHUS_INTERNET_BRUGER_EN', $event_owner->user_email);
					send_AKDTU_email(false, $subject_replaces_en, $content_replaces_en, 'FÆLLESHUS_INTERNET_BRUGER_EN', "victor2@akdtu.dk");
				}
			}
		}
	}
}

?>