<?php
	//TODO Simplify panel for events, use form flags to detect certain actions (e.g. submitted, etc)
	global $wpdb, $bp, $EM_Notices;
	/* @var array $args */
	/* @var array $EM_Events */
	/* @var int $events_count */
	/* @var int $future_count */
	/* @var int $past_count */
	/* @var int $draft_count */
	/* @var int $pending_count */
	/* @var bool $show_add_new */
	/* @var int $limit */
	/* @var int $page */
	$url = esc_url(add_query_arg(array('scope' => null, 'status' => null, 'em_search' => null, 'pno' => null, 'admin_mode' => null))); //template for cleaning the link for each view below
	?>
	<div class="em-events-admin-list">
		<?php
			echo $EM_Notices;
			//add new button will only appear if called from em_event_admin template tag, or if the $show_add_new var is set
			if(!empty($show_add_new) && current_user_can('edit_events')) echo '<a class="em-button button add-new-h2" href="'.em_add_get_params($_SERVER['REQUEST_URI'],array('action'=>'edit','scope'=>null,'status'=>null,'event_id'=>null, 'success'=>null)).'">'.pll__('Add New','events-manager').'</a>';
		?>
		<form id="posts-filter" action="" method="get">
			<div class="subsubsub">
				<?php $url = esc_url(add_query_arg(array('scope'=>null,'status'=>null,'em_search'=>null,'pno'=>null, 'admin_mode'=>null))); //template for cleaning the link for each view below ?>
				<a href='<?php echo add_query_arg('view', 'future', $url); ?>' <?php echo ( !isset($_GET['view']) ) ? 'class="current"':''; ?>><?php pll_e( 'Upcoming', 'events-manager'); ?> <span class="count">(<?php echo $future_count; ?>)</span></a> &nbsp;|&nbsp;
				<?php if( $pending_count > 0 ): ?>
				<a href='<?php echo add_query_arg('view', 'pending', $url); ?>' <?php echo ( isset($_GET['view']) && $_GET['view'] == 'pending' ) ? 'class="current"':''; ?>><?php pll_e( 'Pending', 'events-manager'); ?> <span class="count">(<?php echo $pending_count; ?>)</span></a> &nbsp;|&nbsp;
				<?php endif; ?>
				<?php if( $draft_count > 0 ): ?>
				<a href='<?php echo add_query_arg('view', 'draft', $url); ?>' <?php echo ( isset($_GET['view']) && $_GET['view'] == 'draft' ) ? 'class="current"':''; ?>><?php pll_e( 'Draft', 'events-manager'); ?> <span class="count">(<?php echo $draft_count; ?>)</span></a> &nbsp;|
				<?php endif; ?>
				<a href='<?php echo add_query_arg('view', 'past', $url); ?>' <?php echo ( isset($_GET['view']) && $_GET['view'] == 'past' ) ? 'class="current"':''; ?>><?php pll_e( 'Past Events', 'events-manager'); ?> <span class="count">(<?php echo $past_count; ?>)</span></a>
				
				<!-- <?php if( current_user_can('edit_others_events') ): ?>
					<div class="admin-events-filter">
						<a href='<?php echo add_query_arg('admin_mode', 1, $url); ?>' <?php echo ( !empty($_GET['admin_mode']) ) ? 'class="current"':''; ?>><?php pll_e( 'All Events', 'events-manager'); ?></a> &nbsp;|&nbsp;
						<a href='<?php echo add_query_arg('admin_mode', null, $url); ?>' <?php echo ( empty($_GET['admin_mode']) ) ? 'class="current"':''; ?>><?php pll_e( 'My Events', 'events-manager'); ?></a>
					</div>
				<?php endif; ?> -->
			</div>
			<?php if (current_user_can('edit_others_events')): ?>
			<!-- <p class="search-box">
				<label class="screen-reader-text" for="post-search-input"><?php pll_e('Search Events','events-manager'); ?>:</label>
				<input type="text" id="post-search-input" name="em_search" value="<?php echo (!empty($_REQUEST['em_search'])) ? esc_attr($_REQUEST['em_search']):''; ?>" />
				<?php if( !empty($_REQUEST['view']) ): ?>
				<input type="hidden" name="view" value="<?php echo esc_attr($_REQUEST['view']); ?>" />
				<?php endif; ?>
				<input type="submit" value="<?php pll_e('Search Events','events-manager'); ?>" class="button" />
			</p> -->
			<?php endif; ?>
			<div class="tablenav">
				<?php
				if ( $events_count >= $limit ) {
					$events_nav = em_admin_paginate( $events_count, $limit, $page);
					echo $events_nav;
				}
				?>
				<br class="clear" />
			</div>
				
			<?php
			if ( empty($EM_Events) ) {
				echo pll_e('No events', 'events-manager');
			} else {
			?>
					
			<table class="widefat events-table">
				<thead>
					<tr>
						<?php /* 
						<th class='manage-column column-cb check-column' scope='col'>
							<input class='select-all' type="checkbox" value='1' />
						</th>
						*/ ?>
						<th><?php pll_e( 'Name', 'events-manager'); ?></th>
						<th>&nbsp;</th>
						<?php if( get_option('dbem_locations_enabled') ): ?>
						<th><?php pll_e( 'Location', 'events-manager'); ?></th>
						<?php endif; ?>
						<th><?php pll_e( 'Date and time', 'events-manager'); ?></th>
						<th><?php pll_e( 'Rental price', 'events-manager'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$rowno = 0;
					$delete_before_event_time = time() + get_option('dbem_bookings_delete_time') * 86400;
					foreach ( $EM_Events as $EM_Event ) {
						/* @var EM_Event $EM_Event */
						$rowno++;
						$class = ($rowno % 2) ? 'alternate' : '';
						
						if( $EM_Event->start()->getTimestamp() < time() && $EM_Event->end()->getTimestamp() < time() ){
							$class .= " past";
						}
						//Check pending approval events
						if ( !$EM_Event->get_status() ){
							$class .= " pending";
						}					
						?>
						<tr class="event <?php echo trim($class); ?>" id="event_<?php echo $EM_Event->event_id ?>">
							<?php /*
							<td>
								<input type='checkbox' class='row-selector' value='<?php echo $EM_Event->event_id; ?>' name='events[]' />
							</td>
							*/ ?>
							<td>
								<strong>
									<?php echo format_common_house_rental_name($EM_Event); ?>
								</strong>
								<?php 
								if( get_option('dbem_rsvp_enabled') == 1 && $EM_Event->event_rsvp == 1 ){
									?>
									<br/>
									<a href="<?php echo $EM_Event->get_bookings_url(); ?>"><?php pll_e("Bookings",'events-manager'); ?></a> &ndash;
									<?php pll_e("Booked",'events-manager'); ?>: <?php echo $EM_Event->get_bookings()->get_booked_spaces()."/".$EM_Event->get_spaces(); ?>
									<?php if( get_option('dbem_bookings_approval') == 1 ): ?>
										| <?php pll_e("Pending",'events-manager') ?>: <?php echo $EM_Event->get_bookings()->get_pending_spaces(); ?>
									<?php endif;
								}
								?>
								<div class="row-actions">
									<?php if( current_user_can('delete_events') && $EM_Event->start()->getTimestamp() > $delete_before_event_time && $EM_Event->end()->getTimestamp() > $delete_before_event_time ) : ?>
									<span class="edit"><a href="<?php echo esc_url($EM_Event->get_edit_url()); ?>" class="em-event-edit"><?php pll_e('Edit','events-manager'); ?></a></span>
									<span class="trash"><a href="<?php echo esc_url(add_query_arg(array('action'=>'event_delete', 'event_id'=>$EM_Event->event_id, '_wpnonce'=> wp_create_nonce('event_delete_'.$EM_Event->event_id)))); ?>" class="em-event-delete"><?php pll_e('Delete','events-manager'); ?></a></span>
									<?php endif; ?>
								</div>
							</td>
							<td>
								<!-- <a href="<?php echo $EM_Event->duplicate_url(); ?>" title="<?php pll_e( 'Duplicate this event', 'events-manager'); ?>">
									<strong>+</strong>
								</a> -->
							</td>
							<?php if( get_option('dbem_locations_enabled') ): ?>
								<td>
									<?php
									if( $EM_Event->has_location() ){
										echo "<b>" . esc_html($EM_Event->get_location()->location_name) . "</b><br/>" . esc_html($EM_Event->get_location()->location_address) . " - " . esc_html($EM_Event->get_location()->location_town);
									}elseif( $EM_Event->has_event_location() ) {
										echo $EM_Event->get_event_location()->get_admin_column();
									}else{
										echo pll_e('None','events-manager');
									}
									?>
								</td>
							<?php endif; ?>
							<td>
								<?php echo $EM_Event->output_dates(); ?>
								<br />
								<?php echo $EM_Event->output_times(); ?>
							</td>
							<td>
							<?php 
								$startdate = new DateTime($EM_Event->event_start_date . " " . $EM_Event->event_start_time);
								$enddate = new DateTime($EM_Event->event_end_date . " " . $EM_Event->event_end_time);
								$owner_id = $EM_Event->owner;
								echo pll__('Common house rental price, pre', 'events-manager') . " " . calc_rental_cost($startdate, $enddate, $owner_id) . " " . pll__('Common house rental price, post', 'events-manager');
							?>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>  
			<?php
			} // end of table
			?>
			<div class='tablenav'>
				<div class="alignleft actions">
				<br class='clear' />
				</div>
				<?php if ( $events_count >= $limit ) : ?>
				<div class="tablenav-pages">
					<?php
					echo $events_nav;
					?>
				</div>
				<?php endif; ?>
				<br class='clear' />
			</div>
		</form>
	</div>