<?php

function send_opkrævning_fælleshus($debug = false) {

	if (FÆLLESHUS_TO != '' || $debug) {
		global $wpdb;

		if ((new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('j') == '1' || $debug) {
			$mention_moved_users_months_before = 1; # If tenant has moved in/out within this amount of months, a note is set on the name to specify if new or old tenant should pay
			$mention_moved_users_date = new DateTime("first day of this month", new DateTimeZone('Europe/Copenhagen'));
			$mention_moved_users_date->setTime(0, 0, 0);
			$mention_moved_users_date->modify('-' . $mention_moved_users_months_before . ' month');
			$mention_moved_users_date = $mention_moved_users_date->format('Y-m-d');

			$month_ini = new DateTime("first day of " . ($debug ? "this" : "last") . " month", new DateTimeZone('Europe/Copenhagen'));
			$month_ini->setTime(0, 0, 0);
			$month_end = new DateTime("last day of " . ($debug ? "this" : "last") . " month", new DateTimeZone('Europe/Copenhagen'));
			$month_end->setTime(23, 59, 59);

			$month = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
			$month->setPattern('MMMM');
			$monthnum = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
			$monthnum->setPattern('MM');
			$year = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen');
			$year->setPattern('YYYY');

			$month_year = $month->format($month_ini) . " " . $year->format($month_ini);

			$price_to_pay = get_price_to_pay($month_ini, $month_end);
			$price_adjustments = get_price_adjustments($month_ini, $month_end);
			$final_price = get_final_price($price_to_pay, $price_adjustments);

			$query = $wpdb->prepare('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_membercreation WHERE allow_creation_date >= "' . $mention_moved_users_date . '" AND initial_reset = 1 ORDER BY allow_creation_date ASC, apartment_number ASC');
			$moved_users = $wpdb->get_col($query);

			$payment_info = '';
			$amnt_rentals = count(array_keys($final_price));
			if ($amnt_rentals > 0) {
				$it = 1;

				foreach ($final_price as $username => $price) {
					if ($price > 0) {
						$replaces = array(
							'#APT' => apartment_number_from_username($username) . (is_archive_user_from_username($username) ? ' (Tidligere beboer)' : (in_array(apartment_number_from_username($username), $moved_users) ? ' (Ny beboer)' : '')),
							'#PRICE' => $price
						);

						$payment_info .= str_replace(array_keys($replaces), $replaces, nl2br(FÆLLESHUS_FORMAT));

						if ($it < $amnt_rentals) {
							$payment_info .= '<br>';
						}
						$it++;
					}
				}
			} else {
				$payment_info = "Der var ingen udlejninger i " . $month_year . ", så intet skal opkræves.";
			}

			$subject_replaces = array(
				'#MONTHNUM' => $monthnum->format($month_ini),
				'#MONTH' => $month->format($month_ini),
				'#YEAR' => $year->format($month_ini)
			);

			$content_replaces = array(
				'#PAYMENT_INFO' => $payment_info,
				'#MONTHNUM' => $monthnum->format($month_ini),
				'#MONTH' => $month->format($month_ini),
				'#YEAR' => $year->format($month_ini)
			);

			send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FÆLLESHUS');
		}
	}
}
