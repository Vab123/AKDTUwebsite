<?php

add_action('wp_enqueue_scripts', 'AKDTU_enqueue_styles');

function AKDTU_enqueue_styles() {
	wp_enqueue_style(
		'child-style',
		get_stylesheet_uri()
	);
}

## \wp-content\plugins\events-manager\templates\forms\event-editor.php
pll_register_string('events-manager', 'Unauthorized Access', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Common room rental notice', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Your Details', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Name', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Email', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Event Name', 'events-manager-fælleshus');
pll_register_string('events-manager', 'The event name. Example: Birthday party', 'events-manager-fælleshus');
pll_register_string('events-manager', 'When', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Where', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Details', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Details about the event.', 'events-manager-fælleshus');
pll_register_string('events-manager', 'HTML allowed.', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Event Image', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Bookings/Registration', 'events-manager-fælleshus');

## \wp-content\plugins\events-manager\classes\em-tickets.php
pll_register_string('events-manager', 'You cannot delete tickets if there are any bookings associated with them. Please delete these bookings first.', 'events-manager-havedag');
pll_register_string('events-manager', 'Standard', 'events-manager-havedag');
pll_register_string('events-manager', 'Date', 'events-manager-havedag');
pll_register_string('events-manager', 'Price', 'events-manager-havedag');
pll_register_string('events-manager', 'Spaces', 'events-manager-havedag');

## \wp-content\plugins\events-manager\templates\tables\events.php
## used styles: __(), _e(), _e (), esc_html_e(), esc_html_e ()
pll_register_string('events-manager', 'Search Events', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Pending', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Edit', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Delete', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Draft', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Add New', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'None', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'WARNING! You will delete ALL recurrences of this event, including booking history associated with any event in this recurrence. To keep booking information, go to the relevant single event and save it to detach it from this recurrence series.', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Edit Recurring Events', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Upcoming', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Past Events', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Location', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Date and time', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Duplicate this event', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'All Events', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'My Events', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Bookings', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Booked', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'No events', 'events-manager-mine-begivenheder');

## replaces for calendar
pll_register_string('events-manager', 'RENTAL_BEFORE_APARTMENTNUM_NOTAPPROVED', 'events-manager-calendar');
pll_register_string('events-manager', 'RENTAL_BEFORE_APARTMENTNUM_APPROVED', 'events-manager-calendar');
pll_register_string('events-manager', 'RENTAL_AFTER_APARTMENTNUM_NOTAPPROVED', 'events-manager-calendar');
pll_register_string('events-manager', 'RENTAL_AFTER_APARTMENTNUM_APPROVED', 'events-manager-calendar');

pll_register_string('events-manager', 'VICEVÆRT_RESERVATION', 'events-manager-calendar');

pll_register_string('events-manager', 'EXPORT_RENTAL_BEFORE_APARTMENTNUM_NOTAPPROVED', 'events-manager-calendar-export');
pll_register_string('events-manager', 'EXPORT_RENTAL_BEFORE_APARTMENTNUM_APPROVED', 'events-manager-calendar-export');
pll_register_string('events-manager', 'EXPORT_RENTAL_AFTER_APARTMENTNUM_NOTAPPROVED', 'events-manager-calendar-export');
pll_register_string('events-manager', 'EXPORT_RENTAL_AFTER_APARTMENTNUM_APPROVED', 'events-manager-calendar-export');
pll_register_string('events-manager', 'EXPORT_RENTAL_DESCRIPTION_NOTAPPROVED', 'events-manager-calendar-export');
pll_register_string('events-manager', 'EXPORT_RENTAL_DESCRIPTION_APPROVED', 'events-manager-calendar-export');

## \wp-content\plugins\events-manager\templates\tables\events.php
## used styles: _e(), _e (), esc_html_e()
pll_register_string('events-manager', 'Rental of common house format with %s', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'From ', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'to', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Startdate', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Enddate', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Starttime', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Endtime', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Event starts at', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'All day', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Timezone', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'This event spans every day between the beginning and end date, with start/end times applying to each day.', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'The expected price for the rental is:', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Default common house rental price', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Common house rental price, pre', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Common house rental price, post', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Common house rental price, invalid', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Common house rental price month, invalid', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Price calculation steps', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'which will be paid with the rent at the end of', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Default common house rental month', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Month_array', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Rental is free for members of the board.', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Submit event', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Update event', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', '12:00 (noon)', 'events-manager-mine-begivenheder');
pll_register_string('events-manager', 'Rental price', 'events-manager-mine-begivenheder');

## wp-content\plugins\events-manager\templates\templates\my-bookings.php
## used styles: _e(), _e (), esc_html_e()
pll_register_string('events-manager', 'Garden day', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'Status', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'You do not have any bookings.', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'Please <a href="%s">Log In</a> to view your bookings.', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'You do not have any bookings.', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'Cancel', 'events-manager-mine-tilmeldinger');

## D:\xampp\apps\wordpress\htdocs\wp-content\plugins\events-manager\classes\em-booking.php
## used styles: __()
pll_register_string('events-manager', 'Pending', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'Approved', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'Rejected', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'Cancelled', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'Awaiting Online Payment', 'events-manager-mine-tilmeldinger');
pll_register_string('events-manager', 'Awaiting Payment', 'events-manager-mine-tilmeldinger');

## wp-content\plugins\events-manager\templates\placeholders\bookingform.php
## used styles: 
pll_register_string('events-manager', 'You are already signed up for this event.', 'events-manager-havedag');
pll_register_string('events-manager', 'Manage my signups', 'events-manager-havedag');
pll_register_string('events-manager', 'Comment for allergies', 'events-manager-havedag');

## wp-content\plugins\events-manager\classes\em-event.php
pll_register_string('events-manager', 'Event already exists during that time.', 'events-manager-fælleshus');
pll_register_string('events-manager', 'Event start and end at the same time. This is not possible.', 'events-manager-fælleshus');

## wp-content\plugins\events-manager\templates\forms\bookingform\tickets-list.php
pll_register_string('events-manager', 'No spaces left', 'events-manager-havedag');

## \wp-content\plugins\simple-membership\views\login.php
## used styles: echo SwpmUtils::_()
pll_register_string('simple-membership', 'Forgot Password?', 'simple-membership-login');
pll_register_string('simple-membership', 'Login', 'simple-membership-login');
pll_register_string('simple-membership', 'Remember Me', 'simple-membership-login');
pll_register_string('simple-membership', 'Password', 'simple-membership-login');
pll_register_string('simple-membership', 'Username or Email', 'simple-membership-login');
pll_register_string('simple-membership', 'If you have recently moved in and have no account yet, contact the network group.', 'simple-membership-login');
pll_register_string('simple-membership', 'Login notice', 'simple-membership-login');

## wp-content\plugins\simple-membership\classes\class.swpm-auth.php
pll_register_string('simple-membership', 'Password Empty or Invalid.', 'simple-membership-login');
pll_register_string('simple-membership', 'User Not Found.', 'simple-membership-login');

## wp-content\plugins\simple-membership\views\loggedin.php
pll_register_string('simple-membership', 'Edit Profile', 'simple-membership-login');
pll_register_string('simple-membership', 'Logout', 'simple-membership-login');
pll_register_string('simple-membership', 'Membership', 'simple-membership-login');
pll_register_string('simple-membership', 'Logged in as', 'simple-membership-login');
pll_register_string('simple-membership', 'Loggedin notice', 'simple-membership-login');

## wp-content\plugins\simple-membership\views\add.php
pll_register_string('simple-membership', 'Apartment number', 'simple-membership-add');
pll_register_string('simple-membership', 'Are you a temporary renter?', 'simple-membership-add');
pll_register_string('simple-membership', ' - Only used for confirmation', 'simple-membership-add');
pll_register_string('simple-membership', 'Wrong apartment number recieved.', 'simple-membership-add');
pll_register_string('simple-membership', 'A user with that username already exists.', 'simple-membership-add');
pll_register_string('simple-membership', 'Creating a user for this apartment is currently not permitted.', 'simple-membership-add');
pll_register_string('simple-membership', 'Email already exists.', 'simple-membership-add');
pll_register_string('simple-membership', 'Registration completed message', 'simple-membership-add');
pll_register_string('simple-membership', 'Confirmation email sent', 'simple-membership-add');
pll_register_string('simple-membership', 'Registration form message', 'simple-membership-add');
pll_register_string('simple-membership', 'You are already logged in. You dont need to create another account. So the registration form is hidden.', 'simple-membership-add');

## wp-content\plugins\simple-membership\views\edit.php
pll_register_string('simple-membership', 'Username', 'simple-membership-edit');
pll_register_string('simple-membership', 'Email', 'simple-membership-edit');
pll_register_string('simple-membership', 'Password', 'simple-membership-edit');
pll_register_string('simple-membership', 'Repeat Password', 'simple-membership-edit');
pll_register_string('simple-membership', 'First Name', 'simple-membership-edit');
pll_register_string('simple-membership', 'Last Name', 'simple-membership-edit');
pll_register_string('simple-membership', 'Leave empty to keep the current password', 'simple-membership-edit');

## wp-content\plugins\simple-membership\classes\class.swpm-front-registration.php
pll_register_string('simple-membership', 'Profile updated successfully.', 'simple-membership-edit');
pll_register_string('simple-membership', 'Profile updated successfully. You will need to re-login since you changed your password.', 'simple-membership-edit');
pll_register_string('simple-membership', 'Email address not valid.', 'simple-membership-edit');
pll_register_string('simple-membership', 'No user found with that email address.', 'simple-membership-edit');
pll_register_string('simple-membership', 'Email Address: ', 'simple-membership-edit');
pll_register_string('simple-membership', 'New password has been sent to your email address.', 'simple-membership-edit');

## wp-content\plugins\simple-membership\classes\class.swpm-access-control.php
pll_register_string('simple-membership', 'You need to login to view this content. ', 'simple-membership-access-control');


## wp-content\plugins\AKDTU\shortcodes\contact-form-7-custom-tags.php
pll_register_string('contact-form-7', 'The date should be after %1$s-%2$s-%3$s %4$s:%5$s:%6$s but is %7$s-%8$s-%9$s %10$s:%11$s:%12$s', 'contact-form-7-custom-datetime-tag');
pll_register_string('contact-form-7', 'The date should be before %1$s-%2$s-%3$s %4$s:%5$s:%6$s but is %7$s-%8$s-%9$s %10$s:%11$s:%12$s', 'contact-form-7-custom-datetime-tag');


/**
 * Restrict sending emails when changing to and from default user
 */
## Dont send email change notifications when changing to or from @akdtu.dk
if (!function_exists('send_email_change_email')) {
	function send_email_change_email($return, $user, $userdata) {
		return $return && strtolower(explode("@", $userdata['user_email'])[1]) != 'akdtu.dk' && strtolower(explode("@", $user['user_email'])[1]) != 'akdtu.dk';
	}
	add_filter('send_email_change_email', 'send_email_change_email', 10, 3);
}
## Dont send password change notifications when changing to or from @akdtu.dk
if (!function_exists('send_password_change_email')) {
	function send_password_change_email($return, $user, $userdata) {
		return $return && strtolower(explode("@", $userdata['user_email'])[1]) != 'akdtu.dk' && strtolower(explode("@", $user['user_email'])[1]) != 'akdtu.dk';
	}
	add_filter('send_password_change_email', 'send_password_change_email', 10, 3);
}

/**
 * Custom user role for board-member user profiles
 */
add_role(
	'board_member',
	'Bestyrelsesmedlem',
	array(
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
	)
);
add_role(
	'deputy',
	'Bestyrelsessuppleant',
	array(
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
	)
);
add_role(
	'vicevaert',
	'Vicevært',
	array(
		'read' => true,
		'delete_events' => true,
		'edit_events' => true,
		'read_private_events' => true,
		'read_private_locations' => true,
		'read_others_locations' => true,
	)
);

/**
 * Remove admin bar from certain user roles
 */
$allowed_roles = ['administrator', 'editor', 'board_member', 'deputy'];
if (is_user_logged_in() && empty(array_intersect(wp_get_current_user()->roles, $allowed_roles))) {
	add_filter('show_admin_bar', '__return_false');
}


add_action( 'show_user_profile', 'AKDTU_define_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'AKDTU_define_custom_user_profile_fields' );

function AKDTU_define_custom_user_profile_fields( $user ) {
	$saved_apartment_number = get_user_meta( $user->ID, 'apartment_number', true );
	$saved_user_type = get_user_meta( $user->ID, 'user_type', true );
	$saved_knet_type = get_user_meta( $user->ID, 'knet_type', true );
	$saved_is_active = get_user_meta( $user->ID, 'is_active', true ); ?>
  <h3>AKDTU information</h3>

  <table class="form-table">
    <tr>
      <th><label for="aparment_number">Lejlighed</label></th>
      <td>
        <input type="number" name="apartment_number" id="apartment_number" value="<?php echo esc_attr($saved_apartment_number); ?>" />
      </td>
    </tr>
    <tr>
      <th><label for="user_type">Bruger-type</label></th>
      <td>
        <select name="user_type" id="user_type">
			<?php global $AKDTU_USER_TYPES;
			foreach ($AKDTU_USER_TYPES as $key => $definition): ?>
				<option value="<?php echo $definition["id"]; ?>"<?php echo $definition["id"] == $saved_user_type ? ' selected' : ''; ?>><?php echo $definition['name']; ?></option>
			<?php endforeach; ?>
		</select>
      </td>
    </tr>
    <tr>
      <th><label for="knet_type">Netværksgruppe-type</label></th>
      <td>
        <select name="knet_type" id="knet_type">
			<?php global $KNET_USER_TYPES;
			foreach ($KNET_USER_TYPES as $key => $definition): ?>
				<option value="<?php echo $definition["id"]; ?>"<?php echo $definition["id"] == $saved_knet_type ? ' selected' : ''; ?>><?php echo $definition['name']; ?></option>
			<?php endforeach; ?>
		</select>
      </td>
    </tr>
    <tr>
      <th><label for="is_active">Aktiv profil</label></th>
      <td>
        <input type="checkbox" name="is_active" id="is_active"<?php echo $saved_is_active ? ' checked' : ''; ?> />
      </td>
    </tr>
  </table>
<?php }

add_action( 'personal_options_update', 'AKDTU_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'AKDTU_save_custom_user_profile_fields' );

function AKDTU_save_custom_user_profile_fields( $user_id ) {
  if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
    return;
  }

  if ( !current_user_can( 'edit_user', $user_id ) ) {
    return;
  }

  update_user_meta( $user_id, 'user_type', $_POST['user_type'] );
  update_user_meta( $user_id, 'knet_type', $_POST['knet_type'] );
  update_user_meta( $user_id, 'apartment_number', $_POST['apartment_number'] );
  update_user_meta( $user_id, 'is_active', isset($_POST['is_active']) );
}
