<?php

/**
 * @file Action to add a new vicevært to the system
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'add_vicevært' && isset($_REQUEST['first_name']) && isset($_REQUEST['last_name']) && isset($_REQUEST['username']) && isset($_REQUEST['email'])){
		add_vicevært($_REQUEST['first_name'], $_REQUEST['last_name'], $_REQUEST['username'], $_REQUEST['email']);
	}
}

/**
 * Add a new vicevært to the system
 * 
 * @param string $first_name First name of the new vicevært
 * @param string $last_name Last name of the new vicevært
 * @param string $username Username of the new vicevært
 * @param string $email Email address of the new vicevært
 * 
 * @return bool True if the vicevært was created successfully
 */
function add_vicevært($first_name, $last_name, $username, $email){
	# Check if a user already exists with that email
	if (email_exists( $email ) == false) {
		if (create_vicevært($first_name, $last_name, $username, $email)) {
			# Success. Write success message to admin interface
			new AKDTU_notice('success', "Vicevært med brugernavn {$username} oprettet.");

			return true;
		}

		new AKDTU_notice('error', 'Viceværten kunne ikke oprettes.');

		return false;
	}

	return false;
}
