<?php

# Add custom shortcode
add_shortcode("AKDTU-networkgroup-list", "AKDTU_networkgroup_list");

/**
 * Creates a list of the members and deputies of the network group, split into their respective roles
 * 
 * @param array $atts Array of settings to be displayed
 * 
 * Default values:
 *   'chairman'				=> 'Bestyrelsesformand',		# Text written as a headline for the chairman
 *	 'deputy-chairman'		=> 'Bestyrelsesnæstformand',	# Text written as a headline for the deputy chairman
 *	 'member'				=> 'Bestyrelsesmedlem',			# Text written as a headline for the ordinary board members
 *	 'deputy'				=> 'Bestyrelsessuppleant',		# Text written as a headline for the board deputies
 *	 'apartment'			=> 'Lejlighed',					# Text written before the apartment number of the board member or deputy
 *	 'list-style-type'		=> 'disclosure-closed',			# Type of the items in the list
 *	 'knet-representative'	=> 'K-Net repræsentant',		# Text written if the person is the representative of the dorm in K-Net
 *	 'knet-deputy'			=> 'K-Net suppleant',			# Text written if the person is the representative of the dorm in K-Net
 * 
 * @return string Formatted list of the members of the network group
 */
function AKDTU_networkgroup_list( $atts ){
	$now = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));

	# Default values
	$default = array(
		'chairman'				=> 'Bestyrelsesformand',		# Text written as a headline for the chairman
		'deputy-chairman'		=> 'Bestyrelsesnæstformand',	# Text written as a headline for the deputy chairman
		'member'				=> 'Bestyrelsesmedlem',			# Text written as a headline for the ordinary board members
		'deputy'				=> 'Bestyrelsessuppleant',		# Text written as a headline for the board deputies
		'apartment'				=> 'Lejlighed',					# Text written before the apartment number of the board member or deputy
		'list-style-type'		=> 'disclosure-closed',			# Type of the items in the list
		'knet-representative'	=> 'K-Net repræsentant',		# Text written if the person is the representative of the dorm in K-Net
		'knet-deputy'			=> 'K-Net suppleant',			# Text written if the person is the representative of the dorm in K-Net
    );

	# Combine default values and provided settings
    $values = shortcode_atts($default, $atts);

	$member_types = array(
		'vicevært' => '',
		'none' => '',
		'default' => $values['member'] . ', ',
		'deputy' => $values['deputy'] . ', ',
		'chairman' => $values['chairman'] . ', ',
		'deputy-chairman' => $values['deputy-chairman'] . ', '
	);
	
	$networkgroup_members = all_networkgroup_ids();
	
	// Prepare string for list
	$networkgroup_list = "";

	// Add info about chairmen to list
	$networkgroup_list .= '<ul style="list-style-type: ' . $values['list-style-type'] . ';">';
	if (count($networkgroup_members) > 0) {
		$networkgroup_list .= join('', array_map(function ($person_id) use($values, $now, $member_types) {
			$member_type = user_type_id_from_id($person_id, $now);

			return '<li>' . name_from_id($person_id) . ' — ' . $member_types[$member_type] . $values['apartment'] . ' ' . padded_apartment_number_from_id($person_id) . (is_KNet_representative_from_id($person_id) ? ' — ' . $values["knet-representative"] : '') . (is_KNet_deputy_from_id($person_id) ? ' — ' . $values["knet-deputy"] : '') . '</li>';
		}, $networkgroup_members));
	}

	// Add final separator
	$networkgroup_list .= '</ul>';

	# Return formatted list of board members and deputies
	return $networkgroup_list;
}
