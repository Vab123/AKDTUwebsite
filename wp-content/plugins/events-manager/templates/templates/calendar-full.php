<?php 
/*
 * This file contains the HTML generated for full calendars. You can copy this file to yourthemefolder/plugins/events-manager/templates and modify it in an upgrade-safe manner.
 *
 * Note that leaving the class names for the previous/next links will keep the AJAX navigation working.
 *
 * There are two variables made available to you: 
 */
/* @var array $calendar - contains an array of information regarding the calendar and is used to generate the content */
/* @var array $args - the arguments passed to EM_Calendar::output() */

$cal_count = count($calendar['cells']); //to prevent an extra tr
$col_count = $tot_count = 1; //this counts collumns in the $calendar_array['cells'] array
$col_max = count($calendar['row_headers']); //each time this collumn number is reached, we create a new collumn, the number of cells should divide evenly by the number of row_headers
$EM_DateTime = new EM_DateTime($calendar['month_start'], 'UTC');
?>
<table class="em-calendar fullcalendar">
	<thead>
		<tr>
			<td><a class="em-calnav full-link em-calnav-prev" href="<?php echo esc_url($calendar['links']['previous_url']); ?>">&lt;&lt;</a></td>
			<td class="month_name" colspan="5"><?php echo esc_html($EM_DateTime->i18n(get_option('dbem_full_calendar_month_format'))); ?></td>
			<td><a class="em-calnav full-link em-calnav-next" href="<?php echo esc_url($calendar['links']['next_url']); ?>">&gt;&gt;</a></td>
		</tr>
	</thead>
	<tbody>
		<tr class="days-names">
			<td><?php echo implode('</td><td>',$calendar['row_headers']); ?></td>
		</tr>
		<tr>
			<?php
			foreach($calendar['cells'] as $date => $cell_data ){
				$cell_data['events'] = array_filter($cell_data['events'],function($EM_Event){return count(pll_get_post_translations($EM_Event->post_id)) == 1 || pll_get_post_language($EM_Event->post_id) == pll_current_language('slug');}); // Remove events with translations if they are not in the desired language.
				$class = ( !empty($cell_data['events']) && count($cell_data['events']) > 0 ) ? 'eventful':'eventless';
				if(!empty($cell_data['type'])){
					$class .= "-".$cell_data['type']; 
				}
				//In some cases (particularly when long events are set to show here) long events and all day events are not shown in the right order. In these cases, 
				//if you want to sort events cronologically on each day, including all day events at top and long events within the right times, add define('EM_CALENDAR_SORTTIME', true); to your wp-config.php file 
				if( defined('EM_CALENDAR_SORTTIME') && EM_CALENDAR_SORTTIME ) ksort($cell_data['events']); //indexes are timestamps
				?>
				<td class="<?php echo esc_attr($class); ?>">
					<?php if( !empty($cell_data['events']) && count($cell_data['events']) > 0 ): ?>
					<?php echo esc_html(date('j',$cell_data['date'])); ?>
					<ul>
						<?php echo EM_Events::output($cell_data['events'],array('format'=>get_option('dbem_full_calendar_event_format'))); ?>
						<?php if( $args['limit'] && count($cell_data['events']) > $args['limit'] && get_option('dbem_display_calendar_events_limit_msg') != '' ): ?>
						<li><?php echo get_option('dbem_display_calendar_events_limit_msg'); ?></li>
						<?php endif; ?>
					</ul>
					<?php else:?>
					<?php echo esc_html(date('j',$cell_data['date'])); ?>
					<?php endif; ?>
				</td>
				<?php
				//create a new row once we reach the end of a table collumn
				$col_count= ($col_count == $col_max ) ? 1 : $col_count+1;
				echo ($col_count == 1 && $tot_count < $cal_count) ? '</tr><tr>':'';
				$tot_count ++; 
			}
			?>
		</tr>
	</tbody>
</table>