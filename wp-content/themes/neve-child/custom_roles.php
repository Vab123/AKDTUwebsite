<?php

/**
 * Custom user role for board-member user profiles
 */
add_role(
	'board_member',
	'Bestyrelsesmedlem',
	[
		'read' => true,
		'publish_events' => true,
		'delete_others_events' => true,
		'edit_others_events' => true,
		'delete_events' => true,
		'edit_events' => true,
		'read_private_events' => true,
		'read_private_locations' => true,
		'read_others_locations' => true,
		'manage_others_bookings' => true,
		'manage_bookings' => true,
	]
);
add_role(
	'deputy',
	'Bestyrelsessuppleant',
	[
		'read' => true,
		'publish_events' => true,
		'delete_others_events' => true,
		'edit_others_events' => true,
		'delete_events' => true,
		'edit_events' => true,
		'read_private_events' => true,
		'read_private_locations' => true,
		'read_others_locations' => true,
		'manage_others_bookings' => true,
		'manage_bookings' => true,
	]
);
add_role(
	'vicevaert',
	'Vicevært',
	[
		'read' => true,
		'delete_events' => true,
		'edit_events' => true,
		'read_private_events' => true,
		'read_private_locations' => true,
		'read_others_locations' => true,
	]
);