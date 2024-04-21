<?php

# Add custom shortcode
add_shortcode("AKDTU-board-list", "AKDTU_board_list");

/**
 * Formats a person as a string
 * 
 * @param string $apartment_text Text written before the apartment number of the person. Default: "Lejlighed"
 * @param string $apartment Apartment number of the person. Default: ""
 * @param string $name_text Text written before the name of the person. Default: "Navn"
 * @param string $name Name of the person. Default: ""
 * @param string $email_text Text written before the email adress of the person. Default: "Email"
 * @param string $email Email of the person. Default: ""
 * 
 * @return string Formatted string with details about the person
 */
function person_as_string($apartment_text = "Lejlighed", $apartment = "", $name_text = "Navn", $name = "", $email_text = "Email", $email = "") {
	$person_as_string = '<table>';

	$person_as_string .= '<tr><td style="width: 0; border-bottom: none; padding: 10px;"><strong>' . $apartment_text . '</strong></td><td style="border-bottom: none; padding: 10px;">' . $apartment . '</td></tr>';
	$person_as_string .= '<tr><td style="width: 0; border-bottom: none; padding: 10px;"><strong>' . $name_text . '</strong></td><td style="border-bottom: none; padding: 10px;">' . $name . '</td></tr>';
	$person_as_string .= '<tr><td style="width: 0; border-bottom: none; padding: 10px;"><strong>' . $email_text . '</strong></td><td style="border-bottom: none; padding: 10px;"><a href="mailto:' . $email . '">' . $email . '</a></td></tr>';

	$person_as_string .= '</table>';

	return $person_as_string;
}

/**
 * Creates a list of the members and deputies of the board, split into their respective roles
 * 
 * @param array $atts Array of settings to be displayed
 * 
 * Default values:
 *   'chairman'			=> 'Formand',		# Text written as a headline for the chairman
 *	 'deputy-chairman'	=> 'Næstformand',	# Text written as a headline for the deputy chairman
 *	 'members'			=> 'Medlemmer',		# Text written as a headline for the ordinary board members
 *	 'deputies'			=> 'Suppleanter',	# Text written as a headline for the board deputies
 *	 'apartment'		=> 'Lejlighed',		# Text written before the apartment number of the board member or deputy
 *	 'name'				=> 'Navn',			# Text written before the name of the board member or deputy
 *	 'email'			=> 'Email',			# Text written before the email adress of the board member or deputy
 *	 'none'				=> 'Ingen',			# Text written when nobody of a given group has been elected
 * 
 * @return string Formatted list of the members and deputies of the board
 */
function AKDTU_board_list( $atts ){
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	# Default values
	$default = array(
		'chairman'			=> 'Formand',		# Text written as a headline for the chairman
		'deputy-chairman'	=> 'Næstformand',	# Text written as a headline for the deputy chairman
		'members'			=> 'Medlemmer',		# Text written as a headline for the ordinary board members
		'deputies'			=> 'Suppleanter',	# Text written as a headline for the board deputies
		'apartment'			=> 'Lejlighed',		# Text written before the apartment number of the board member or deputy
		'name'				=> 'Navn',			# Text written before the name of the board member or deputy
		'email'				=> 'Email',			# Text written before the email adress of the board member or deputy
		'none'				=> 'Ingen',			# Text written when nobody of a given group has been elected
    );

	# Combine default values and provided settings
    $values = shortcode_atts($default, $atts);
	
	// Get board deputies
	$boarddeputies = all_board_deputies_ids();

	// Get board members
	$boardmembers = all_boardmember_ids();

	// Get current chairmen
	$chairmen = array_filter($boardmembers, function ($boardmember_id) use($now) {return was_chairman_from_id($boardmember_id, $now);});

	// Get current deputy chairmen
	$deputychairmen = array_filter($boardmembers, function ($boardmember_id) use($now) {return was_deputy_chairman_from_id($boardmember_id, $now);});

	// Get current base members
	$base_members = array_filter($boardmembers, function ($boardmember_id) use($now) {return !was_chairman_from_id($boardmember_id, $now) && !was_deputy_chairman_from_id($boardmember_id, $now);});

	// Prepare string for list
	$board_list = "";

	// Add info about chairmen to list
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
	
	// Add info about deputy chairmen to list
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

	// Add info about regular members to list
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
		
	// Add info about deputies to list
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

	// Add final separator
	$board_list .= '<hr class="wp-block-separator has-alpha-channel-opacity">';

	# Return formatted list of board members and deputies
	return $board_list;
}
