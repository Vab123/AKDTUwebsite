<?php

/**
 * Decide what content to show in the bookings section. 
 */

 add_action('wp_ajax_update_arrivalstatus','update_arrivalstatus');

function update_arrivalstatus() {
	global $wpdb;
	if (isset($_REQUEST['userid']) && isset($_REQUEST['status'])){
		$res = $wpdb->get_results('SELECT ticket_id,showed_up FROM wp_em_tilmeldinger WHERE event_id = ' . $_REQUEST['event_id'] . ' AND ticket_id = ' . $_REQUEST['event_ticket_id'] );
		if ($_REQUEST['status'] == 'true') {
			## User showed up
			if (count($res) == 0) {
				$wpdb->insert('wp_em_tilmeldinger', array('showed_up' => json_encode(array($_REQUEST['userid'] => true)), 'event_id' => $_REQUEST['event_id'], 'ticket_id' => $_REQUEST['event_ticket_id']));
			} else {
				$id = $_REQUEST['userid'];
				$showed_up = json_decode($res[0]->showed_up);
				$showed_up->$id = true;
				$wpdb->update('wp_em_tilmeldinger', array('showed_up' => json_encode($showed_up)), array('event_id' => $_REQUEST['event_id'], 'ticket_id' => $_REQUEST['event_ticket_id']));
			}
		} else {
			## User removed again
			if (count($res) == 0) {
				## Do nothing, since there is nothing to remove
			} else {
				$id = $_REQUEST['userid'];
				$showed_up = json_decode($res[0]->showed_up);
				$showed_up->$id = false;
				$wpdb->update('wp_em_tilmeldinger', array('showed_up' => json_encode($showed_up)), array('event_id' => $_REQUEST['event_id'], 'ticket_id' => $_REQUEST['event_ticket_id']));
			}
		}
	}
	wp_die('','',['response'=>200]);
}

function em_bookings_print_page(){
	//First any actions take priority
	do_action('em_bookings_admin_page');
	if( !empty($_REQUEST['_wpnonce']) ){ $_REQUEST['_wpnonce'] = $_GET['_wpnonce'] = $_POST['_wpnonce'] = esc_attr($_REQUEST['_wpnonce']); } //XSS fix just in case here too
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,7) != 'booking' ){ //actions not starting with booking_
		do_action('em_bookings_'.$_REQUEST['action']);
	}elseif( !empty($_REQUEST['event_id']) ){
		em_bookings_print_event();
	}else{
		em_bookings_print_dashboard();
	}
}

/**
 * Generates the bookings dashboard, showing information on all events 
 */
function em_bookings_print_dashboard(){
	global $EM_Notices;
	?>
	<div class='wrap em-bookings-dashboard'>
		<?php if( is_admin() ): ?>
  		<h1>Tilmeldingslister til havedage</h1>
  		<?php else: echo $EM_Notices; ?>
  		<?php endif; ?>
  		<div class="em-bookings-events">
			<h2><?php esc_html_e('Events With Bookings Enabled','events-manager'); ?></h2>		
			<?php em_bookings_events_print_table(); ?>
			<?php do_action('em_bookings_dashboard'); ?>
		</div>
	</div>
	<?php		
}
?>