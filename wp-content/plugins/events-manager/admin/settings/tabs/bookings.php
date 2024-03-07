<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- BOOKING OPTIONS -->
<div class="em-menu-bookings em-menu-group"  <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>	
	
	<div  class="postbox " id="em-opt-bookings-general" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__( '%s Options', 'events-manager'),__('General','events-manager')); ?> </span></h3>
	<div class="inside">
		<table class='form-table'> 
			<?php 
			em_options_radio_binary ( __( 'Allow guest bookings?', 'events-manager'), 'dbem_bookings_anonymous', __( 'If enabled, guest visitors can supply an email address and a user account will automatically be created for them along with their booking. They will be also be able to log back in with that newly created account.', 'events-manager') );
			em_options_radio_binary ( __( 'Approval Required?', 'events-manager'), 'dbem_bookings_approval', __( 'Bookings will not be confirmed until the event administrator approves it.', 'events-manager').' '.__( 'This setting is not applicable when using payment gateways, see individual gateways for approval settings.', 'events-manager'));
			em_options_radio_binary ( __( 'Reserved unconfirmed spaces?', 'events-manager'), 'dbem_bookings_approval_reserved', __( 'By default, event spaces become unavailable once there are enough CONFIRMED bookings. To reserve spaces even if unapproved, choose yes.', 'events-manager') );
			em_options_radio_binary ( __( 'Can users cancel their booking?', 'events-manager'), 'dbem_bookings_user_cancellation', __( 'If enabled, users can cancel their bookings themselves from their bookings page.', 'events-manager') );
			em_options_radio_binary ( __( 'Allow overbooking when approving?', 'events-manager'), 'dbem_bookings_approval_overbooking', __( 'If you get a lot of pending bookings and you decide to allow more bookings than spaces allow, setting this to yes will allow you to override the event space limit when manually approving.', 'events-manager') );
			em_options_radio_binary ( __( 'Allow double bookings?', 'events-manager'), 'dbem_bookings_double', __( 'If enabled, users can book an event more than once.', 'events-manager') );
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	
	<div  class="postbox " id="em-opt-pricing-options" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__( '%s Options', 'events-manager'),__('Pricing','events-manager')); ?> </span></h3>
	<div class="inside">
		<table class='form-table'>
			<?php
			/* Tax & Currency */
			em_options_select ( __( 'Currency', 'events-manager'), 'dbem_bookings_currency', em_get_currencies()->names, __( 'Choose your currency for displaying event pricing.', 'events-manager') );
			em_options_input_text ( __( 'Thousands Separator', 'events-manager'), 'dbem_bookings_currency_thousands_sep', '<code>'.get_option('dbem_bookings_currency_thousands_sep')." = ".em_get_currency_symbol().'100<strong>'.get_option('dbem_bookings_currency_thousands_sep').'</strong>000<strong>'.get_option('dbem_bookings_currency_decimal_point').'</strong>00</code>' );
			em_options_input_text ( __( 'Decimal Point', 'events-manager'), 'dbem_bookings_currency_decimal_point', '<code>'.get_option('dbem_bookings_currency_decimal_point')." = ".em_get_currency_symbol().'100<strong>'.get_option('dbem_bookings_currency_decimal_point').'</strong>00</code>' );
			em_options_input_text ( __( 'Currency Format', 'events-manager'), 'dbem_bookings_currency_format', __('Choose how prices are displayed. <code>@</code> will be replaced by the currency symbol, and <code>#</code> will be replaced by the number.','events-manager').' <code>'.get_option('dbem_bookings_currency_format')." = ".em_get_currency_formatted('10000000').'</code>');
			em_options_input_text ( __( 'Tax Rate', 'events-manager'), 'dbem_bookings_tax', __( 'Add a tax rate to your ticket prices (entering 10 will add 10% to the ticket price).', 'events-manager') );
			em_options_radio_binary ( __( 'Add tax to ticket price?', 'events-manager'), 'dbem_bookings_tax_auto_add', __( 'When displaying ticket prices and booking totals, include the tax automatically?', 'events-manager') );
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox --> 
	
	<div  class="postbox " id="em-opt-booking-feedbacks" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e( 'Customize Feedback Messages', 'events-manager'); ?> </span></h3>
	<div class="inside">
		<p><?php _e('Below you will find texts that will be displayed to users in various areas during the bookings process, particularly on booking forms.','events-manager'); ?></p>
		<table class='form-table'>
			<tr class="em-header"><td colspan='2'><h4><?php _e('My Bookings messages','events-manager') ?></h4></td></tr>
			<?php 
			em_options_input_text ( __( 'Booking Cancelled (Danish)', 'events-manager'), 'dbem_booking_feedback_cancelled_da', __( 'When a user cancels their booking, this message will be displayed confirming the cancellation.', 'events-manager') );
			em_options_input_text ( __( 'Booking Cancelled (English)', 'events-manager'), 'dbem_booking_feedback_cancelled_en', __( 'When a user cancels their booking, this message will be displayed confirming the cancellation.', 'events-manager') );
			em_options_input_text ( __( 'Booking Cancellation Warning (Danish)', 'events-manager'), 'dbem_booking_warning_cancel_da', __( 'When a user chooses to cancel a booking, this warning is displayed for them to confirm.', 'events-manager') );
			em_options_input_text ( __( 'Booking Cancellation Warning (English)', 'events-manager'), 'dbem_booking_warning_cancel_en', __( 'When a user chooses to cancel a booking, this warning is displayed for them to confirm.', 'events-manager') );
			?>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Booking form texts/messages','events-manager') ?></h4></td></tr>
			<?php
			em_options_input_text ( __( 'Bookings disabled (Danish)', 'events-manager'), 'dbem_bookings_form_msg_disabled_da', __( 'An event with no bookings.', 'events-manager') );
			em_options_input_text ( __( 'Bookings disabled (English)', 'events-manager'), 'dbem_bookings_form_msg_disabled_en', __( 'An event with no bookings.', 'events-manager') );
			em_options_input_text ( __( 'Bookings closed (Danish)', 'events-manager'), 'dbem_bookings_form_msg_closed_da', __( 'Bookings have closed (e.g. event has started).', 'events-manager') );
			em_options_input_text ( __( 'Bookings closed (English)', 'events-manager'), 'dbem_bookings_form_msg_closed_en', __( 'Bookings have closed (e.g. event has started).', 'events-manager') );
			em_options_input_text ( __( 'Fully booked (Danish)', 'events-manager'), 'dbem_bookings_form_msg_full_da', __( 'Event is fully booked.', 'events-manager') );
			em_options_input_text ( __( 'Fully booked (English)', 'events-manager'), 'dbem_bookings_form_msg_full_en', __( 'Event is fully booked.', 'events-manager') );
			?>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Booking form feedback messages','events-manager') ?></h4></td></tr>
			<tr><td colspan='2'><?php _e('When a booking is made by a user, a feedback message is shown depending on the result, which can be customized below.','events-manager'); ?></td></tr>
			<?php
			em_options_input_text ( __( 'Successful booking (Danish)', 'events-manager'), 'dbem_booking_feedback_da', __( 'When a booking is registered and confirmed.', 'events-manager') );
			em_options_input_text ( __( 'Successful booking (English)', 'events-manager'), 'dbem_booking_feedback_en', __( 'When a booking is registered and confirmed.', 'events-manager') );
			em_options_input_text ( __( 'Successful pending booking (Danish)', 'events-manager'), 'dbem_booking_feedback_pending_da', __( 'When a booking is registered but pending.', 'events-manager') );
			em_options_input_text ( __( 'Successful pending booking (English)', 'events-manager'), 'dbem_booking_feedback_pending_en', __( 'When a booking is registered but pending.', 'events-manager') );
			em_options_input_text ( __( 'Not enough spaces (Danish)', 'events-manager'), 'dbem_booking_feedback_full_da', __( 'When a booking cannot be made due to lack of spaces.', 'events-manager') );
			em_options_input_text ( __( 'Not enough spaces (English)', 'events-manager'), 'dbem_booking_feedback_full_en', __( 'When a booking cannot be made due to lack of spaces.', 'events-manager') );
			em_options_input_text ( __( 'Errors (Danish)', 'events-manager'), 'dbem_booking_feedback_error_da', __( 'When a booking cannot be made due to an error when filling the form. Below this, there will be a dynamic list of errors.', 'events-manager') );
			em_options_input_text ( __( 'Errors (English)', 'events-manager'), 'dbem_booking_feedback_error_en', __( 'When a booking cannot be made due to an error when filling the form. Below this, there will be a dynamic list of errors.', 'events-manager') );
			em_options_input_text ( __( 'Email Exists (Danish)', 'events-manager'), 'dbem_booking_feedback_email_exists_da', __( 'When a guest tries to book using an email registered with a user account.', 'events-manager') );
			em_options_input_text ( __( 'Email Exists (English)', 'events-manager'), 'dbem_booking_feedback_email_exists_en', __( 'When a guest tries to book using an email registered with a user account.', 'events-manager') );
			em_options_input_text ( __( 'User must log in (Danish)', 'events-manager'), 'dbem_booking_feedback_log_in_da', __( 'When a user must log in before making a booking.', 'events-manager') );
			em_options_input_text ( __( 'User must log in (English)', 'events-manager'), 'dbem_booking_feedback_log_in_en', __( 'When a user must log in before making a booking.', 'events-manager') );
			em_options_input_text ( __( 'Error mailing user (Danish)', 'events-manager'), 'dbem_booking_feedback_nomail_da', __( 'If a booking is made and an email cannot be sent, this is added to the success message.', 'events-manager') );
			em_options_input_text ( __( 'Error mailing user (English)', 'events-manager'), 'dbem_booking_feedback_nomail_en', __( 'If a booking is made and an email cannot be sent, this is added to the success message.', 'events-manager') );
			em_options_input_text ( __( 'Already booked (Danish)', 'events-manager'), 'dbem_booking_feedback_already_booked_da', __( 'If the user made a previous booking and cannot double-book.', 'events-manager') );
			em_options_input_text ( __( 'Already booked (English)', 'events-manager'), 'dbem_booking_feedback_already_booked_en', __( 'If the user made a previous booking and cannot double-book.', 'events-manager') );
			em_options_input_text ( __( 'No spaces booked (Danish)', 'events-manager'), 'dbem_booking_feedback_min_space_da', __( 'If the user tries to make a booking without requesting any spaces.', 'events-manager') );$notice_full = __('Sold Out', 'events-manager');
			em_options_input_text ( __( 'No spaces booked (English)', 'events-manager'), 'dbem_booking_feedback_min_space_en', __( 'If the user tries to make a booking without requesting any spaces.', 'events-manager') );$notice_full = __('Sold Out', 'events-manager');
			em_options_input_text ( __( 'Maximum spaces per booking (Danish)', 'events-manager'), 'dbem_booking_feedback_spaces_limit_da', __( 'If the user tries to make a booking with spaces that exceeds the maximum number of spaces per booking.', 'events-manager').' '. __('%d will be replaced by a number.','events-manager') );
			em_options_input_text ( __( 'Maximum spaces per booking (English)', 'events-manager'), 'dbem_booking_feedback_spaces_limit_en', __( 'If the user tries to make a booking with spaces that exceeds the maximum number of spaces per booking.', 'events-manager').' '. __('%d will be replaced by a number.','events-manager') );
			?>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Booking button feedback messages','events-manager') ?></h4></td></tr>
			<tr><td colspan='2'><?php echo sprintf(__('With the %s placeholder, the below texts will be used.','events-manager'),'<code>#_BOOKINGBUTTON</code>'); ?></td></tr>
			<?php			
			em_options_input_text ( __( 'User can book (Danish)', 'events-manager'), 'dbem_booking_button_msg_book_da', '');
			em_options_input_text ( __( 'User can book (English)', 'events-manager'), 'dbem_booking_button_msg_book_en', '');
			em_options_input_text ( __( 'Booking in progress (Danish)', 'events-manager'), 'dbem_booking_button_msg_booking_da', '');
			em_options_input_text ( __( 'Booking in progress (English)', 'events-manager'), 'dbem_booking_button_msg_booking_en', '');
			em_options_input_text ( __( 'Booking complete (Danish)', 'events-manager'), 'dbem_booking_button_msg_booked_da', '');
			em_options_input_text ( __( 'Booking complete (English)', 'events-manager'), 'dbem_booking_button_msg_booked_en', '');
			em_options_input_text ( __( 'Booking already made (Danish)', 'events-manager'), 'dbem_booking_button_msg_already_booked_da', '');
			em_options_input_text ( __( 'Booking already made (English)', 'events-manager'), 'dbem_booking_button_msg_already_booked_en', '');
			em_options_input_text ( __( 'Booking error (Danish)', 'events-manager'), 'dbem_booking_button_msg_error_da', '');
			em_options_input_text ( __( 'Booking error (English)', 'events-manager'), 'dbem_booking_button_msg_error_en', '');
			em_options_input_text ( __( 'Event fully booked (Danish)', 'events-manager'), 'dbem_booking_button_msg_full_da', '');
			em_options_input_text ( __( 'Event fully booked (English)', 'events-manager'), 'dbem_booking_button_msg_full_en', '');
			em_options_input_text ( __( 'Bookings closed (Danish)', 'events-manager'), 'dbem_booking_button_msg_closed_da', '');
			em_options_input_text ( __( 'Bookings closed (English)', 'events-manager'), 'dbem_booking_button_msg_closed_en', '');
			em_options_input_text ( __( 'Cancel (Danish)', 'events-manager'), 'dbem_booking_button_msg_cancel_da', '');
			em_options_input_text ( __( 'Cancel (English)', 'events-manager'), 'dbem_booking_button_msg_cancel_en', '');
			em_options_input_text ( __( 'Cancelation in progress (Danish)', 'events-manager'), 'dbem_booking_button_msg_canceling_da', '');
			em_options_input_text ( __( 'Cancelation in progress (English)', 'events-manager'), 'dbem_booking_button_msg_canceling_en', '');
			em_options_input_text ( __( 'Cancelation complete (Danish)', 'events-manager'), 'dbem_booking_button_msg_cancelled_da', '');
			em_options_input_text ( __( 'Cancelation complete (English)', 'events-manager'), 'dbem_booking_button_msg_cancelled_en', '');
			em_options_input_text ( __( 'Cancelation error (Danish)', 'events-manager'), 'dbem_booking_button_msg_cancel_error_da', '');
			em_options_input_text ( __( 'Cancelation error (English)', 'events-manager'), 'dbem_booking_button_msg_cancel_error_en', '');
			
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox --> 
	
	<div  class="postbox " id="em-opt-booking-form-options" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__( '%s Options', 'events-manager'),__('Booking Form','events-manager')); ?> </span></h3>
	<div class="inside">
		<table class='form-table'>
			<?php
			em_options_radio_binary ( __( 'Display login form?', 'events-manager'), 'dbem_bookings_login_form', __( 'Choose whether or not to display a login form in the booking form area to remind your members to log in before booking.', 'events-manager') );
			em_options_input_text ( __( 'Submit button text (Danish)', 'events-manager'), 'dbem_bookings_submit_button_da', sprintf(__( 'The text used by the submit button. To use an image instead, enter the full url starting with %s or %s.', 'events-manager'), '<code>http://</code>','<code>https://</code>') );
			em_options_input_text ( __( 'Submit button text (English)', 'events-manager'), 'dbem_bookings_submit_button_en', sprintf(__( 'The text used by the submit button. To use an image instead, enter the full url starting with %s or %s.', 'events-manager'), '<code>http://</code>','<code>https://</code>') );
			do_action('em_options_booking_form_options');
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	
	<div  class="postbox " id="em-opt-ticket-options" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__( '%s Options', 'events-manager'),__('Ticket','events-manager')); ?> </span></h3>
	<div class="inside">
		<table class='form-table'>
			<?php
			em_options_radio_binary ( __( 'Single ticket mode?', 'events-manager'), 'dbem_bookings_tickets_single', __( 'In single ticket mode, users can only create one ticket per event (and will not see options to add more tickets).', 'events-manager') );
			em_options_radio_binary ( __( 'Show ticket table in single ticket mode?', 'events-manager'), 'dbem_bookings_tickets_single_form', __( 'If you prefer a ticket table like with multiple tickets, even for single ticket events, enable this.', 'events-manager') );
			em_options_radio_binary ( __( 'Show unavailable tickets?', 'events-manager'), 'dbem_bookings_tickets_show_unavailable', __( 'You can choose whether or not to show unavailable tickets to visitors.', 'events-manager') );
			em_options_radio_binary ( __( 'Show member-only tickets?', 'events-manager'), 'dbem_bookings_tickets_show_member_tickets', sprintf(__('%s must be set to yes for this to work.', 'events-manager'), '<strong>'.__( 'Show unavailable tickets?', 'events-manager').'</strong>').' '.__( 'If there are member-only tickets, you can choose whether or not to show these tickets to guests.','events-manager') );
			
			em_options_radio_binary ( __( 'Show multiple tickets if logged out?', 'events-manager'), 'dbem_bookings_tickets_show_loggedout', __( 'If guests cannot make bookings, they will be asked to register in order to book. However, enabling this will still show available tickets.', 'events-manager') );
			em_options_radio_binary ( __( 'Enable custom ticket ordering?', 'events-manager'), 'dbem_bookings_tickets_ordering', __( 'When enabled, users can custom-order their tickets using drag and drop. If enabled, saved ordering supercedes the default ticket ordering below.', 'events-manager') );
			$ticket_orders = apply_filters('em_tickets_orderby_options', array(
				'ticket_price DESC, ticket_name ASC'=>__('Ticket Price (Descending)','events-manager'),
				'ticket_price ASC, ticket_name ASC'=>__('Ticket Price (Ascending)','events-manager'),
				'ticket_name ASC, ticket_price DESC'=>__('Ticket Name (Ascending)','events-manager'),
				'ticket_name DESC, ticket_price DESC'=>__('Ticket Name (Descending)','events-manager')
			));
			em_options_select ( __( 'Order Tickets By', 'events-manager'), 'dbem_bookings_tickets_orderby', $ticket_orders, __( 'Choose which order your tickets appear.', 'events-manager') );
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox --> 
		
	<div  class="postbox " id="em-opt-no-user-bookings" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e('No-User Booking Mode','events-manager'); ?> </span></h3>
	<div class="inside">
		<table class='form-table'>
			<tr><td colspan='2'>
				<p><?php _e('The option below allows you to disable user accounts, yet you will still see the supplied personal information for each booking.','events-manager'); ?></p>
				<p><?php _e('By default, when a booking is made by a user, this booking is tied to a user account, if the user is not registered nor logged in and guest bookings are enabled, an account will be created for them.','events-manager'); ?></p>
				<p><?php _e('Users with accounts (which would be created by other means when this mode is enabled) will still be able to log in and make bookings linked to their account as normal.','events-manager'); ?></p>
				<p><?php _e('<strong>Warning : </strong> Various features afforded to users with an account will not be available, e.g. viewing bookings. Once you enable this and select a user, modifying these values will prevent older non-user bookings from displaying the correct information.','events-manager'); ?></p>
			</td></tr>
			<?php
			em_options_radio_binary ( __( 'Enable No-User Booking Mode?', 'events-manager'), 'dbem_bookings_registration_disable', __( 'This disables user registrations for bookings.', 'events-manager') );
			em_options_radio_binary ( __( 'Allow bookings with registered emails?', 'events-manager'), 'dbem_bookings_registration_disable_user_emails', __( 'By default, if a guest tries to book an event using the email of a user account on your site they will be asked to log in, selecting yes will bypass this security measure.', 'events-manager').'<br />'.__('<strong>Warning : </strong> By enabling this, registered users will not be able to see bookings they make as guests in their "My Bookings" page.','events-manager') );
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->

	<div  class="postbox " id="em-opt-akdtu-settings" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__( '%s Options', 'events-manager'),__('AKDTU','events-manager')); ?> </span></h3>
	<div class="inside">
		<table class='form-table'>
			<?php
			em_options_input_text ( __( 'Limit users deleting events before start', 'events-manager'), 'dbem_bookings_delete_time', '<em>Users cannot delete events after this amount of days before the event starts. This ensures a log of past events.</em>' );
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox --> 
	
	<?php do_action('em_options_page_footer_bookings'); ?>
	
</div> <!-- .em-menu-bookings -->