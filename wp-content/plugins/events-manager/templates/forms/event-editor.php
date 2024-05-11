<?php
/* WARNING! This file may change in the near future as we intend to add features to the event editor. If at all possible, try making customizations using CSS, jQuery, or using our hooks and filters. - 2012-02-14 */
/* 
 * To ensure compatability, it is recommended you maintain class, id and form name attributes, unless you now what you're doing. 
 * You also must keep the _wpnonce hidden field in this form too.
 */
global $EM_Event, $EM_Notices, $bp;

//check that user can access this page
if (is_object($EM_Event) && !$EM_Event->can_manage('edit_events', 'edit_others_events')) {
?>
	<div class="wrap">
		<h2><?php pll_e('Unauthorized Access', 'events-manager'); ?></h2>
		<p><?php echo sprintf(pll__('You do not have the rights to manage this %s.', 'events-manager'), pll__('Event', 'events-manager')); ?></p>
	</div>
<?php
	return false;
} elseif (!is_object($EM_Event)) {
	$EM_Event = new EM_Event();
}
$required = apply_filters('em_required_html', '<i>*</i>');

echo $EM_Notices;
//Success notice
if (!empty($_REQUEST['success'])) {
	if (!get_option('dbem_events_form_reshow')) return false;
}
?>
<form enctype='multipart/form-data' id="event-form" class="em-event-admin-editor <?php if ($EM_Event->is_recurring()) echo 'em-event-admin-recurring' ?>" method="post" action="<?php echo esc_url(add_query_arg(array('success' => null, 'action' => null))); ?>">
	<div class="wrap">
		<?php if (!$EM_Event->can_manage('edit_recurring_events', 'edit_others_recurring_events')) {
			if (count(array_filter(wp_get_current_user()->roles, function ($role) {
				return $role == 'vicevaert';
			})) > 0) {
				$notice = pll__('Vicevært common room rental notice', 'events-manager');
			} else {
				$notice = pll__('Common room rental notice', 'events-manager');
			}
			if (strlen($notice) > 0) {
				echo '<p>' . $notice . '</p>';
			}
		} ?>
		<?php do_action('em_front_event_form_header', $EM_Event); ?>
		<?php if (get_option('dbem_events_anonymous_submissions') && !is_user_logged_in()) : ?>
			<h3 class="event-form-submitter"><?php pll_e('Your Details', 'events-manager'); ?></h3>
			<div class="inside event-form-submitter">
				<p>
					<label><?php pll_e('Name', 'events-manager'); ?></label>
					<input type="text" name="event_owner_name" id="event-owner-name" value="<?php echo esc_attr($EM_Event->event_owner_name); ?>" />
				</p>
				<p>
					<label><?php pll_e('Email', 'events-manager'); ?></label>
					<input type="text" name="event_owner_email" id="event-owner-email" value="<?php echo esc_attr($EM_Event->event_owner_email); ?>" />
				</p>
				<?php do_action('em_front_event_form_guest'); ?>
				<?php do_action('em_font_event_form_guest'); //deprecated 
				?>
			</div>
		<?php endif; ?>

		<?php if ($EM_Event->can_manage('edit_recurring_events', 'edit_others_recurring_events')) : ?>
			<h3 class="event-form-name"><?php pll_e('Event Name', 'events-manager'); ?></h3>
			<div class="inside event-form-name">
				<input type="text" name="event_name" id="event-name" value="<?php echo esc_attr($EM_Event->event_name, ENT_QUOTES); ?>" /><?php echo $required; ?>
				<br />
				<?php echo pll_e('The event name. Example: Birthday party', 'events-manager'); ?>
				<br />
				<?php em_locate_template('forms/event/group.php', true); ?>
			</div>
		<?php else :
			$new_event_name = common_house_rental_name(apartment_number_from_username(wp_get_current_user()->user_login), is_vicevært_from_id(wp_get_current_user()->ID));
			echo '<input type="hidden" name="event_name" id="event-name" value="' . $new_event_name . '" readonly hidden /><br />';
			em_locate_template('forms/event/group.php', true);
			endif; ?>

		<h3 class="event-form-when"><?php pll_e('When', 'events-manager'); ?></h3>
		<div class="inside event-form-when">
			<?php
			if (empty($EM_Event->event_id) && $EM_Event->can_manage('edit_recurring_events', 'edit_others_recurring_events') && get_option('dbem_recurrence_enabled')) {
				em_locate_template('forms/event/when-with-recurring.php', true);
			} elseif ($EM_Event->is_recurring()) {
				em_locate_template('forms/event/recurring-when.php', true);
			} else {
				em_locate_template('forms/event/when.php', true);
			}
			?>
		</div>

		<?php if (!$EM_Event->can_manage('edit_recurring_events', 'edit_others_recurring_events') && count(array_filter(wp_get_current_user()->roles, function ($role) { return $role == 'vicevaert'; })) == 0) : ?>
			<div class="inside event-form-price">
				<h3 class="event-form-price"><?php pll_e('Price', 'events-manager'); ?></h3>
				<?php if (had_to_pay_rental_cost_from_id(wp_get_current_user()->ID, new DateTime('now', new DateTimeZone('Europe/Copenhagen')))) : ?>
					<span><?php pll_e('Price calculation steps', 'events-manager'); ?></span>
					<p><?php pll_e('The expected price for the rental is:', 'events-manager'); ?> <span id="event-form-price">
							<?php
							if ($EM_Event->event_start_date == "" || $EM_Event->event_end_date == "") {
								pll_e('Default common house rental price', 'events-manager');
							} else {
								require_once WP_PLUGIN_DIR . '/AKDTU/functions/fælleshus.php';
								echo pll__('Common house rental price, pre', 'events-manager') . " " . calc_rental_cost(new DateTime(trim($EM_Event->event_start_date . ' ' . $EM_Event->event_start_time)), new DateTime(trim($EM_Event->event_end_date . ' ' . $EM_Event->event_end_time)), wp_get_current_user()->ID) . " " . pll__('Common house rental price, post', 'events-manager');
							}
							?></span> <?php pll_e('which will be paid with the rent at the end of', 'events-manager'); ?> <span id="event-form-price-month">
							<?php if ($EM_Event->event_start_date == "" || $EM_Event->event_end_date == "") {
								pll_e('Default common house rental month', 'events-manager');
							} else {
								$month = new IntlDateFormatter(pll_current_language(), IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'Europe/Copenhagen', null, 'MMMM');
								echo $month->format(new DateTime(trim($EM_Event->event_end_date . ' ' . $EM_Event->event_end_time), new DateTimeZone('Europe/Copenhagen')));
							} ?></span>.</p>
				<?php else : ?>
					<p><?php pll_e('Rental is free for members of the board.', 'events-manager'); ?></p>
				<?php endif; ?>
			</div>
			<script>
				var AKDTU_price_mark_pre = "<?php pll_e('Common house rental price, pre', 'events-manager'); ?>";
				var AKDTU_price_mark_post = "<?php pll_e('Common house rental price, post', 'events-manager'); ?>";
				var AKDTU_price_mark_invalid = "<?php pll_e('Common house rental price, invalid', 'events-manager'); ?>";
				var AKDTU_months = [<?php pll_e('Month_array', 'events-manager'); ?>];
				var AKDTU_price_mark_invalid_month = "<?php pll_e('Common house rental price month, invalid', 'events-manager'); ?>";

				<?php echo js_calc_rental_cost_script(); ?>
			</script>
		<?php endif; ?>

		<?php if ($EM_Event->can_manage('edit_recurring_events', 'edit_others_recurring_events')) : ?>
			<?php if (get_option('dbem_locations_enabled')) : ?>
				<h3 class="event-form-where"><?php pll_e('Where', 'events-manager'); ?></h3>
				<div class="inside event-form-where">
					<?php em_locate_template('forms/event/location.php', true); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ($EM_Event->can_manage('edit_recurring_events', 'edit_others_recurring_events')) : ?>
			<h3 class="event-form-details"><?php pll_e('Details', 'events-manager'); ?></h3>
			<div class="inside event-form-details">
				<div class="event-editor">
					<?php if (get_option('dbem_events_form_editor') && function_exists('wp_editor')) : ?>
						<?php wp_editor($EM_Event->post_content, 'em-editor-content', array('textarea_name' => 'content')); ?>
					<?php else : ?>
						<textarea name="content" rows="10" style="width:100%"><?php echo $EM_Event->post_content ?></textarea>
						<br />
						<?php pll_e('Details about the event.', 'events-manager') ?> <?php pll_e('HTML allowed.', 'events-manager') ?>
					<?php endif; ?>
				</div>
				<div class="event-extra-details">
					<?php if (get_option('dbem_attributes_enabled')) {
						em_locate_template('forms/event/attributes-public.php', true);
					}  ?>
					<?php if (get_option('dbem_categories_enabled')) {
						em_locate_template('forms/event/categories-public.php', true);
					}  ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($EM_Event->can_manage('upload_event_images', 'upload_event_images')) : ?>
			<h3><?php pll_e('Event Image', 'events-manager'); ?></h3>
			<div class="inside event-form-image">
				<?php em_locate_template('forms/event/featured-image-public.php', true); ?>
			</div>
		<?php endif; ?>

		<?php if (is_admin() && get_option('dbem_rsvp_enabled') && $EM_Event->can_manage('manage_bookings', 'manage_others_bookings')) : ?>
			<!-- START Bookings -->
			<h3><?php pll_e('Bookings/Registration', 'events-manager'); ?></h3>
			<div class="inside event-form-bookings">
				<?php em_locate_template('forms/event/bookings.php', true); ?>
			</div>
			<!-- END Bookings -->
		<?php endif; ?>

		<?php do_action('em_front_event_form_footer', $EM_Event); ?>
	</div>
	<p class="submit">
		<?php if (empty($EM_Event->event_id)) : ?>
			<input type='submit' class='button-primary' value='<?php pll_e('Submit event', 'events-manager'); ?>' />
		<?php else : ?>
			<input type='submit' class='button-primary' value='<?php pll_e('Update event', 'events-manager'); ?>' />
		<?php endif; ?>
	</p>
	<input type="hidden" name="event_id" value="<?php echo $EM_Event->event_id; ?>" />
	<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('wpnonce_event_save'); ?>" />
	<input type="hidden" name="action" value="event_save" />
	<?php if (!empty($_REQUEST['redirect_to'])) : ?>
		<input type="hidden" name="redirect_to" value="<?php echo esc_attr($_REQUEST['redirect_to']); ?>" />
	<?php endif; ?>
</form>