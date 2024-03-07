<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- EMAIL OPTIONS -->
<div class="em-menu-emails em-menu-group" <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>
	
	<?php if ( !is_multisite() ) { em_admin_option_box_email(); } ?>

	<?php if( get_option('dbem_rsvp_enabled') ): ?>
	<div  class="postbox "  id="em-opt-booking-emails">
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Booking Email Templates', 'events-manager'); ?> </span></h3>
	<div class="inside">
	    <?php do_action('em_options_page_booking_email_templates_options_top'); ?>
		<table class='form-table'>
			<?php
			$email_subject_tip = __('You can disable this email by leaving the subject blank.','events-manager');
			em_options_input_text ( __( 'Email events admin?', 'events-manager'), 'dbem_bookings_notify_admin', __( "If you would like every event booking confirmation email sent to an administrator write their email here (leave blank to not send an email).", 'events-manager').' '.__('For multiple emails, separate by commas (e.g. email1@test.com,email2@test.com,etc.)','events-manager') );
			em_options_radio_binary ( __( 'Email event owner?', 'events-manager'), 'dbem_bookings_contact_email', __( 'Check this option if you want the event contact to receive an email when someone books places. An email will be sent when a booking is first made (regardless if confirmed or pending)', 'events-manager') );
			do_action('em_options_page_booking_email_templates_options_subtop');
			?>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Event Admin/Owner Emails', 'events-manager'); ?></h4></td></tr>
			<tbody class="em-subsection">
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Confirmed booking email','events-manager') ?></h5>
				<em><?php echo __('This is sent when a person\'s booking is confirmed. This will be sent automatically if approvals are required and the booking is approved. If approvals are disabled, this is sent out when a user first submits their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking confirmed email subject', 'events-manager'), 'dbem_bookings_contact_email_confirmed_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking confirmed email', 'events-manager'), 'dbem_bookings_contact_email_confirmed_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Pending booking email','events-manager') ?></h5>
				<em><?php echo __('This is sent when a person\'s booking is pending. If approvals are enabled, this is sent out when a user first submits their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking pending email subject', 'events-manager'), 'dbem_bookings_contact_email_pending_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking pending email', 'events-manager'), 'dbem_bookings_contact_email_pending_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Booking cancelled','events-manager') ?></h5>
				<em><?php echo __('An email will be sent to the event contact if someone cancels their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking cancelled email subject', 'events-manager'), 'dbem_bookings_contact_email_cancelled_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking cancelled email', 'events-manager'), 'dbem_bookings_contact_email_cancelled_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Rejected booking email','events-manager') ?></h5>
				<em><?php echo __( 'This will be sent to event admins when a booking is rejected.', 'events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking rejected email subject', 'events-manager'), 'dbem_bookings_contact_email_rejected_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking rejected email', 'events-manager'), 'dbem_bookings_contact_email_rejected_body', '' );
			?>
			</tbody>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Booked User Emails', 'events-manager'); ?></h4></td></tr>
			<tbody class="em-subsection">
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Confirmed booking email','events-manager') ?></h5>
				<em><?php echo __('This is sent when a person\'s booking is confirmed. This will be sent automatically if approvals are required and the booking is approved. If approvals are disabled, this is sent out when a user first submits their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking confirmed email subject', 'events-manager'), 'dbem_bookings_email_confirmed_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking confirmed email', 'events-manager'), 'dbem_bookings_email_confirmed_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Pending booking email','events-manager') ?></h5>
				<em><?php echo __( 'This will be sent to the person when they first submit their booking. Not relevant if bookings don\'t require approval.', 'events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking pending email subject', 'events-manager'), 'dbem_bookings_email_pending_subject', $email_subject_tip);
			em_options_textarea ( __( 'Booking pending email', 'events-manager'), 'dbem_bookings_email_pending_body','') ;
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Booking cancelled','events-manager') ?></h5>
				<em><?php echo __('This will be sent when a user cancels their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking cancelled email subject', 'events-manager'), 'dbem_bookings_email_cancelled_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking cancelled email', 'events-manager'), 'dbem_bookings_email_cancelled_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Rejected booking email','events-manager') ?></h5>
				<em><?php echo __( 'This will be sent automatically when a booking is rejected. Not relevant if bookings don\'t require approval.', 'events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking rejected email subject', 'events-manager'), 'dbem_bookings_email_rejected_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking rejected email', 'events-manager'), 'dbem_bookings_email_rejected_body', '' );
			?>
			</tbody>
	        <?php do_action('em_options_page_booking_email_templates_options_bottom'); ?>
			<?php echo $save_button; ?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php endif; ?>
			  		
	<?php if( get_option('dbem_rsvp_enabled') ): ?>
	<div  class="postbox "  id="em-opt-registration-emails">
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Registration Email Templates', 'events-manager'); ?> </span></h3>
	<div class="inside">
		<p class="em-boxheader">
			<?php echo sprintf(__('This is only applicable when %s is not active.','events-manager'), '<em>'.__('No-User Booking Mode','events-manager').'</em>'); ?>
			<?php _e('When a guest user makes a booking for the first time in Events Manager, a new user account is created for them and they are sent their credentials in a separate email, which can be modified below.','events-manager'); ?>
		</p>
		<table class='form-table'>
			<?php
			em_options_radio_binary ( __( 'Disable new registration email?', 'events-manager'), 'dbem_email_disable_registration', __( 'Check this option if you want to prevent the WordPress registration email from going out when a user anonymously books an event.', 'events-manager') );
			
			em_options_input_text ( __( 'Registration email subject', 'events-manager'), 'dbem_bookings_email_registration_subject' );
			em_options_textarea ( __( 'Registration email', 'events-manager'), 'dbem_bookings_email_registration_body', sprintf(__('%s is replaced by username, %s is replaced by the user password and %s is replaced by a link to create a password.','events-manager'),'<code>%username%</code>','<code>%password%</code>','<code>%passwordurl%</code>') );
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php endif; ?>
	
	<div  class="postbox " id="em-opt-event-submission-emails" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Event Submission Templates', 'events-manager'); ?> </span></h3>
	<div class="inside">
		<table class='form-table'>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Event Admin Emails', 'events-manager'); ?></h4></td></tr>
			<?php 
			em_options_input_text ( __( 'Administrator Email', 'events-manager'), 'dbem_event_submitted_email_admin', __('Event submission notifications will be sent to emails added here.','events-manager').' '.__('If left blank, no emails will be sent. Separate emails with commas for more than one email.','events-manager') );
			em_options_input_text ( __( 'Attachment root', 'events-manager'), 'dbem_event_attachment_root', __('The folder which all attachment options below are relative to. Must NOT end in <code>/</code>','events-manager') );
			?>
			<tbody class="em-subsection">
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Submitted','events-manager') ?></h5>
				<em><?php echo __('An email will be sent to your administrator emails when an event is submitted and pending approval.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event submitted subject', 'events-manager'), 'dbem_event_submitted_email_subject', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event submitted email', 'events-manager'), 'dbem_event_submitted_email_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Re-Submitted','events-manager') ?></h5>
				<em><?php echo __('When a user modifies a previously published event, it will be put back into pending review status and will not be published until you re-approve it.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event resubmitted subject', 'events-manager'), 'dbem_event_resubmitted_email_subject', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event resubmitted email', 'events-manager'), 'dbem_event_resubmitted_email_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Published','events-manager') ?></h5>
				<em><?php echo __('An email will be sent to an administrator of your choice when an event is published by users who are not administrators.','events-manager').$bookings_placeholder_tip ?>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event published subject', 'events-manager'), 'dbem_event_published_email_subject', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event published email', 'events-manager'), 'dbem_event_published_email_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Deleted','events-manager') ?></h5>
			    <?php echo __('An email will be sent to an administrator of your choice when an event is sent deleted by users who are not administrators.','events-manager').$bookings_placeholder_tip ?>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event deleted subject', 'events-manager'), 'dbem_event_deleted_email_subject', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event deleted email', 'events-manager'), 'dbem_event_deleted_email_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Approved Confirmation','events-manager') ?></h5>
				<em><?php echo __('An email will be sent to the administrator as confirmation when an event is approved. Users requiring event approval do not have the <code>publish_events</code> capability.','events-manager').$bookings_placeholder_tip ?>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event approved confirmation subject (Danish)', 'events-manager'), 'dbem_event_approved_confirmation_email_subject_da', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event approved confirmation email (Danish)', 'events-manager'), 'dbem_event_approved_confirmation_email_body_da', '' );
			em_options_input_text ( __( 'Event approved confirmation subject (English)', 'events-manager'), 'dbem_event_approved_confirmation_email_subject_en', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event approved confirmation email (English)', 'events-manager'), 'dbem_event_approved_confirmation_email_body_en', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Reapproved Confirmation ','events-manager') ?></h5>
			    <?php echo __('When a user modifies a previously published event, it will be put back into pending review status and will not be published until you re-approve it.','events-manager').$bookings_placeholder_tip ?>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event reapproved confirmation subject (Danish)', 'events-manager'), 'dbem_event_reapproved_confirmation_email_subject_da', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event reapproved confirmation email (Danish)', 'events-manager'), 'dbem_event_reapproved_confirmation_email_body_da', '' );
			em_options_input_text ( __( 'Event reapproved confirmation subject (English)', 'events-manager'), 'dbem_event_reapproved_confirmation_email_subject_en', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event reapproved confirmation email (English)', 'events-manager'), 'dbem_event_reapproved_confirmation_email_body_en', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Rejected confirmation','events-manager') ?></h5>
			    <?php echo __('An email will be sent to the administrator as confirmation when an event is sent to the trash by an administrator','events-manager').$bookings_placeholder_tip ?>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event deleted confirmation subject (Danish)', 'events-manager'), 'dbem_event_rejected_confirmation_email_subject_da', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event deleted confirmation email (Danish)', 'events-manager'), 'dbem_event_rejected_confirmation_email_body_da', '' );
			em_options_input_text ( __( 'Event deleted confirmation subject (English)', 'events-manager'), 'dbem_event_rejected_confirmation_email_subject_en', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event deleted confirmation email (English)', 'events-manager'), 'dbem_event_rejected_confirmation_email_body_en', '' );
			?>
			</tbody>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Event Submitter Emails', 'events-manager'); ?></h4></td></tr>
			<?php
			em_options_radio_binary ( __( 'Send mails til begivenhedsarrangører?', 'events-manager'), 'dbem_event_send_mail_to_owner', __( 'Vælg ja hvis der skal sendes mails til ejerne af begivenheder når deres begivenheder bliver godkendt eller afvist.', 'events-manager') );
			?>
			<tbody class="em-subsection">
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Approved','events-manager') ?></h5>
				<em><?php echo __('An email will be sent to the event owner when their event is approved. Users requiring event approval do not have the <code>publish_events</code> capability.','events-manager').$bookings_placeholder_tip ?>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event approved subject (Danish)', 'events-manager'), 'dbem_event_approved_email_subject_da', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event approved email (Danish)', 'events-manager'), 'dbem_event_approved_email_body_da', '' );
			em_options_input_text ( __( 'Event approved attachments (Danish)', 'events-manager'), 'dbem_event_approved_email_attachments_da', __('If left blank, no attachments are used. Must be relative to the root folder set above. Split multiple attachments by <code>,</code> without any spaces.','events-manager') );
			em_options_input_text ( __( 'Event approved subject (English)', 'events-manager'), 'dbem_event_approved_email_subject_en', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event approved email (English)', 'events-manager'), 'dbem_event_approved_email_body_en', '' );
			em_options_input_text ( __( 'Event approved attachments (English)', 'events-manager'), 'dbem_event_approved_email_attachments_en', __('If left blank, no attachments are used. Must be relative to the root folder set above. Split multiple attachments by <code>,</code> without any spaces.','events-manager') );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Reapproved','events-manager') ?></h5>
			    <?php echo __('When a user modifies a previously published event, it will be put back into pending review status and will not be published until you re-approve it.','events-manager').$bookings_placeholder_tip ?>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event reapproved subject (Danish)', 'events-manager'), 'dbem_event_reapproved_email_subject_da', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event reapproved email (Danish)', 'events-manager'), 'dbem_event_reapproved_email_body_da', '' );
			em_options_input_text ( __( 'Event reapproved attachments (Danish)', 'events-manager'), 'dbem_event_reapproved_email_attachments_da', __('If left blank, no attachments are used. Must be relative to the root folder set above. Split multiple attachments by <code>,</code> without any spaces.','events-manager') );
			em_options_input_text ( __( 'Event reapproved subject (English)', 'events-manager'), 'dbem_event_reapproved_email_subject_en', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event reapproved email (English)', 'events-manager'), 'dbem_event_reapproved_email_body_en', '' );
			em_options_input_text ( __( 'Event reapproved attachments (English)', 'events-manager'), 'dbem_event_reapproved_email_attachments_en', __('If left blank, no attachments are used. Must be relative to the root folder set above. Split multiple attachments by <code>,</code> without any spaces.','events-manager') );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Event Rejected','events-manager') ?></h5>
			    <?php echo __('An email will be sent to the user when their event is sent to the trash by an administrator','events-manager').$bookings_placeholder_tip ?>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Event deleted subject (Danish)', 'events-manager'), 'dbem_event_rejected_email_subject_da', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event deleted email (Danish)', 'events-manager'), 'dbem_event_rejected_email_body_da', '' );
			em_options_input_text ( __( 'Event deleted attachments (Danish)', 'events-manager'), 'dbem_event_rejected_email_attachments_da', __('If left blank, no attachments are used. Must be relative to the root folder set above. Split multiple attachments by <code>,</code> without any spaces.','events-manager') );
			em_options_input_text ( __( 'Event deleted subject (English)', 'events-manager'), 'dbem_event_rejected_email_subject_en', __('If left blank, this email will not be sent.','events-manager') );
			em_options_textarea ( __( 'Event deleted email (English)', 'events-manager'), 'dbem_event_rejected_email_body_en', '' );
			em_options_input_text ( __( 'Event deleted attachments (English)', 'events-manager'), 'dbem_event_rejected_email_attachments_en', __('If left blank, no attachments are used. Must be relative to the root folder set above. Split multiple attachments by <code>,</code> without any spaces.','events-manager') );
			?>
			</tbody>
			<?php echo $save_button; ?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	
	<?php do_action('em_options_page_footer_emails'); ?>
	
</div><!-- .em-group-emails --> 
