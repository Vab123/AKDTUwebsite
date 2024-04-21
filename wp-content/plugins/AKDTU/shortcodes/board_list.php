<?php

# Add custom shortcode
add_shortcode("AKDTU-board-list", "AKDTU_board_list");

function person_as_string($apartment_text = "Lejlighed", $apartment = "", $name_text = "Navn", $name = "", $email_text = "Email", $email = "") {
	$person_as_string = '<table>';

	$person_as_string .= '<tr><td style="width: 0; border-bottom: none; padding: 10px;"><strong>' . $apartment_text . '</strong></td><td style="border-bottom: none; padding: 10px;">' . $apartment . '</td></tr>';
	$person_as_string .= '<tr><td style="width: 0; border-bottom: none; padding: 10px;"><strong>' . $name_text . '</strong></td><td style="border-bottom: none; padding: 10px;">' . $name . '</td></tr>';
	$person_as_string .= '<tr><td style="width: 0; border-bottom: none; padding: 10px;"><strong>' . $email_text . '</strong></td><td style="border-bottom: none; padding: 10px;"><a href="mailto:' . $email . '">' . $email . '</a></td></tr>';

	$person_as_string .= '</table>';

	return $person_as_string;
}

function AKDTU_board_list( $atts ){
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	# Default values
	$default = array(
		'chairman'			=> 'Formand',		# Format for output for date of document
		'deputy-chairman'	=> 'Næstformand',	# What to write in element text
		'members'			=> 'Medlemmer',		# Text written before element link
		'deputies'			=> 'Suppleanter',	# Text written after element link
		'apartment'			=> 'Lejlighed',		# Name of downloaded file: %1$s is year, %2$s is month, %3$s is day, %4$s is type
		'name'				=> 'Navn',			# Type of the items in the list
		'email'				=> 'Email',			# Type of the items in the list
		'none'				=> 'Ingen',			# 
    );

	# Combine default values and provided settings
    $values = shortcode_atts($default, $atts);

	$boardmembers = all_boardmember_ids();
	$boarddeputies = all_board_deputies_ids();

	$chairmen = array_filter($boardmembers, function ($boardmember_id) use($now) {return was_chairman_from_id($boardmember_id, $now);});
	$deputychairmen = array_filter($boardmembers, function ($boardmember_id) use($now) {return was_deputy_chairman_from_id($boardmember_id, $now);});
	$base_members = array_filter($boardmembers, function ($boardmember_id) use($now) {return !was_chairman_from_id($boardmember_id, $now) && !was_deputy_chairman_from_id($boardmember_id, $now);});

	$board_list = "";

	$board_list .= '<hr class="wp-block-separator has-alpha-channel-opacity"><h3 class="wp-block-heading">' . $values['chairman'] . '</h3>';
	if (count($chairmen) > 0) {
		$board_list .= join('<hr class="wp-block-separator has-css-opacity is-style-dots">', array_map(function ($person_id) use($values) {
			$user = get_user_by('ID', $person_id);
			$chairman_email = 'formand@akdtu.dk';

			return person_as_string(
				$values['apartment'],
				padded_apartment_number_from_id($person_id),
				$values['name'],
				$user->first_name . ' ' . $user->last_name,
				$values['email'],
				$chairman_email
			);
		}, $chairmen));
	}
	else {
		$board_list .= '<p><strong>' . $values['none'] . '</strong></p>';
	}
	
	$board_list .= '<hr class="wp-block-separator has-alpha-channel-opacity"><h3 class="wp-block-heading">' . $values['deputy-chairman'] . '</h3>';
	if (count($deputychairmen) > 0) {
		$board_list .= join('<hr class="wp-block-separator has-css-opacity is-style-dots">', array_map(function ($person_id) use($values) {
			$user = get_user_by('ID', $person_id);

			return person_as_string(
				$values['apartment'],
				padded_apartment_number_from_id($person_id),
				$values['name'],
				$user->first_name . ' ' . $user->last_name,
				$values['email'],
				board_email_from_id($person_id)
			);
		}, $deputychairmen));
	}
	else {
		$board_list .= '<p><strong>' . $values['none'] . '</strong></p>';
	}
		
	$board_list .= '<hr class="wp-block-separator has-alpha-channel-opacity"><h3 class="wp-block-heading">' . $values['members'] . '</h3>';
	if (count($base_members) > 0) {
		$board_list .= join('<hr class="wp-block-separator has-css-opacity is-style-dots">', array_map(function ($person_id) use($values) {
			$user = get_user_by('ID', $person_id);

			return person_as_string(
				$values['apartment'],
				padded_apartment_number_from_id($person_id),
				$values['name'],
				$user->first_name . ' ' . $user->last_name,
				$values['email'],
				board_email_from_id($person_id)
			);
		}, $base_members));
	}
	else {
		$board_list .= '<p><strong>' . $values['none'] . '</strong></p>';
	}
		
	$board_list .= '<hr class="wp-block-separator has-alpha-channel-opacity"><h3 class="wp-block-heading">' . $values['deputies'] . '</h3>';
	if (count($boarddeputies) > 0) {
		$board_list .= join('<hr class="wp-block-separator has-css-opacity is-style-dots">', array_map(function ($person_id) use($values) {
			$user = get_user_by('ID', $person_id);

			return person_as_string(
				$values['apartment'],
				padded_apartment_number_from_id($person_id),
				$values['name'],
				$user->first_name . ' ' . $user->last_name,
				$values['email'],
				board_email_from_id($person_id)
			);
		}, $boarddeputies));
	}
	else {
		$board_list .= '<p><strong>' . $values['none'] . '</strong></p>';
	}

	$board_list .= '<hr class="wp-block-separator has-alpha-channel-opacity">';

	# Return formatted list of documents
	return $board_list;
}
