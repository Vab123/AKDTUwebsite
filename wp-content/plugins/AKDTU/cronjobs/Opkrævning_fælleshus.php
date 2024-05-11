<?php

/**
 * @file Cron-job responsible for sending billing information for residents renting the common house
 */

/**
 * Cron-job responsible for sending billing information for residents renting the common house
 * 
 * @param bool $debug Flag, for whether the email should actually be sent (false), or if this is a test run to show sample results (true)
 */
function send_opkrævning_fælleshus($debug = false) {
	# Check if an email should be sent or echoed
	if (FÆLLESHUS_TO != '' || $debug) {
		global $wpdb;

		# Check if today is the first day of the month (Emails are only sent on the first day of the month)
		if ((new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('j') == '1' || $debug) {
			$mention_moved_users_months_before = 1; # If tenant has moved in/out within this amount of months, a note is set on the name to specify if new or old tenant should pay
			$mention_moved_users_date = new DateTime("first day of this month", new DateTimeZone('Europe/Copenhagen'));
			$mention_moved_users_date->setTime(0, 0, 0);
			$mention_moved_users_date->modify('-' . $mention_moved_users_months_before . ' month');
			$mention_moved_users_date = $mention_moved_users_date->format('Y-m-d');

			# First and last day of the previous month
			$month_ini = new DateTime("first day of " . ($debug ? "this" : "last") . " month", new DateTimeZone('Europe/Copenhagen'));
			$month_ini->setTime(0, 0, 0);
			$month_end = new DateTime("last day of " . ($debug ? "this" : "last") . " month", new DateTimeZone('Europe/Copenhagen'));
			$month_end->setTime(23, 59, 59);

			# Date formatters
			$month = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen', null, 'MMMM');
			$monthnum = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen', null, 'MM');
			$year = new IntlDateFormatter('da_DK', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen', null, 'YYYY');

			# Month and year of the previous month, formatted as string
			$month_year = $month->format($month_ini) . " " . $year->format($month_ini);

			# Get prices, price adjustments, and final prices
			$price_to_pay = get_price_to_pay($month_ini, $month_end);
			$price_adjustments = get_price_adjustments($month_ini, $month_end);
			$final_price = get_final_price($price_to_pay, $price_adjustments);

			# Get moved users
			$moved_users = $wpdb->get_col($wpdb->prepare('SELECT apartment_number FROM ' . $wpdb->prefix . 'swpm_allowed_membercreation WHERE allow_creation_date >= "' . $mention_moved_users_date . '" AND initial_reset = 1 ORDER BY allow_creation_date ASC, apartment_number ASC'));

			# Prepare string for payment info
			$payment_info = '';
			
			# Calculate the amount of rentals
			$amnt_rentals = count(array_keys($final_price));

			# Check if there has been any rentals
			if ($amnt_rentals > 0) {
				# There has been any rentals, so write info about the rentals

				# Counter of rentals
				$it = 1;

				# Go through all rentals
				foreach ($final_price as $username => $price) {
					# Check if the price is not zero
					if ($price > 0) {
						# Replacements for the format of the payment
						$replaces = array(
							'#APT' => padded_apartment_number_from_username($username) . (is_archive_user_from_username($username) ? ' (Tidligere beboer)' : (in_array(apartment_number_from_username($username), $moved_users) ? ' (Ny beboer)' : '')),
							'#PRICE' => number_format($price, 2, ",", "."),
						);

						# Append payment info
						$payment_info .= str_replace(array_keys($replaces), $replaces, nl2br(FÆLLESHUS_FORMAT));

						# If this is not the last rental, add a linebreak
						if ($it < $amnt_rentals) {
							$payment_info .= '<br>';
						}

						# Increment counter
						$it++;
					}
				}
			} else {
				# There were no rentals. Payment info reflects that there were no rentals

				# Replacements
				$replaces = array(
					'#MONYEAR' => $month_year,
				);

				# Append payment info
				$payment_info = str_replace(array_keys($replaces), $replaces, nl2br(FÆLLESHUS_NONE_FORMAT));
			}

			# Replacements for email subject
			$subject_replaces = array(
				'#MONTHNUM' => $monthnum->format($month_ini),
				'#MONTH' => $month->format($month_ini),
				'#YEAR' => $year->format($month_ini)
			);

			# Replacements for email content
			$content_replaces = array(
				'#PAYMENT_INFO' => $payment_info,
				'#MONTHNUM' => $monthnum->format($month_ini),
				'#MONTH' => $month->format($month_ini),
				'#YEAR' => $year->format($month_ini)
			);

			# Send or echo email
			send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FÆLLESHUS');
		}
	}
}
