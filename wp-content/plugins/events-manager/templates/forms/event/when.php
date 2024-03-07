<?php
global $EM_Event, $post;
$hours_format = em_get_hour_format();
$required = apply_filters('em_required_html', '<i>*</i>');
?>
<div class="event-form-when" id="em-form-when">
		<?php
		if (is_admin() && current_user_can('edit_others_events') || count(array_filter(wp_get_current_user()->roles, function ($role) {
			return $role == 'vicevaert';
		})) > 0) {
			echo '<p class="em-date-range">';
			echo pll__('Startdate', 'events-manager') . '<input class="em-date-start em-date-input-loc" type="text" /><input class="em-date-input" type="hidden" name="event_start_date" value="' . $EM_Event->start()->getDate() . '" />' . pll__('Enddate', 'events-manager') . '<input class="em-date-end em-date-input-loc" type="text" /><input class="em-date-input" type="hidden" name="event_end_date" value="' . $EM_Event->end()->getDate() . '" />';
			echo '</p>';

			echo '<p class="em-time-range">';
			echo pll__('Event starts at', 'events-manager') . ' <input class="em-time-input em-time-start" type="text" size="8" maxlength="8" name="event_start_time" value="' . $EM_Event->start()->format($hours_format) . '" /> ' . pll__('to', 'events-manager') . ' <input class="em-time-input em-time-end" type="text" size="8" maxlength="8" name="event_end_time" value="' . $EM_Event->end()->format($hours_format) . '" />';
			pll_e('All day', 'events-manager');
			echo '<input type="checkbox" class="em-time-all-day" name="event_all_day" id="em-time-all-day" value="1" />';
			echo '</p>';
		} else {
			echo '<p class="em-date-range">';
			echo pll__('Startdate', 'events-manager') . ' <input class="em-date-start em-date-input-loc" type="text" /><input class="em-date-input" type="hidden" name="event_start_date" value="' . $EM_Event->start()->getDate() . '" /> ' . pll__('Starttime', 'events-manager') . ' ' . pll__("12:00 (noon)", 'events-manager');
			echo '</p>';

			echo '<p class="em-date-range">';
			echo pll__('Enddate', 'events-manager') . ' <input class="em-date-end em-date-input-loc" type="text" /><input class="em-date-input" type="hidden" name="event_end_date" value="' . $EM_Event->end()->getDate() . '" /> ' . pll__('Endtime', 'events-manager') . ' ' . pll__("12:00 (noon)", 'events-manager');
			echo '</p>';

			echo '<p class="em-time-range">';
			echo '<input class="em-time-input em-time-start" type="hidden" size="8" maxlength="8" name="event_start_time" value="12:00" />';
			echo '<input class="em-time-input em-time-end" type="hidden" size="8" maxlength="8" name="event_end_time" value="12:00" />';
			echo '<input type="checkbox" class="em-time-all-day" name="event_all_day" style="display:none;" id="em-time-all-day" value="1" />';
			echo '</p>';
		}
		?>
	<?php if (get_option('dbem_timezone_enabled') && $EM_Event->can_manage('edit_recurring_events', 'edit_others_recurring_events')) : ?>
		<p class="em-timezone">
			<label for="event-timezone"><?php pll_e('Timezone', 'events-manager'); ?></label>
			<select id="event-timezone" name="event_timezone" aria-describedby="timezone-description">
				<?php echo wp_timezone_choice($EM_Event->get_timezone()->getName(), get_user_locale()); ?>
			</select>
		</p>
	<?php endif; ?>
	<span id='event-date-explanation'>
		<?php pll_e('This event spans every day between the beginning and end date, with start/end times applying to each day.', 'events-manager'); ?>
	</span>
</div>
<?php if (false && get_option('dbem_recurrence_enabled') && $EM_Event->is_recurrence()) : //in future, we could enable this and then offer a detach option alongside, which resets the recurrence id and removes the attachment to the recurrence set 
?>
	<input type="hidden" name="recurrence_id" value="<?php echo $EM_Event->recurrence_id; ?>" />
<?php endif;
