<?php

function em_install() {
	global $wp_rewrite;
	switch_to_locale(EM_ML::$wplang); //switch to blog language (if applicable)
   	$wp_rewrite->flush_rules();
	$old_version = get_option('dbem_version');
	//Won't upgrade <4.300 anymore
   	if( $old_version != '' && $old_version < 4.1 ){
		function em_update_required_notification(){
			global $EM_Booking;
			?><div class="error"><p><strong>Events Manager upgrade not complete, please upgrade to the version 4.300 or higher first from <a href="http://wordpress.org/extend/plugins/events-manager/download/">here</a> before upgrading to this version.</strong></p></div><?php
		}
		add_action ( 'admin_notices', 'em_update_required_notification' );
		return;
   	}
	if( EM_VERSION > $old_version || $old_version == '' || (is_multisite() && !EM_MS_GLOBAL && get_option('em_ms_global_install')) ){
		if( get_option('dbem_upgrade_throttle') <= time() || !get_option('dbem_upgrade_throttle') ){
		 	// Creates the events table if necessary
		 	if( !EM_MS_GLOBAL || (EM_MS_GLOBAL && is_main_site()) ){
				em_create_events_table();
				em_create_events_meta_table();
				em_create_locations_table();
			  	em_create_bookings_table();
				em_create_tickets_table();
				em_create_tickets_bookings_table();
		 		delete_option('em_ms_global_install'); //in case for some reason the user changed global settings
			    add_action('em_ml_init', 'EM_ML::toggle_languages_index');
		 	}else{
		 		update_option('em_ms_global_install',1); //in case for some reason the user changes global settings in the future
		 	}	
			//New install, or Migrate?
			if( $old_version < 5 && !empty($old_version) ){
				update_option('dbem_upgrade_throttle', time()+300);
				set_time_limit(300);
				em_migrate_v4();
				update_site_option('dbem_ms_update_nag',1);
			}elseif( empty($old_version) ){
				em_create_events_page();
				update_option('dbem_hello_to_user',1);
			}			
			//set caps and options
			em_set_capabilities();
			em_add_options();
			em_upgrade_current_installation();
			do_action('events_manager_updated', $old_version );
			//Update Version
		  	update_option('dbem_version', EM_VERSION);
			delete_option('dbem_upgrade_throttle');
			delete_option('dbem_upgrade_throttle_time');
			//last but not least, flush the toilet
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			update_option('dbem_flush_needed',1);
		}else{
			function em_upgrading_in_progress_notification(){
				global $EM_Booking;
				?><div class="error"><p>Events Manager upgrade still in progress. Please be patient, this message should disappear once the upgrade is complete.</p></div><?php
			}
			add_action ( 'admin_notices', 'em_upgrading_in_progress_notification' );
			add_action ( 'network_admin_notices', 'em_upgrading_in_progress_notification' );
			return;
		}
	}
	restore_previous_locale(); //now that we're done, switch back to current language (if applicable)
}

/**
 * Magic function that takes a table name and cleans all non-unique keys not present in the $clean_keys array. if no array is supplied, all but the primary key is removed.
 * @param string $table_name
 * @param array $clean_keys
 */
function em_sort_out_table_nu_keys($table_name, $clean_keys = array()){
	global $wpdb;
	//sort out the keys
	$new_keys = $clean_keys;
	$table_key_changes = array();
	$table_keys = $wpdb->get_results("SHOW KEYS FROM $table_name WHERE Key_name != 'PRIMARY'", ARRAY_A);
	foreach($table_keys as $table_key_row){
		if( !in_array($table_key_row['Key_name'], $clean_keys) ){
			$table_key_changes[] = "ALTER TABLE $table_name DROP INDEX ".$table_key_row['Key_name'];
		}elseif( in_array($table_key_row['Key_name'], $clean_keys) ){
			foreach($clean_keys as $key => $clean_key){
				if($table_key_row['Key_name'] == $clean_key){
					unset($new_keys[$key]);
				}
			}
		}
	}
	//delete duplicates
	foreach($table_key_changes as $sql){
		$wpdb->query($sql);
	}
	//add new keys
	foreach($new_keys as $key){
		if( preg_match('/\(/', $key) ){
			$wpdb->query("ALTER TABLE $table_name ADD INDEX $key");
		}else{
			$wpdb->query("ALTER TABLE $table_name ADD INDEX ($key)");
		}
	}
}

/**
 * Since WP 4.2 tables are created with utf8mb4 collation. This creates problems when storing content in previous utf8 tables such as when using emojis. 
 * This function checks whether the table in WP was changed 
 * @return boolean
 */
function em_check_utf8mb4_tables(){
		global $wpdb, $em_check_utf8mb4_tables;
		
		if( $em_check_utf8mb4_tables || $em_check_utf8mb4_tables === false ) return $em_check_utf8mb4_tables;
		
		$column = $wpdb->get_row( "SHOW FULL COLUMNS FROM {$wpdb->posts} WHERE Field='post_content';" );
		if ( ! $column ) {
			return false;
		}
		
		//if this doesn't become true further down, that means we couldn't find a correctly converted utf8mb4 posts table 
		$em_check_utf8mb4_tables = false;
		
		if ( $column->Collation ) {
			list( $charset ) = explode( '_', $column->Collation );
			$em_check_utf8mb4_tables = ( 'utf8mb4' === strtolower( $charset ) );
		}
		return $em_check_utf8mb4_tables;
		
}

function em_create_events_table() {
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$table_name = $wpdb->prefix.'em_events';
	$sql = "CREATE TABLE ".$table_name." (
		event_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		post_id bigint(20) unsigned NOT NULL,
		event_parent bigint(20) unsigned NULL DEFAULT NULL,
		event_slug VARCHAR( 200 ) NULL DEFAULT NULL,
		event_owner bigint(20) unsigned DEFAULT NULL,
		event_status tinyint(1) NULL DEFAULT NULL,
		event_name text NULL DEFAULT NULL,
		event_start_date date NULL DEFAULT NULL,
		event_end_date date NULL DEFAULT NULL,
		event_start_time time NULL DEFAULT NULL,
		event_end_time time NULL DEFAULT NULL,
 		event_all_day tinyint(1) unsigned NULL DEFAULT NULL,
		event_start datetime NULL DEFAULT NULL,
		event_end datetime NULL DEFAULT NULL,
		event_timezone tinytext NULL DEFAULT NULL,
		post_content longtext NULL DEFAULT NULL,
		event_rsvp tinyint(1) unsigned NOT NULL DEFAULT 0,
		event_rsvp_date date NULL DEFAULT NULL,
		event_rsvp_time time NULL DEFAULT NULL,
		event_rsvp_spaces int(5) NULL DEFAULT NULL,
		event_spaces int(5) NULL DEFAULT 0,
		event_private tinyint(1) unsigned NOT NULL DEFAULT 0,
		location_id bigint(20) unsigned NULL DEFAULT NULL,
		event_location_type VARCHAR(15) NULL DEFAULT NULL,
		recurrence_id bigint(20) unsigned NULL DEFAULT NULL,
  		event_date_created datetime NULL DEFAULT NULL,
  		event_date_modified datetime NULL DEFAULT NULL,
		recurrence tinyint(1) unsigned NOT NULL DEFAULT 0,
		recurrence_interval int(4) NULL DEFAULT NULL,
		recurrence_freq tinytext NULL DEFAULT NULL,
		recurrence_byday tinytext NULL DEFAULT NULL,
		recurrence_byweekno int(4) NULL DEFAULT NULL,
		recurrence_days int(4) NULL DEFAULT NULL,
		recurrence_rsvp_days int(3) NULL DEFAULT NULL,
		blog_id bigint(20) unsigned NULL DEFAULT NULL,
		group_id bigint(20) unsigned NULL DEFAULT NULL,
		event_language varchar(14) NULL DEFAULT NULL,
		event_translation tinyint(1) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY  (event_id)
		) DEFAULT CHARSET=utf8 ;";

	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ){
		dbDelta($sql);
	}elseif( get_option('dbem_version') != '' ){
		if( get_option('dbem_version') < 5.984 ){
			// change the recurrence flag to a required field defaulting to 0, to avoid missing recurrences in EM_Events::get() due to wayward null values
			$wpdb->query("UPDATE $table_name SET recurrence = 0 WHERE recurrence IS NULL");
			$wpdb->query("ALTER TABLE $table_name CHANGE `recurrence` `recurrence` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
			$wpdb->query("ALTER TABLE $table_name CHANGE `event_status` `event_status` TINYINT(1) NULL DEFAULT NULL;");
		}
		dbDelta($sql);
	}
	em_sort_out_table_nu_keys($table_name, array('event_status','post_id','blog_id','group_id','location_id','event_start', 'event_end', 'event_start_date', 'event_end_date'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_create_events_meta_table(){
	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_meta';

	// Creating the events table
	$sql = "CREATE TABLE ".$table_name." (
		meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		object_id bigint(20) unsigned NOT NULL,
		meta_key varchar(255) DEFAULT NULL,
		meta_value longtext,
		meta_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (meta_id)
		) DEFAULT CHARSET=utf8 ";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name, array('object_id','meta_key'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_create_locations_table() {

	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_locations';

	// Creating the events table
	$sql = "CREATE TABLE ".$table_name." (
		location_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		post_id bigint(20) unsigned NOT NULL,
		blog_id bigint(20) unsigned NULL DEFAULT NULL,
		location_parent bigint(20) unsigned NULL DEFAULT NULL,
		location_slug VARCHAR( 200 ) NULL DEFAULT NULL,
		location_name text NULL DEFAULT NULL,
		location_owner bigint(20) unsigned NOT NULL DEFAULT 0,
		location_address VARCHAR( 200 ) NULL DEFAULT NULL,
		location_town VARCHAR( 200 ) NULL DEFAULT NULL,
		location_state VARCHAR( 200 ) NULL DEFAULT NULL,
		location_postcode VARCHAR( 10 ) NULL DEFAULT NULL,
		location_region VARCHAR( 200 ) NULL DEFAULT NULL,
		location_country CHAR( 2 ) NULL DEFAULT NULL,
		location_latitude DECIMAL( 9, 6 ) NULL DEFAULT NULL,
		location_longitude DECIMAL( 9, 6 ) NULL DEFAULT NULL,
		post_content longtext NULL DEFAULT NULL,
		location_status int(1) NULL DEFAULT NULL,
		location_private tinyint(1) unsigned NOT NULL DEFAULT 0,
		location_language varchar(14) NULL DEFAULT NULL,
		location_translation tinyint(1) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY  (location_id)
		) DEFAULT CHARSET=utf8 ;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
		dbDelta($sql);
		/*
		DEPRICATED - kept here as an example for how migrations from the wp_em_events table is fairly easy
		//Add default values
		$wpdb->query("INSERT INTO ".$table_name." (location_name, location_address, location_town, location_state, location_country, location_latitude, location_longitude, location_slug, location_owner, location_status, post_id) VALUES ('Arts Millenium Building', 'Newcastle Road','Galway','Galway','IE', 53.275, -9.06532, 'arts-millenium-building','".get_current_user_id()."', 1,0)");
		$wpdb->query("INSERT INTO ".$table_name." (location_name, location_address, location_town, location_state, location_country, location_latitude, location_longitude, location_slug, location_owner, location_status, post_id) VALUES ('The Crane Bar', '2, Sea Road','Galway','Galway','IE', 53.2692, -9.06151, 'the-crane-bar','".get_current_user_id()."', 1, 0)");
		$wpdb->query("INSERT INTO ".$table_name." (location_name, location_address, location_town, location_state, location_country, location_latitude, location_longitude, location_slug, location_owner, location_status, post_id) VALUES ('Taaffes Bar', '19 Shop Street','Galway','Galway','IE', 53.2725, -9.05321, 'taffes-bar','".get_current_user_id()."', 1, 0)");
		em_migrate_locations($wpdb->get_results('SELECT * FROM '.$table_name, ARRAY_A));
		*/
	}else{
		if( get_option('dbem_version') != '' && get_option('dbem_version') < 4.938 ){
			$wpdb->query("ALTER TABLE $table_name CHANGE location_description post_content longtext NULL DEFAULT NULL");
		}
		dbDelta($sql);
		if( get_option('dbem_version') != '' && get_option('dbem_version') < 4.93 ){
			//if updating from version 4 (4.93 is beta v5) then set all statuses to 1 since it's new
			$wpdb->query("UPDATE ".$table_name." SET location_status=1");
		}
	}
	if( em_check_utf8mb4_tables() ){
		maybe_convert_table_to_utf8mb4( $table_name );
		em_sort_out_table_nu_keys($table_name, array('location_state (location_state(191))','location_region (location_region(191))','location_country','post_id','blog_id'));
	}else{
		em_sort_out_table_nu_keys($table_name, array('location_state','location_region','location_country','post_id','blog_id'));
	}
}

function em_create_bookings_table() {

	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_bookings';

	$sql = "CREATE TABLE ".$table_name." (
		booking_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		event_id bigint(20) unsigned NULL,
		person_id bigint(20) unsigned NOT NULL,
		booking_spaces int(5) NOT NULL,
		booking_comment text DEFAULT NULL,
		booking_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		booking_status bool NOT NULL DEFAULT 1,
 		booking_price decimal(14,4) unsigned NOT NULL DEFAULT 0,
 		booking_tax_rate decimal(7,4) NULL DEFAULT NULL,
 		booking_taxes decimal(14,4) NULL DEFAULT NULL,
		booking_meta LONGTEXT NULL,
		PRIMARY KEY  (booking_id)
		) DEFAULT CHARSET=utf8 ;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name, array('event_id','person_id','booking_status'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}


//Add the categories table
function em_create_tickets_table() {

	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_tickets';

	// Creating the events table
	$sql = "CREATE TABLE {$table_name} (
		ticket_id BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
		event_id BIGINT( 20 ) UNSIGNED NOT NULL ,
		ticket_name TINYTEXT NOT NULL ,
		ticket_description TEXT NULL ,
		ticket_price DECIMAL( 14 , 4 ) NULL ,
		ticket_start DATETIME NULL ,
		ticket_end DATETIME NULL ,
		ticket_min INT( 10 ) NULL ,
		ticket_max INT( 10 ) NULL ,
		ticket_spaces INT NULL ,
		ticket_members INT( 1 ) NULL ,
		ticket_members_roles LONGTEXT NULL,
		ticket_guests INT( 1 ) NULL ,
		ticket_required INT( 1 ) NULL ,
		ticket_parent BIGINT( 20 ) UNSIGNED NULL,
		ticket_order INT( 2 ) UNSIGNED NULL,
		ticket_meta LONGTEXT NULL,
		PRIMARY KEY  (ticket_id)
		) DEFAULT CHARSET=utf8 ;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name, array('event_id'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

//Add the categories table
function em_create_tickets_bookings_table() {
	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_tickets_bookings';

	// Creating the events table
	$sql = "CREATE TABLE {$table_name} (
		  ticket_booking_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  booking_id bigint(20) unsigned NOT NULL,
		  ticket_id bigint(20) unsigned NOT NULL,
		  ticket_booking_spaces int(6) NOT NULL,
		  ticket_booking_price decimal(14,4) NOT NULL,
		  PRIMARY KEY  (ticket_booking_id)
		) DEFAULT CHARSET=utf8 ;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name, array('booking_id','ticket_id'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_add_options() {
	global $wp_locale, $wpdb;
	$decimal_point = !empty($wp_locale->number_format['decimal_point']) ? $wp_locale->number_format['decimal_point']:'.';
	$thousands_sep = !empty($wp_locale->number_format['thousands_sep']) ? $wp_locale->number_format['thousands_sep']:',';
	$email_footer = '<br/><br/>-------------------------------<br/>Powered by Events Manager - http://wp-events-plugin.com';
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br/>You have successfully reserved #_BOOKINGSPACES space/spaces for #_EVENTNAME.<br/>When : #_EVENTDATES @ #_EVENTTIMES<br/>Where : #_LOCATIONNAME - #_LOCATIONFULLLINE<br/>Yours faithfully,<br/>#_CONTACTNAME",'events-manager').$email_footer;
	$respondent_email_pending_body_localizable = __("Dear #_BOOKINGNAME, <br/>You have requested #_BOOKINGSPACES space/spaces for #_EVENTNAME.<br/>When : #_EVENTDATES @ #_EVENTTIMES<br/>Where : #_LOCATIONNAME - #_LOCATIONFULLLINE<br/>Your booking is currently pending approval by our administrators. Once approved you will receive an automatic confirmation.<br/>Yours faithfully,<br/>#_CONTACTNAME",'events-manager').$email_footer;
	$respondent_email_rejected_body_localizable = __("Dear #_BOOKINGNAME, <br/>Your requested booking for #_BOOKINGSPACES spaces at #_EVENTNAME on #_EVENTDATES has been rejected.<br/>Yours faithfully,<br/>#_CONTACTNAME",'events-manager').$email_footer;
	$respondent_email_cancelled_body_localizable = __("Dear #_BOOKINGNAME, <br/>Your requested booking for #_BOOKINGSPACES spaces at #_EVENTNAME on #_EVENTDATES has been cancelled.<br/>Yours faithfully,<br/>#_CONTACTNAME",'events-manager').$email_footer;
	$event_approved_email_body = __("Dear #_CONTACTNAME, <br/>Your event #_EVENTNAME on #_EVENTDATES has been approved.<br/>You can view your event here: #_EVENTURL",'events-manager').$email_footer;
	$event_submitted_email_body = __("A new event has been submitted by #_CONTACTNAME.<br/>Name : #_EVENTNAME <br/>Date : #_EVENTDATES <br/>Time : #_EVENTTIMES <br/>Please visit #_EDITEVENTURL to review this event for approval.",'events-manager').$email_footer;
	$event_submitted_email_body = str_replace('#_EDITEVENTURL', admin_url().'post.php?action=edit&post=#_EVENTPOSTID', $event_submitted_email_body);
	$event_published_email_body = __("A new event has been published by #_CONTACTNAME.<br/>Name : #_EVENTNAME <br/>Date : #_EVENTDATES <br/>Time : #_EVENTTIMES <br/>Edit this event - #_EDITEVENTURL <br/> View this event - #_EVENTURL",'events-manager').$email_footer;
	$event_published_email_body = str_replace('#_EDITEVENTURL', admin_url().'post.php?action=edit&post=#_EVENTPOSTID', $event_published_email_body);
	$event_resubmitted_email_body = __("A previously published event has been modified by #_CONTACTNAME, and this event is now unpublished and pending your approval.<br/>Name : #_EVENTNAME <br/>Date : #_EVENTDATES <br/>Time : #_EVENTTIMES <br/>Please visit #_EDITEVENTURL to review this event for approval.",'events-manager').$email_footer;
	$event_resubmitted_email_body = str_replace('#_EDITEVENTURL', admin_url().'post.php?action=edit&post=#_EVENTPOSTID', $event_resubmitted_email_body);

	//event admin emails - new format to the above, standard format plus one unique line per booking status at the top of the body and subject line
	$contact_person_email_body_template = '#_EVENTNAME - #_EVENTDATES @ #_EVENTTIMES'.'<br/>'
 		    .__('Now there are #_BOOKEDSPACES spaces reserved, #_AVAILABLESPACES are still available.','events-manager').'<br/>'.
 		    strtoupper(__('Booking Details','events-manager')).'<br/>'.
 	 		__('Name','events-manager').' : #_BOOKINGNAME'.'<br/>'.
 		    __('Email','events-manager').' : #_BOOKINGEMAIL'.'<br/>'.
 		    '#_BOOKINGSUMMARY'.'<br/>'.
 		    '<br/>Powered by Events Manager - http://wp-events-plugin.com';
	$contact_person_emails['confirmed'] = sprintf(__('The following booking is %s :','events-manager'),strtolower(__('Confirmed','events-manager'))).'<br/>'.$contact_person_email_body_template;
	$contact_person_emails['pending'] = sprintf(__('The following booking is %s :','events-manager'),strtolower(__('Pending','events-manager'))).'<br/>'.$contact_person_email_body_template;
	$contact_person_emails['cancelled'] = sprintf(__('The following booking is %s :','events-manager'),strtolower(__('Cancelled','events-manager'))).'<br/>'.$contact_person_email_body_template;
	$contact_person_emails['rejected'] = sprintf(__('The following booking is %s :','events-manager'),strtolower(__('Rejected','events-manager'))).'<br/>'.$contact_person_email_body_template;
	//registration email content
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$booking_registration_email_subject = sprintf(__('[%s] Your username and password', 'events-manager'), $blogname);
	$booking_registration_email_body = sprintf(__('You have successfully created an account at %s', 'events-manager'), $blogname).
		'<br/>'.sprintf(__('You can log into our site here : %s', 'events-manager'), get_bloginfo('wpurl').'/wp-login.php').
		'<br/>'.__('Username', 'events-manager').' : %username%'.
		'<br/>'.__('Password', 'events-manager').' : %password%'.
		'<br/>'.sprintf(__('To view your bookings, please visit %s after logging in.', 'events-manager'), em_get_my_bookings_url());
	//all the options
	$dbem_options = array(
		'dbem_data' => array(), //used to store admin-related data such as notice flags and other row keys that may not always exist in the wp_options table
		//time formats
		'dbem_time_format' => get_option('time_format'),
		'dbem_date_format' => get_option('date_format'),
		'dbem_date_format_js' => 'dd/mm/yy',
		'dbem_dates_separator' => ' - ',
		'dbem_times_separator' => ' - ',
		//defaults
		'dbem_default_category'=>0,
		'dbem_default_location'=>0,
		//Event List Options
		'dbem_events_default_orderby' => 'event_start_date,event_start_time,event_name',
		'dbem_events_default_order' => 'ASC',
		'dbem_events_default_limit' => 10,
		//Event Search Options
		'dbem_search_form_submit' => __('Search','events-manager'),
		'dbem_search_form_advanced' => 1,
		'dbem_search_form_advanced_hidden' => 1,
		'dbem_search_form_advanced_show' => __('Show Advanced Search','events-manager'),
		'dbem_search_form_advanced_hide' => __('Hide Advanced Search','events-manager'),
		'dbem_search_form_text' => 1,
		'dbem_search_form_text_label' => __('Search','events-manager'),
		'dbem_search_form_geo' => 1,
		'dbem_search_form_geo_label' => __('Near...','events-manager'),
		'dbem_search_form_geo_units' => 1,
		'dbem_search_form_geo_units_label' => __('Within','events-manager'),
		'dbem_search_form_geo_unit_default' => 'mi',
		'dbem_search_form_geo_distance_default' => 25,
	    'dbem_search_form_geo_distance_options' => '5,10,25,50,100',
		'dbem_search_form_dates' => 1,
		'dbem_search_form_dates_label' => __('Dates','events-manager'),
		'dbem_search_form_dates_separator' => __('and','events-manager'),
		'dbem_search_form_categories' => 1,
		'dbem_search_form_categories_label' => __('All Categories','events-manager'),
		'dbem_search_form_category_label' => __('Category','events-manager'),
		'dbem_search_form_countries' => 1,
		'dbem_search_form_default_country' => get_option('dbem_location_default_country',''),
		'dbem_search_form_countries_label' => __('All Countries','events-manager'),
		'dbem_search_form_country_label' => __('Country','events-manager'),
		'dbem_search_form_regions' => 1,
		'dbem_search_form_regions_label' => __('All Regions','events-manager'),
		'dbem_search_form_region_label' => __('Region','events-manager'),
		'dbem_search_form_states' => 1,
		'dbem_search_form_states_label' => __('All States','events-manager'),
		'dbem_search_form_state_label' => __('State/County','events-manager'),
		'dbem_search_form_towns' => 0,
		'dbem_search_form_towns_label' => __('All Cities/Towns','events-manager'),
		'dbem_search_form_town_label' => __('City/Town','events-manager'),
		/*
		//GeoCoding
		'dbem_geo' => 1,
		'dbem_geonames_username' => '',
		*/
		//Event Form and Anon Submissions
		'dbem_events_form_editor' => 1,
		'dbem_events_form_reshow' => 1,
		'dbem_events_form_result_success' => __('You have successfully submitted your event, which will be published pending approval.','events-manager'),
		'dbem_events_form_result_success_updated' => __('You have successfully updated your event, which will be republished pending approval.','events-manager'),
		'dbem_events_anonymous_submissions' => 0,
		'dbem_events_anonymous_user' => 0,
		'dbem_events_anonymous_result_success' => __('You have successfully submitted your event, which will be published pending approval.','events-manager'),
		//Event Emails
		'dbem_event_submitted_email_admin' => '',
		'dbem_event_submitted_email_subject' => __('Submitted Event Awaiting Approval', 'events-manager'),
		'dbem_event_submitted_email_body' => str_replace("<br/>", "\n\r", $event_submitted_email_body),
		'dbem_event_resubmitted_email_subject' => __('Re-Submitted Event Awaiting Approval', 'events-manager'),
		'dbem_event_resubmitted_email_body' => str_replace("<br/>", "\n\r", $event_resubmitted_email_body),
		'dbem_event_published_email_subject' => __('Published Event', 'events-manager').' - #_EVENTNAME',
		'dbem_event_published_email_body' => str_replace("<br/>", "\n\r", $event_published_email_body),
		'dbem_event_approved_email_subject' => __("Event Approved",'events-manager'). " - #_EVENTNAME" ,
		'dbem_event_approved_email_body' => str_replace("<br/>", "\n\r", $event_approved_email_body),
		'dbem_event_reapproved_email_subject' => __("Event Approved",'events-manager'). " - #_EVENTNAME" ,
		'dbem_event_reapproved_email_body' => str_replace("<br/>", "\n\r", $event_approved_email_body),
		//Event Formatting
		'dbem_events_page_title' => __('Events','events-manager'),
		'dbem_events_page_scope' => 'future',
		'dbem_events_page_search_form' => 1,
		'dbem_event_list_item_format_header' => '<table class="events-table" >
    <thead>
        <tr>
			<th class="event-time" scope="col">'.__('Date/Time','events-manager').'</th>
			<th class="event-description" scope="col">'.__('Event','events-manager').'</th>
		</tr>
   	</thead>
    <tbody>',
		'dbem_event_list_item_format' => '<tr>
			<td>
                #_EVENTDATES<br/>
                #_EVENTTIMES
            </td>
            <td>
                #_EVENTLINK
                {has_location}<br/><i>#_LOCATIONNAME, #_LOCATIONTOWN #_LOCATIONSTATE</i>{/has_location}
            </td>
        </tr>',
		'dbem_event_list_item_format_footer' => '</tbody></table>',
		'dbem_event_list_groupby' => 0,
		'dbem_event_list_groupby_format' => '',
		'dbem_event_list_groupby_header_format' => '<h2>#s</h2>',
		'dbem_display_calendar_in_events_page' => 0,
		'dbem_single_event_format' => '<div style="float:right; margin:0px 0px 15px 15px;">#_LOCATIONMAP</div>
<p>
	<strong>'.esc_html__('Date/Time','events-manager').'</strong><br/>
	Date(s) - #_EVENTDATES<br /><i>#_EVENTTIMES</i>
</p>
{has_location}
<p>
	<strong>'.esc_html__('Location','events-manager').'</strong><br/>
	#_LOCATIONLINK
</p>
{/has_location}
<p>
	<strong>'.esc_html__('Categories','events-manager').'</strong>
	#_CATEGORIES
</p>
<br style="clear:both" />
#_EVENTNOTES
{has_bookings}
<h3>'.esc_html__('Bookings','events-manager').'</h3>
#_BOOKINGFORM
{/has_bookings}',
	    'dbem_event_excerpt_format' => '#_EVENTDATES @ #_EVENTTIMES - #_EVENTEXCERPT',
	    'dbem_event_excerpt_alt_format' => '#_EVENTDATES @ #_EVENTTIMES - #_EVENTEXCERPT{55}',
		'dbem_event_page_title_format' => '#_EVENTNAME',
		'dbem_event_all_day_message' => __('All Day','events-manager'),
		'dbem_no_events_message' => sprintf(__( 'No %s', 'events-manager'),__('Events','events-manager')),
		//Location Formatting
		'dbem_locations_default_orderby' => 'location_name',
		'dbem_locations_default_order' => 'ASC',
		'dbem_locations_default_limit' => 10,
		'dbem_locations_page_title' => __('Event','events-manager')." ".__('Locations','events-manager'),
		'dbem_locations_page_search_form' => 1,
		'dbem_no_locations_message' => sprintf(__( 'No %s', 'events-manager'),__('Locations','events-manager')),
		'dbem_location_default_country' => '',
		'dbem_location_list_item_format_header' => '<ul class="em-locations-list">',
		'dbem_location_list_item_format' => '<li>#_LOCATIONLINK<ul><li>#_LOCATIONFULLLINE</li></ul></li>',
		'dbem_location_list_item_format_footer' => '</ul>',
		'dbem_location_page_title_format' => '#_LOCATIONNAME',
		'dbem_single_location_format' => '<div style="float:right; margin:0px 0px 15px 15px;">#_LOCATIONMAP</div>
<p>
	<strong>'.__('Address','events-manager').'</strong><br/>
	#_LOCATIONADDRESS<br/>
	#_LOCATIONTOWN<br/>
	#_LOCATIONSTATE<br/>
	#_LOCATIONREGION<br/>
	#_LOCATIONPOSTCODE<br/>
	#_LOCATIONCOUNTRY
</p>
<br style="clear:both" />
#_LOCATIONNOTES

<h3>'.__('Upcoming Events','events-manager').'</h3>
<p>#_LOCATIONNEXTEVENTS</p>',
	    'dbem_location_excerpt_format' => '#_LOCATIONEXCERPT',
	    'dbem_location_excerpt_alt_format' => '#_LOCATIONEXCERPT{55}',
		'dbem_location_no_events_message' => '<li>'.__('No events in this location', 'events-manager').'</li>',
		'dbem_location_event_list_item_header_format' => "<ul>",
		'dbem_location_event_list_item_format' => "<li>#_EVENTLINK - #_EVENTDATES - #_EVENTTIMES</li>",
		'dbem_location_event_list_item_footer_format' => "</ul>",
		'dbem_location_event_list_limit' => 20,
		'dbem_location_event_list_orderby' => 'event_start_date,event_start_time,event_name',
		'dbem_location_event_list_order' => 'ASC',
		'dbem_location_event_single_format' => '#_EVENTLINK - #_EVENTDATES - #_EVENTTIMES',
		'dbem_location_no_event_message' => __('No events in this location', 'events-manager'),
		//Category page options
		'dbem_categories_default_limit' => 10,
		'dbem_categories_default_orderby' => 'name',
		'dbem_categories_default_order' =>  'ASC',
		//Categories Page Formatting
		'dbem_categories_list_item_format_header' => '<ul class="em-categories-list">',
		'dbem_categories_list_item_format' => '<li>#_CATEGORYLINK</li>',
		'dbem_categories_list_item_format_footer' => '</ul>',
		'dbem_no_categories_message' =>  sprintf(__( 'No %s', 'events-manager'),__('Categories','events-manager')),
		//Category Formatting
		'dbem_category_page_title_format' => '#_CATEGORYNAME',
		'dbem_category_page_format' => '#_CATEGORYNOTES<h3>'.__('Upcoming Events','events-manager').'</h3>#_CATEGORYNEXTEVENTS',
		'dbem_category_no_events_message' => '<li>'.__('No events in this category', 'events-manager').'</li>',
		'dbem_category_event_list_item_header_format' => '<ul>',
		'dbem_category_event_list_item_format' => "<li>#_EVENTLINK - #_EVENTDATES - #_EVENTTIMES</li>",
		'dbem_category_event_list_item_footer_format' => '</ul>',
		'dbem_category_event_list_limit' => 20,
		'dbem_category_event_list_orderby' => 'event_start_date,event_start_time,event_name',
		'dbem_category_event_list_order' => 'ASC',
		'dbem_category_event_single_format' => '#_EVENTLINK - #_EVENTDATES - #_EVENTTIMES',
		'dbem_category_no_event_message' => __('No events in this category', 'events-manager'),
		'dbem_category_default_color' => '#a8d144',
		//Tags page options
		'dbem_tags_default_limit' => 10,
		'dbem_tags_default_orderby' => 'name',
		'dbem_tags_default_order' =>  'ASC',
		//Tags Page Formatting
		'dbem_tags_list_item_format_header' => '<ul class="em-tags-list">',
		'dbem_tags_list_item_format' => '<li>#_TAGLINK</li>',
		'dbem_tags_list_item_format_footer' => '</ul>',
		'dbem_no_tags_message' =>  sprintf(__( 'No %s', 'events-manager'),__('Tags','events-manager')),
		//Tag Page Formatting
		'dbem_tag_page_title_format' => '#_TAGNAME',
		'dbem_tag_page_format' => '<h3>'.__('Upcoming Events','events-manager').'</h3>#_TAGNEXTEVENTS',
		'dbem_tag_no_events_message' => '<li>'.__('No events with this tag', 'events-manager').'</li>',
		'dbem_tag_event_list_item_header_format' => '<ul class="em-tags-list">',
		'dbem_tag_event_list_item_format' => "<li>#_EVENTLINK - #_EVENTDATES - #_EVENTTIMES</li>",
		'dbem_tag_event_list_item_footer_format' => '</ul>',
		'dbem_tag_event_single_format' => '#_EVENTLINK - #_EVENTDATES - #_EVENTTIMES',
		'dbem_tag_no_event_message' => __('No events with this tag', 'events-manager'),
		'dbem_tag_event_list_limit' => 20,
		'dbem_tag_event_list_orderby' => 'event_start_date,event_start_time,event_name',
		'dbem_tag_event_list_order' => 'ASC',
		'dbem_tag_default_color' => '#a8d145',
		//RSS Stuff
		'dbem_rss_limit' => 50,
		'dbem_rss_scope' => 'future',
		'dbem_rss_main_title' => get_bloginfo('title')." - ".__('Events', 'events-manager'),
		'dbem_rss_main_description' => get_bloginfo('description')." - ".__('Events', 'events-manager'),
		'dbem_rss_description_format' => "#_EVENTDATES - #_EVENTTIMES <br/>#_LOCATIONNAME <br/>#_LOCATIONADDRESS <br/>#_LOCATIONTOWN",
		'dbem_rss_title_format' => "#_EVENTNAME",
		'dbem_rss_order' => get_option('dbem_events_default_order', 'ASC'), //get event order and orderby or use same new installation defaults
		'dbem_rss_orderby' => get_option('dbem_events_default_orderby', 'event_start_date,event_start_time,event_name'),
		'em_rss_pubdate' => date('D, d M Y H:i:s +0000'),
		//iCal Stuff
		'dbem_ical_limit' => 50,
		'dbem_ical_scope' => "future",
		'dbem_ical_description_format' => "#_EVENTNAME",
		'dbem_ical_real_description_format' => "#_EVENTEXCERPT",
		'dbem_ical_location_format' => "#_LOCATIONNAME, #_LOCATIONFULLLINE, #_LOCATIONCOUNTRY",
		//Google Maps
		'dbem_gmap_is_active'=> 1,
		'dbem_google_maps_browser_key'=> '',
		'dbem_map_default_width'=> '400px', //eventually will use %
		'dbem_map_default_height'=> '300px',
		'dbem_location_baloon_format' => '<strong>#_LOCATIONNAME</strong><br/>#_LOCATIONADDRESS - #_LOCATIONTOWN<br/><a href="#_LOCATIONPAGEURL">'.__('Events', 'events-manager').'</a>',
		'dbem_map_text_format' => '<strong>#_LOCATIONNAME</strong><p>#_LOCATIONADDRESS</p><p>#_LOCATIONTOWN</p>',
		//Email Config
		'dbem_email_disable_registration' => 0,
		'dbem_rsvp_mail_port' => 465,
		'dbem_smtp_host' => 'localhost',
		'dbem_mail_sender_name' => '',
		'dbem_rsvp_mail_send_method' => 'wp_mail',
		'dbem_rsvp_mail_SMTPAuth' => 1,
		'dbem_smtp_html' => 1,
		'dbem_smtp_html_br' => 1,
		'dbem_smtp_encryption' => 'tls',
		'dbem_smtp_autotls' => true,
		//Image Manipulation
		'dbem_image_max_width' => 700,
		'dbem_image_max_height' => 700,
		'dbem_image_min_width' => 50,
		'dbem_image_min_height' => 50,
		'dbem_image_max_size' => 204800,
		//Calendar Options
		'dbem_list_date_title' => __('Events', 'events-manager').' - #j #M #y',
		'dbem_full_calendar_month_format' => 'M Y',
		'dbem_full_calendar_event_format' => '<li>#_EVENTLINK</li>',
		'dbem_full_calendar_long_events' => '0',
		'dbem_full_calendar_initials_length' => 0,
		'dbem_full_calendar_abbreviated_weekdays' => true,
		'dbem_display_calendar_day_single_yes' => 1,
		'dbem_small_calendar_month_format' => 'M Y',
		'dbem_small_calendar_event_title_format' => "#_EVENTNAME",
		'dbem_small_calendar_event_title_separator' => ", ",
		'dbem_small_calendar_initials_length' => 1,
		'dbem_small_calendar_abbreviated_weekdays' => false,
		'dbem_small_calendar_long_events' => '0',
		'dbem_display_calendar_order' => 'ASC',
		'dbem_display_calendar_orderby' => 'event_name,event_start_time',
		'dbem_display_calendar_events_limit' => get_option('dbem_full_calendar_events_limit',3),
		'dbem_display_calendar_events_limit_msg' => __('more...','events-manager'),
		'dbem_calendar_direct_links' => 1,
		//General Settings
		'dbem_timezone_enabled' => 1,
		'dbem_timezone_default' => EM_DateTimeZone::create()->getName(),
		'dbem_require_location' => 0,
		'dbem_locations_enabled' => 1,
		'dbem_location_types' => array('location' => 1, 'url' => 1),
		'dbem_use_select_for_locations' => 0,
		'dbem_attributes_enabled' => 1,
		'dbem_recurrence_enabled'=> 1,
		'dbem_rsvp_enabled'=> 1,
		'dbem_categories_enabled'=> 1,
		'dbem_tags_enabled' => 1,
		'dbem_placeholders_custom' => '',
		'dbem_location_attributes_enabled' => 1,
		'dbem_location_placeholders_custom' => '',
		//Bookings
		'dbem_bookings_registration_disable' => 0,
		'dbem_bookings_registration_disable_user_emails' => 0,
		'dbem_bookings_approval' => 1, //approval is on by default
		'dbem_bookings_approval_reserved' => 0, //overbooking before approval?
		'dbem_bookings_approval_overbooking' => 0, //overbooking possible when approving?
		'dbem_bookings_double'=>0,//double bookings or more, users can't double book by default
		'dbem_bookings_user_cancellation' => 1, //can users cancel their booking?
		'dbem_bookings_currency' => 'USD',
		'dbem_bookings_currency_decimal_point' => $decimal_point,
		'dbem_bookings_currency_thousands_sep' => $thousands_sep,
		'dbem_bookings_currency_format' => '@#',
		'dbem_bookings_tax' => 0, //extra tax
		'dbem_bookings_tax_auto_add' => 0, //adjust prices to show tax?
			//Form Options
			'dbem_bookings_submit_button' => __('Send your booking', 'events-manager'),
			'dbem_bookings_login_form' => 1, //show login form on booking area
			'dbem_bookings_anonymous' => 1,
			'dbem_bookings_form_max' => 20,
			//Messages
			'dbem_bookings_form_msg_disabled' => __('Online bookings are not available for this event.','events-manager'),
			'dbem_bookings_form_msg_closed' => __('Bookings are closed for this event.','events-manager'),
			'dbem_bookings_form_msg_full' => __('This event is fully booked.','events-manager'),
			'dbem_bookings_form_msg_attending'=>__('You are currently attending this event.','events-manager'),
			'dbem_bookings_form_msg_bookings_link'=>__('Manage my bookings','events-manager'),
			//messages
			'dbem_booking_warning_cancel' => __('Are you sure you want to cancel your booking?','events-manager'),
			'dbem_booking_feedback_cancelled' =>sprintf(__('Booking %s','events-manager'), __('Cancelled','events-manager')),
			'dbem_booking_feedback_pending' =>__('Booking successful, pending confirmation (you will also receive an email once confirmed).', 'events-manager'),
			'dbem_booking_feedback' => __('Booking successful.', 'events-manager'),
			'dbem_booking_feedback_full' => __('Booking cannot be made, not enough spaces available!', 'events-manager'),
			'dbem_booking_feedback_log_in' => __('You must log in or register to make a booking.','events-manager'),
			'dbem_booking_feedback_nomail' => __('However, there were some problems whilst sending confirmation emails to you and/or the event contact person. You may want to contact them directly and letting them know of this error.', 'events-manager'),
			'dbem_booking_feedback_error' => __('Booking could not be created','events-manager').':',
			'dbem_booking_feedback_email_exists' => __('This email already exists in our system, please log in to register to proceed with your booking.','events-manager'),
			'dbem_booking_feedback_new_user' => __('A new user account has been created for you. Please check your email for access details.','events-manager'),
			'dbem_booking_feedback_reg_error' => __('There was a problem creating a user account, please contact a website administrator.','events-manager'),
			'dbem_booking_feedback_already_booked' => __('You already have booked a seat at this event.','events-manager'),
			'dbem_booking_feedback_min_space' => __('You must request at least one space to book an event.','events-manager'),
			'dbem_booking_feedback_spaces_limit' => __('You cannot book more than %d spaces for this event.','events-manager'),
			//button messages
			'dbem_booking_button_msg_book' => __('Book Now', 'events-manager'),
			'dbem_booking_button_msg_booking' => __('Booking...','events-manager'),
			'dbem_booking_button_msg_booked' => sprintf(__('%s Submitted','events-manager'), __('Booking','events-manager')),
			'dbem_booking_button_msg_already_booked' => __('Already Booked','events-manager'),
			'dbem_booking_button_msg_error' => sprintf(__('%s Error. Try again?','events-manager'), __('Booking','events-manager')),
			'dbem_booking_button_msg_full' => __('Sold Out', 'events-manager'),
            'dbem_booking_button_msg_closed' => ucwords(__( 'Bookings closed', 'events-manager')), //ucwords it to prevent extra translation
			'dbem_booking_button_msg_cancel' => __('Cancel', 'events-manager'),
			'dbem_booking_button_msg_canceling' => __('Canceling...','events-manager'),
			'dbem_booking_button_msg_cancelled' => __('Cancelled','events-manager'),
			'dbem_booking_button_msg_cancel_error' => sprintf(__('%s Error. Try again?','events-manager'), __('Cancellation','events-manager')),
			//Emails
			'dbem_bookings_notify_admin' => 0,
			'dbem_bookings_contact_email' => 1,
			'dbem_bookings_contact_email_pending_subject' => __("Booking Pending",'events-manager'),
			'dbem_bookings_contact_email_pending_body' => str_replace("<br/>", "\n\r", $contact_person_emails['pending']),
			'dbem_bookings_contact_email_confirmed_subject' => __('Booking Confirmed','events-manager'),
			'dbem_bookings_contact_email_confirmed_body' => str_replace("<br/>", "\n\r", $contact_person_emails['confirmed']),
			'dbem_bookings_contact_email_rejected_subject' => __("Booking Rejected",'events-manager'),
			'dbem_bookings_contact_email_rejected_body' => str_replace("<br/>", "\n\r", $contact_person_emails['rejected']),
			'dbem_bookings_contact_email_cancelled_subject' => __("Booking Cancelled",'events-manager'),
			'dbem_bookings_contact_email_cancelled_body' => str_replace("<br/>", "\n\r", $contact_person_emails['cancelled']),
			'dbem_bookings_email_pending_subject' => __("Booking Pending",'events-manager'),
			'dbem_bookings_email_pending_body' => str_replace("<br/>", "\n\r", $respondent_email_pending_body_localizable),
			'dbem_bookings_email_rejected_subject' => __("Booking Rejected",'events-manager'),
			'dbem_bookings_email_rejected_body' => str_replace("<br/>", "\n\r", $respondent_email_rejected_body_localizable),
			'dbem_bookings_email_confirmed_subject' => __('Booking Confirmed','events-manager'),
			'dbem_bookings_email_confirmed_body' => str_replace("<br/>", "\n\r", $respondent_email_body_localizable),
			'dbem_bookings_email_cancelled_subject' => __('Booking Cancelled','events-manager'),
			'dbem_bookings_email_cancelled_body' => str_replace("<br/>", "\n\r", $respondent_email_cancelled_body_localizable),
			//Registration Email
			'dbem_bookings_email_registration_subject' => $booking_registration_email_subject,
			'dbem_bookings_email_registration_body' => str_replace("<br/>", "\n\r", $booking_registration_email_body),
			//Ticket Specific Options
			'dbem_bookings_tickets_ordering' => 1,
			'dbem_bookings_tickets_orderby' => 'ticket_price DESC, ticket_name ASC',
			'dbem_bookings_tickets_priority' => 0,
			'dbem_bookings_tickets_show_unavailable' => 0,
			'dbem_bookings_tickets_show_loggedout' => 1,
			'dbem_bookings_tickets_single' => 0,
			'dbem_bookings_tickets_single_form' => 0,
			//My Bookings Page
			'dbem_bookings_my_title_format' => __('My Bookings','events-manager'),
		//Flags
		'dbem_hello_to_user' => 1,
		//BP Settings
		'dbem_bp_events_list_format_header' => '<ul class="em-events-list">',
		'dbem_bp_events_list_format' => '<li>#_EVENTLINK - #_EVENTDATES - #_EVENTTIMES<ul><li>#_LOCATIONLINK - #_LOCATIONADDRESS, #_LOCATIONTOWN</li></ul></li>',
		'dbem_bp_events_list_format_footer' => '</ul>',
		'dbem_bp_events_list_none_format' => '<p class="em-events-list">'.__('No Events','events-manager').'</p>',
		//custom CSS options for public pages
		'dbem_css_editors' => 1,
		'dbem_css_rsvp' => 1, //my bookings page
		'dbem_css_rsvpadmin' => 1, //my event bookings page
		'dbem_css_evlist' => 1,
		'dbem_css_search' => 1,
		'dbem_css_loclist' => 1,
		'dbem_css_catlist' => 1,
		'dbem_css_taglist' => 1,
		/*
		 * Custom Post Options - set up to mimick old EM settings and install with minimal setup for most users
		 */
		//slugs
		'dbem_cp_events_slug' => 'events',
		'dbem_cp_locations_slug' => 'locations',
		'dbem_taxonomy_category_slug' => 'events/categories',
		'dbem_taxonomy_tag_slug' => 'events/tags',
		//event cp options
		'dbem_cp_events_template' => '',
		//'dbem_cp_events_template_page' => 0, DEPREICATED
		'dbem_cp_events_body_class' => '',
		'dbem_cp_events_post_class' => '',
		'dbem_cp_events_formats' => 1,
		'dbem_cp_events_has_archive' => 1,
		'dbem_events_default_archive_orderby' => '_event_start',
		'dbem_events_default_archive_order' => 'ASC',
		'dbem_events_archive_scope' => 'past',
		'dbem_cp_events_archive_formats' => 1,
	    'dbem_cp_events_excerpt_formats' => 1,
		'dbem_cp_events_search_results' => 0,
		'dbem_cp_events_custom_fields' => 0,
		'dbem_cp_events_comments' => 1,
		//location cp options
		'dbem_cp_locations_template' => '',
		//'dbem_cp_locations_template_page' => 0, DEPREICATED
		'dbem_cp_locations_body_class' => '',
		'dbem_cp_locations_post_class' => '',
		'dbem_cp_locations_formats' => 1,
		'dbem_cp_locations_has_archive' => 1,
		'dbem_locations_default_archive_orderby' => 'title',
		'dbem_locations_default_archive_order' => 'ASC',
		'dbem_cp_locations_archive_formats' => 1,
	    'dbem_cp_locations_excerpt_formats' => 1,
		'dbem_cp_locations_search_results' => 0,
		'dbem_cp_locations_custom_fields' => 0,
		'dbem_cp_locations_comments' => 1,
		//category cp options
		'dbem_cp_categories_formats' => 1,
		'dbem_categories_default_archive_orderby' => 'event_start_date,event_start_time,event_name',
		'dbem_categories_default_archive_order' => 'ASC',
		//category cp options
		'dbem_cp_tags_formats' => 1,
		'dbem_tags_default_archive_orderby' => 'event_start_date,event_start_time,event_name',
		'dbem_tags_default_archive_order' => 'ASC',
	    //optimization options
	    'dbem_disable_thumbnails'=> false,
	    //feedback reminder
	    'dbem_feedback_reminder' => time(),
	    'dbem_events_page_ajax' => 0,
	    'dbem_conditional_recursions' => 1,
        //data privacy/protection
        'dbem_data_privacy_consent_text' => esc_html__('I consent to my submitted data being collected and stored as outlined by the site %s.','events-manager'),
        'dbem_data_privacy_consent_remember' => 1,
		'dbem_data_privacy_consent_events' => 1,
		'dbem_data_privacy_consent_locations' => 1,
		'dbem_data_privacy_consent_bookings' => 1,
		'dbem_data_privacy_export_events' => 1,
		'dbem_data_privacy_export_locations' => 1,
		'dbem_data_privacy_export_bookings' => 1,
		'dbem_data_privacy_erase_events' => 1,
		'dbem_data_privacy_erase_locations' => 1,
		'dbem_data_privacy_erase_bookings' => 1
	);
	
	//do date js according to locale:
	$locale_code = substr ( pll_current_language (), 0, 2 );
	$locale_dates = array('nl' => 'dd/mm/yy', 'af' => 'dd/mm/yy', 'ar' => 'dd/mm/yy', 'az' => 'dd.mm.yy', 'bg' => 'dd.mm.yy', 'bs' => 'dd.mm.yy', 'cs' => 'dd.mm.yy', 'da' => 'dd-mm-yy', 'de' => 'dd.mm.yy', 'el' => 'dd/mm/yy', 'en-GB' => 'dd/mm/yy', 'eo' => 'dd/mm/yy', 'et' => 'dd.mm.yy', 'eu' => 'yy/mm/dd', 'fa' => 'yy/mm/dd', 'fo' => 'dd-mm-yy', 'fr' => 'dd.mm.yy', 'fr' => 'dd/mm/yy', 'he' => 'dd/mm/yy', 'hu' => 'yy.mm.dd.', 'hr' => 'dd.mm.yy.', 'ja' => 'yy/mm/dd', 'ro' => 'dd.mm.yy', 'sk' =>  'dd.mm.yy', 'sq' => 'dd.mm.yy', 'sr' => 'dd/mm/yy', 'sr' => 'dd/mm/yy', 'sv' => 'yy-mm-dd', 'ta' => 'dd/mm/yy', 'th' => 'dd/mm/yy', 'vi' => 'dd/mm/yy', 'zh' => 'yy/mm/dd', 'es' => 'dd/mm/yy', 'it' => 'dd/mm/yy');
	if( array_key_exists($locale_code, $locale_dates) ){
		$dbem_options['dbem_date_format_js'] = $locale_dates[$locale_code];
	}
	
	//add new options
	foreach($dbem_options as $key => $value){
		add_option($key, $value);
	}
		
	//set time localization for first time depending on current settings
	if( get_option('dbem_time_24h','not set') == 'not set'){
		//Localise vars regardless
		$locale_code = substr ( pll_current_language(), 0, 2 );
		if (preg_match('/^en_(?:GB|IE|AU|NZ|ZA|TT|JM)$/', pll_current_language())) {
		    $locale_code = 'en-GB';
		}
		//Set time
		$show24Hours = ( !preg_match("/en|sk|zh|us|uk/", $locale_code ) );	// Setting 12 hours format for those countries using it
		update_option('dbem_time_24h', $show24Hours);
	}
}

function em_upgrade_current_installation(){
	global $wpdb, $wp_locale, $EM_Notices;
	
	// Check EM Pro update min
	if( defined('EMP_VERSION') && EMP_VERSION < EM_PRO_MIN_VERSION && !defined('EMP_DISABLE_WARNINGS') ) {
		$message = esc_html__('There is a newer version of Events Manager Pro which is recommended for this current version of Events Manager as new features have been added. Please go to the plugin website and download the latest update.','events-manager');
		$EM_Admin_Notice = new EM_Admin_Notice(array('name' => 'em-pro-updates', 'who' => 'admin', 'where' => 'all', 'message' => "$message"));
		EM_Admin_Notices::add($EM_Admin_Notice, is_multisite());
	}
	
	
	if( !get_option('dbem_version') ){ add_option('dbem_credits',1); }
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5 ){
		//make events, cats and locs pages
		update_option('dbem_cp_events_template_page',1);
		update_option('dbem_cp_locations_template_page',1);
		//reset orderby, or convert fields to new fieldnames
		$EM_Event = new EM_Event();
		$orderbyvals = explode(',', get_option('dbem_events_default_orderby'));
		$orderby = array();
		foreach($orderbyvals as $val){
			if(array_key_exists('event_'.$val, $EM_Event->fields)){
				$orderby[] = 'event_'.$val;
			}
		}
		$orderby = (count($orderby) > 0) ? implode(',',$orderby):$dbem_options['dbem_events_default_orderby'];
		update_option('dbem_events_default_orderby',$orderby);
		//Locations and categories weren't controlled in v4, so just reset them
		update_option('dbem_locations_default_orderby','location_name');
		update_option('dbem_categories_default_orderby','name');
		//Update the slugs if necessary
		$events_page_id = get_option ( 'dbem_events_page' );
		$events_page = get_post($events_page_id);
		update_option('dbem_cp_events_slug', $events_page->post_name);
		update_option('dbem_taxonomy_tag_slug', $events_page->post_name.'/tags');
		if( defined('EM_LOCATIONS_SLUG') && EM_LOCATIONS_SLUG != 'locations' ) update_option('dbem_cp_locations_slug', EM_LOCATIONS_SLUG);
		if( defined('EM_CATEGORIES_SLUG') && EM_CATEGORIES_SLUG != 'categories' ) update_option('dbem_taxonomy_category_slug', $events_page->post_name.'/'.EM_CATEGORIES_SLUG);
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.19 ){
		update_option('dbem_event_reapproved_email_subject',  get_option('dbem_event_approved_email_subject'));
		update_option('dbem_event_reapproved_email_body', get_option('dbem_event_approved_email_body'));
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') <= 5.21 ){
		//just remove all rsvp cut-off info
		$wpdb->query("UPDATE ".$wpdb->postmeta." SET meta_value = NULL WHERE meta_key IN ('_event_rsvp_date','_event_rsvp_time') AND post_id IN (SELECT post_id FROM ".EM_EVENTS_TABLE." WHERE recurrence_id > 0)");
		$wpdb->query("UPDATE ".EM_EVENTS_TABLE." SET event_rsvp_time = NULL, event_rsvp_date = NULL WHERE recurrence_id > 0");
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.364 ){
		if( get_option('dbem_cp_events_template_page') ){
			update_option('dbem_cp_events_template', 'page');
			delete_option('dbem_cp_events_template_page');
		}
		if( get_option('dbem_cp_locations_template_page') ){
			update_option('dbem_cp_locations_template', 'page');
			delete_option('dbem_cp_locations_template_page');
		}
		update_option('dbem_events_archive_scope', get_option('dbem_events_page_scope'));
		update_option('em_last_modified', current_time('timestamp', true));
		update_option('dbem_category_event_single_format',get_option('dbem_category_event_list_item_header_format').get_option('dbem_category_event_list_item_format').get_option('dbem_category_event_list_item_footer_format'));
		update_option('dbem_category_no_event_message',get_option('dbem_category_event_list_item_header_format').get_option('dbem_category_no_events_message').get_option('dbem_category_event_list_item_footer_format'));
		update_option('dbem_location_event_single_format',get_option('dbem_location_event_list_item_header_format').get_option('dbem_location_event_list_item_format').get_option('dbem_location_event_list_item_footer_format'));
		update_option('dbem_location_no_event_message',get_option('dbem_location_event_list_item_header_format').get_option('dbem_location_no_events_message').get_option('dbem_location_event_list_item_footer_format'));
		update_option('dbem_tag_event_single_format',get_option('dbem_tag_event_list_item_header_format').get_option('dbem_tag_event_list_item_format').get_option('dbem_tag_event_list_item_footer_format'));
		update_option('dbem_tag_no_event_message',get_option('dbem_tag_event_list_item_header_format').get_option('dbem_tag_no_events_message').get_option('dbem_tag_event_list_item_footer_format'));
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.38 ){
		update_option('dbem_dates_separator', get_option('dbem_dates_Seperator', get_option('dbem_dates_seperator',' - ')));
		update_option('dbem_times_separator', get_option('dbem_times_Seperator', get_option('dbem_times_seperator',' - ')));
		delete_option('dbem_dates_Seperator');
		delete_option('dbem_times_Seperator');
		delete_option('dbem_dates_seperator');
		delete_option('dbem_times_seperator');
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.4 ){
		//tax rates now saved at booking level, so that alterations to tax rates don't change previous booking prices
		//any past bookings that don't get updated will adhere to these two values when calculating prices
		update_option('dbem_legacy_bookings_tax_auto_add', get_option('dbem_bookings_tax_auto_add'));
		update_option('dbem_legacy_bookings_tax', get_option('dbem_bookings_tax'));
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.422 ){
		//copy registration email content into new setting
		update_option('dbem_rss_limit',0);
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.4425 ){
		//copy registration email content into new setting
		update_option('dbem_css_editors',0);
		update_option('dbem_css_rsvp',0);
		update_option('dbem_css_evlist',0);
		update_option('dbem_css_loclist',0);
		update_option('dbem_css_rsvpadmin',0);
		update_option('dbem_css_catlist',0);
		update_option('dbem_css_taglist',0);
		if( locate_template('plugins/events-manager/templates/events-search.php') ){
			update_option('dbem_css_search', 0);
			update_option('dbem_search_form_hide_advanced',0);
		}
		update_option('dbem_events_page_search_form',get_option('dbem_events_page_search'));
		update_option('dbem_search_form_dates_separator',get_option('dbem_dates_separator'));
		delete_option('dbem_events_page_search'); //avoids the double search form on overridden templates
		update_option('dbem_locations_page_search_form',0); //upgrades shouldn't get extra surprises
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.512 ){
		update_option('dbem_search_form_geo_units',0); //don't display units search for previous installs
		//correcting the typo
		update_option('dbem_search_form_submit', get_option('dbem_serach_form_submit'));
		//if template isn't overridden, assume it is still being used
		if( !locate_template('plugins/events-manager/templates/events-search.php') ){
			delete_option('dbem_serach_form_submit', 0);
		}
		//ML translation
		if( get_option('dbem_serach_form_submit_ml') ){
			update_option('dbem_search_form_submit_ml', get_option('dbem_serach_form_submit_ml'));
			delete_option('dbem_serach_form_submit_ml'); //we can assume this isn't used in templates
		}
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.54 ){
		update_option('dbem_cp_events_excerpt_formats',0); //don't override excerpts in previous installs
		update_option('dbem_cp_locations_excerpt_formats',0);
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.55 ){
		//rename email templates sent to admins on new bookings
		update_option('dbem_bookings_contact_email_cancelled_subject',get_option('dbem_contactperson_email_cancelled_subject'));
		update_option('dbem_bookings_contact_email_cancelled_body',get_option('dbem_contactperson_email_cancelled_body'));
		if( get_option('dbem_bookings_approval') ){
			//if approvals ENABLED, we should make the old 'New Booking' email the one for a pending booking
			update_option('dbem_bookings_contact_email_pending_subject',get_option('dbem_bookings_contact_email_subject'));
			update_option('dbem_bookings_contact_email_pending_body',get_option('dbem_bookings_contact_email_body'));
		}else{
			//if approvals DISABLED, we should make the old 'New Booking' email the one for a confirmed booking
			update_option('dbem_bookings_contact_email_confirmed_subject',get_option('dbem_bookings_contact_email_subject'));
			update_option('dbem_bookings_contact_email_confirmed_body',get_option('dbem_bookings_contact_email_body'));
		}
		delete_option('dbem_contactperson_email_cancelled_subject');
		delete_option('dbem_contactperson_email_cancelled_body');
		delete_option('dbem_bookings_contact_email_subject');
		delete_option('dbem_bookings_contact_email_body');
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.62 ){
		//delete all _event_created_date and _event_date_modified records in post_meta, we don't need them anymore, they were never accurate to begin with, refer to the records in em_events table if still needed
		$wpdb->query('DELETE FROM '.$wpdb->postmeta." WHERE (meta_key='_event_date_created' OR meta_key='_event_date_modified') AND post_id IN (SELECT ID FROM ".$wpdb->posts." WHERE post_type='".EM_POST_TYPE_EVENT."' OR post_type='event-recurring')");
		$wpdb->query('ALTER TABLE '. $wpdb->prefix.'em_bookings CHANGE event_id event_id BIGINT(20) UNSIGNED NULL');
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.66 ){
		if( get_option('dbem_ical_description_format') == "#_EVENTNAME - #_LOCATIONNAME - #_EVENTDATES - #_EVENTTIMES" ) update_option('dbem_ical_description_format',"#_EVENTNAME");
		if( get_option('dbem_ical_location_format') == "#_LOCATION" ) update_option('dbem_ical_location_format', "#_LOCATIONNAME, #_LOCATIONFULLLINE, #_LOCATIONCOUNTRY");
		$old_values = array(
				'dbem_ical_description_format' => "#_EVENTNAME - #_LOCATIONNAME - #_EVENTDATES - #_EVENTTIMES",
				'dbem_ical_location_format' => "#_LOCATION",
		);
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.6636 ){
		$sql = $wpdb->prepare("DELETE FROM {$wpdb->postmeta} WHERE meta_key='_post_id' AND post_id IN (SELECT ID FROM {$wpdb->posts} WHERE post_type=%s OR post_type=%s)", array(EM_POST_TYPE_EVENT, 'event-recurring'));
		$wpdb->query($sql);
		remove_filter('pre_option_dbem_bookings_registration_user', 'EM_People::dbem_bookings_registration_user');
		$no_user = get_option('dbem_bookings_registration_user');
		if( get_option('dbem_bookings_registration_disable') && is_numeric($no_user) ){
			if( $wpdb->update(EM_BOOKINGS_TABLE, array('person_id'=>0), array('person_id'=>$no_user), '%d', '%d') ){
				delete_option('dbem_bookings_registration_user');
			}
		}else{
			delete_option('dbem_bookings_registration_user');
		}
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.821 ){
		$admin_data = get_option('dbem_data');
		//upgrade tables only if we didn't do it before during earlier dev versions
		if( empty($admin_data['datetime_backcompat']) ){
			$migration_result = em_migrate_datetime_timezones( false );
			if( $migration_result !== true ){
				$EM_Notices->add_error($migration_result);
			}
			//migrate certain options
			$opt = get_option('dbem_tags_default_archive_orderby');
			if( $opt == '_start_ts' ) update_option('dbem_tags_default_archive_orderby', '_event_start');
			$opt = get_option('dbem_categories_default_archive_orderby');
			if( $opt == '_start_ts' ) update_option('dbem_categories_default_archive_orderby', '_event_start');
			$opt = get_option('dbem_events_default_archive_orderby');
			if( $opt == '_start_ts' ) update_option('dbem_events_default_archive_orderby', '_event_start');
		}else{
			//we're doing this at multisite level instead within dev versions, so fix this for dev versions
			unset( $admin_data['datetime_backcompat'] );
			update_option('dbem_data', $admin_data);
		}
		//add backwards compatability settings and warnings
		$admin_data = get_site_option('dbem_data');
		if( empty($admin_data['updates']) ) $admin_data['updates'] = array(); 
		$admin_data['updates']['timezone-backcompat'] = true;
		update_site_option('dbem_data', $admin_data);
		if( !is_multisite() || em_wp_is_super_admin() ){
			$message = __('Events Manager now supports multiple timezones for your events! Your events will initially match your blog timezone.','events-manager');
			if( is_multisite() ){
				$url = network_admin_url('admin.php?page=events-manager-options#general+admin-tools');
				$admin_tools_link = '<a href="'.$url.'">'.__('Network Admin').' &gt; '.__('Events Manager','events-manager').' &gt; '.__('Admin Tools','events-manager').'</a>';
				$options_link = '<a href="'.network_admin_url('admin.php?page=events-manager-update').'">'.__('Update Network','events-manager').'</a>';
				$message .= '</p><p>' . sprintf(__("Please update your network and when you're happy with the changes you can also finalize the migration by deleting unecessary data in the %s page.", 'events-manager'), $options_link);
				$message .= '</p><p>' . sprintf(__('You can also reset all events of a blog to a new timezone in %s', 'events-manager'), $admin_tools_link);
			}else{
				$options_link = get_admin_url(null, 'edit.php?post_type=event&page=events-manager-options#general+admin-tools');
				$options_link = '<a href="'.$options_link.'">'.__('Settings','events-manager').' &gt; '.__('General','events-manager').' &gt; '.__('Admin Tools','events-manager').'</a>';
				$message .= '</p><p>' . sprintf(__('You can reset your events to a new timezone and also complete the final migration step by deleting unecessary data in %s', 'events-manager'), $options_link);
			}
			$EM_Admin_Notice = new EM_Admin_Notice(array(
				'name' => 'date_time_migration',
				'who' => 'admin',
				'where' => 'all',
				'message' => $message
			));
			EM_Admin_Notices::add($EM_Admin_Notice, is_multisite());
		}
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') == 5.9 && is_multisite() && !EM_MS_GLOBAL && (is_network_admin() || is_main_site()) ){
		//warning just for users who upgraded to 5.9 on multisite without global tables enabled
		$message = 'Due to a bug in 5.9 when updating to new timezones in MultiSite installations, you may notice some of your events are missing from lists.<br><br>To fix this problem, visit %s choose your timezone, select %s and click %s to update all your blogs to the desired timezone.';
		$url = network_admin_url('admin.php?page=events-manager-options#general+admin-tools');
		$admin_tools_link = '<a href="'.$url.'">'.esc_html(__('Network Admin').' &gt; '.__('Events Manager','events-manager').' &gt; '.__('Admin Tools','events-manager')).'</a>';
		$message = sprintf($message, $admin_tools_link, '<em><strong>'.esc_html__('All Blogs', 'events-manager').'</strong></em>', '<em><strong>'.esc_html__('Reset Event Timezones','events-manager').'</strong></em>');
		$EM_Admin_Notice = new EM_Admin_Notice(array(
		'name' => 'date_time_migration_5.9_multisite',
		'who' => 'admin',
		'what' => 'warning',
		'where' => 'all',
		'message' => $message
		));
		EM_Admin_Notices::add($EM_Admin_Notice, is_multisite());
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.93 ){
		$message = __('Events Manager has introduced new privacy tools to help you comply with international laws such as the GDPR, <a href="%s">see our documentation</a> for more information.','events-manager');
		$message = sprintf( $message, 'https://wp-events-plugin.com/documentation/data-privacy-gdpr-compliance/?utm_source=plugin&utm_campaign=gdpr_update');
		$EM_Admin_Notice = new EM_Admin_Notice(array( 'name' => 'gdpr_update', 'who' => 'admin', 'where' => 'all', 'message' => $message ));
		EM_Admin_Notices::add($EM_Admin_Notice, is_multisite());
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.95 ){
		$message = esc_html__('Google has introduced new pricing for displaying maps on your site. If you have moderate traffic levels, this may likely affect you with surprise and unexpected costs!', 'events-manager');
		$message2 = esc_html__('Events Manager has implemented multiple ways to help prevent or reduce these costs drastically, please check our %s page for more information.', 'events-manager');
		$message2 = sprintf($message2, '<a href="https://wp-events-plugin.com/documentation/google-maps/api-usage/?utm_source=plugin&utm_source=medium=settings&utm_campaign=gmaps-update">'.esc_html__('documentation', 'events-manager') .'</a>');
		$EM_Admin_Notice = new EM_Admin_Notice(array( 'name' => 'gdpr_update', 'who' => 'admin', 'where' => 'all', 'message' => "<p>$message</p><p>$message2</p>" ));
		EM_Admin_Notices::add($EM_Admin_Notice, is_multisite());
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.9618 ){
		$multisite_cond = '';
		if( EM_MS_GLOBAL ){
			if( is_main_site() ){
				$multisite_cond = ' AND (blog_id='.absint(get_current_blog_id()).' OR blog_id=0)';
			}else{
				$multisite_cond = ' AND blog_id='.absint(get_current_blog_id());
			}
		}
		$wpdb->query( $wpdb->prepare('UPDATE '.EM_EVENTS_TABLE.' SET event_language=%s WHERE event_language IS NULL'.$multisite_cond, EM_ML::$wplang) );
		$wpdb->query( $wpdb->prepare('UPDATE '.EM_LOCATIONS_TABLE.' SET location_language=%s WHERE location_language IS NULL'.$multisite_cond, EM_ML::$wplang) );
		$host = get_option('dbem_smtp_host');
		//if port is supplied via the host address, give that precedence over the port setting
		if( preg_match('/^(tls|ssl):\/\//', $host, $host_port_matches) ){
			update_option('dbem_smtp_encryption', $host_port_matches[1]);
		}else{
			update_option('dbem_smtp_encryption', 0);
		}
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.975 ){
		update_option('dbem_location_types', array('location'=>1));
		$message = esc_html__('Events Manager has introduced location types, which can include online locations such as a URL or integrations with webinar platforms such as Zoom! Enable different location types in your settings page, for more information see our %s.', 'events-manager');
		$message = sprintf( $message, '<a href="http://wp-events-plugin.com/documentation/location-types/" target="_blank">'. esc_html__('documentation', 'events-manager')).'</a>';
		$EM_Admin_Notice = new EM_Admin_Notice(array( 'name' => 'location-types-update', 'who' => 'admin', 'where' => 'all', 'message' => "$message" ));
		EM_Admin_Notices::add($EM_Admin_Notice, is_multisite());
	}
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 5.9821 ){
		// recreate all event_parent records in post meta
		$sql = "DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_event_parent' AND post_id IN (SELECT post_id FROM ".EM_EVENTS_TABLE.")";
		$wpdb->query($sql);
		$sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) SELECT post_id, '_event_parent', event_parent FROM ".EM_EVENTS_TABLE." WHERE event_parent IS NOT NULL";
		if( EM_MS_GLOBAL ){
			// do just this blog, other blogs will update themselves when loaded
			if( is_main_site() ){
				$sql .= ' AND (blog_id='.absint(get_current_blog_id()).' OR blog_id=0)';
			}else{
				$sql .= ' AND blog_id='.absint(get_current_blog_id());
			}
		}
		$wpdb->query($sql);
	}
}

function em_set_mass_caps( $roles, $caps ){
	global $wp_roles;
	foreach( $roles as $user_role ){
		foreach($caps as $cap){
			$wp_roles->add_cap($user_role, $cap);
		}
	}
}

function em_set_capabilities(){
	//Get default roles
	global $wp_roles;
	if( get_option('dbem_version') == '' ){
		//Assign caps in groups, as we go down, permissions are "looser"
		$caps = array(
			/* Event Capabilities */
			'publish_events', 'delete_others_events', 'edit_others_events', 'manage_others_bookings',
			/* Recurring Event Capabilties */
			'publish_recurring_events', 'delete_others_recurring_events', 'edit_others_recurring_events',
			/* Location Capabilities */
			'publish_locations', 'delete_others_locations',	'delete_locations', 'edit_others_locations',
			/* Category Capabilities */
			'delete_event_categories', 'edit_event_categories'
		);
		em_set_mass_caps( array('administrator','editor'), $caps );

		//Add all the open caps
		$loose_caps = array(
			'manage_bookings', 'upload_event_images',
			/* Event Capabilities */
			'delete_events', 'edit_events', 'read_private_events',
			/* Recurring Event Capabilties */
			'delete_recurring_events', 'edit_recurring_events',
			/* Location Capabilities */
			'edit_locations', 'read_private_locations', 'read_others_locations',
		);
		em_set_mass_caps( array('administrator','editor','contributor','author'), $loose_caps);
		
		//subscribers can read private stuff, nothing else
		$wp_roles->add_cap('subscriber', 'read_private_locations');
		$wp_roles->add_cap('subscriber', 'read_private_events');
	}
	if( get_option('dbem_version')  && get_option('dbem_version') < 5 ){
		//Add new caps that are similar to old ones
		$conditional_caps = array(
			'publish_events' => 'publish_locations,publish_recurring_events',
			'edit_others_events' => 'edit_others_recurring_events',
			'delete_others_events' => 'delete_others_recurring_events',
			'edit_categories' => 'edit_event_categories,delete_event_categories',
			'edit_recurrences' => 'edit_recurring_events,delete_recurring_events',
			'edit_events' => 'upload_event_images'
		);
		$default_caps = array( 'read_private_events', 'read_private_locations' );
		foreach($conditional_caps as $cond_cap => $new_caps){
			foreach( $wp_roles->role_objects as $role_name => $role ){
				if($role->has_cap($cond_cap)){
					foreach(explode(',', $new_caps) as $new_cap){
						$role->add_cap($new_cap);
					}
				}
			}
		}
		em_set_mass_caps( array('administrator','editor','contributor','author','subscriber'), $default_caps);
	}
}

function em_create_events_page(){
	global $wpdb,$current_user;
	$event_page_id = get_option('dbem_events_page');
	if( empty($event_page_id) ){
		$post_data = array(
			'post_status' => 'publish',
			'post_type' => 'page',
			'ping_status' => get_option('default_ping_status'),
			'post_content' => 'CONTENTS',
			'post_excerpt' => 'CONTENTS',
			'post_title' => __('Events','events-manager')
		);
		$post_id = wp_insert_post($post_data, false);
	   	if( $post_id > 0 ){
	   		update_option('dbem_events_page', $post_id);
	   		//Now Locations Page
	   		$post_data = array(
				'post_status' => 'publish',
	   			'post_parent' => $post_id,
				'post_type' => 'page',
				'ping_status' => get_option('default_ping_status'),
				'post_content' => 'CONTENTS',
				'post_excerpt' => '',
				'post_title' => __('Locations','events-manager')
			);
			$loc_id = wp_insert_post($post_data, false);
	   		update_option('dbem_locations_page', $loc_id);
	   		//Now Categories Page
	   		$post_data = array(
				'post_status' => 'publish',
	   			'post_parent' => $post_id,
				'post_type' => 'page',
				'ping_status' => get_option('default_ping_status'),
				'post_content' => 'CONTENTS',
				'post_excerpt' => '',
				'post_title' => __('Categories','events-manager')
			);
			$cat_id = wp_insert_post($post_data, false);
	   		update_option('dbem_categories_page', $cat_id);
	   		//Now Tags Page
	   		$post_data = array(
				'post_status' => 'publish',
	   			'post_parent' => $post_id,
				'post_type' => 'page',
				'ping_status' => get_option('default_ping_status'),
				'post_content' => 'CONTENTS',
				'post_excerpt' => '',
				'post_title' => __('Tags','events-manager')
			);
			$tag_id = wp_insert_post($post_data, false);
	   		update_option('dbem_tags_page', $tag_id);
		   	//Now Bookings Page
		   	$post_data = array(
				'post_status' => 'publish',
		   		'post_parent' => $post_id,
				'post_type' => 'page',
				'ping_status' => get_option('default_ping_status'),
				'post_content' => 'CONTENTS',
				'post_excerpt' => '',
				'post_title' => __('My Bookings','events-manager'),
		   		'post_slug' => 'my-bookings'
			);
			$bookings_post_id = wp_insert_post($post_data, false);
	   		update_option('dbem_my_bookings_page', $bookings_post_id);
	   	}
	}
}

// migrate old dbem tables to new em ones
function em_migrate_v4(){
	global $wpdb, $blog_id;
	//before making any moves, let's create new pages for locations na dcats
	$event_page_id = get_option('dbem_events_page');
	if( !empty($event_page_id) ){
		if( !get_option('dbem_locations_page') ){
		   	$post_data = array(
				'post_status' => 'publish',
		   		'post_parent' => $event_page_id,
				'post_type' => 'page',
				'ping_status' => get_option('default_ping_status'),
				'post_content' => 'CONTENTS',
				'post_excerpt' => '',
				'post_title' => get_option('dbem_locations_page_title', __('Locations','events-manager')),
		   		'post_slug' => get_option('dbem_cp_locations_slug')
			);
			$loc_post_id = wp_insert_post($post_data, false);
	   		update_option('dbem_locations_page', $loc_post_id);
		}
		if( !get_option('dbem_categories_page') ){
		   	//Now Categories Page
		   	$post_data = array(
				'post_status' => 'publish',
		   		'post_parent' => $event_page_id,
				'post_type' => 'page',
				'ping_status' => get_option('default_ping_status'),
				'post_content' => 'CONTENTS',
				'post_excerpt' => '',
				'post_title' => get_option('dbem_categories_page_title', __('Categories','events-manager')),
		   		'post_slug' => get_option('dbem_cp_categories_slug')
			);
			$cat_post_id = wp_insert_post($post_data, false);
	   		update_option('dbem_categories_page', $cat_post_id);
		}
		if( !get_option('dbem_my_bookings_page') ){
		   	//Now Categories Page
		   	$post_data = array(
				'post_status' => 'publish',
		   		'post_parent' => $event_page_id,
				'post_type' => 'page',
				'ping_status' => get_option('default_ping_status'),
				'post_content' => 'CONTENTS',
				'post_excerpt' => '',
				'post_title' => __('My Bookings','events-manager'),
		   		'post_slug' => 'my-bookings'
			);
			$bookings_post_id = wp_insert_post($post_data, false);
	   		update_option('dbem_my_bookings_page', $bookings_post_id);
		}
	}
	//set shared vars
	$limit = 100;
	//-- LOCATIONS --
	if( !is_multisite() || (EM_MS_GLOBAL && is_main_site($blog_id)) || (!EM_MS_GLOBAL && is_multisite()) ){ //old locations will always belong to the main blog when migrated, since we didn't have previous blog ids
		if( is_multisite() ){
			$this_blog = $blog_id;
		}else{
			$this_blog = 0;
		}
		//set location statuses and blog id for all locations
		$wpdb->query('UPDATE '.EM_LOCATIONS_TABLE.' SET location_status=1, blog_id='.$this_blog.' WHERE blog_id IS NULL');
		//first create location posts
		$sql = 'SELECT * FROM '.EM_LOCATIONS_TABLE.' WHERE post_id = 0 LIMIT '.$limit;
		$locations = $wpdb->get_results($sql, ARRAY_A);
		//get location image directory
		$dir = (EM_IMAGE_DS == '/') ? 'locations/':'';
		while( count($locations) > 0 ){
			em_migrate_locations($locations);
			$locations = $wpdb->get_results($sql, ARRAY_A); //get more locations and continue looping
		}
	}
	//-- EVENTS & Recurrences --
	if( is_multisite() ){
		if(EM_MS_GLOBAL && is_main_site()){
			$sql = "SELECT * FROM ".EM_EVENTS_TABLE." WHERE post_id=0 AND (blog_id=$blog_id OR blog_id=0 OR blog_id IS NULL) LIMIT $limit";
		}elseif(EM_MS_GLOBAL){
			$sql = "SELECT * FROM ".EM_EVENTS_TABLE." WHERE post_id=0 AND blog_id=$blog_id LIMIT $limit";
		}else{
			$sql = "SELECT * FROM ".EM_EVENTS_TABLE." WHERE post_id=0 LIMIT $limit";
		}
	}else{
		$sql = "SELECT * FROM ".EM_EVENTS_TABLE." WHERE post_id=0 LIMIT $limit";
	}
	//create posts
	$events = $wpdb->get_results($sql, ARRAY_A);
	while( count($events) > 0 ){
		em_migrate_events($events);
		$events = $wpdb->get_results($sql, ARRAY_A); //get more locations and continue looping
	}
	//-- CATEGORIES --
	//Create the terms according to category table, use the category owner for the term ids to store this
	$categories = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.'em_categories', ARRAY_A); //taking a wild-hope guess that there aren't too many categories on one site/blog
	foreach( $categories as $category ){
		//get all events with this category before resetting ids
		$sql = "SELECT post_id FROM ".EM_EVENTS_TABLE.", ".EM_META_TABLE." WHERE event_id=object_id AND meta_key='event-category' AND meta_value='{$category['category_id']}'";
		$category_posts = $wpdb->get_col($sql);
		//get or create new term
		$term = get_term_by('slug', $category['category_slug'], EM_TAXONOMY_CATEGORY);
		if( $term === false ){
			//term not created yet, let's create it
			$term_array = wp_insert_term($category['category_name'], EM_TAXONOMY_CATEGORY, array(
				'description' => $category['category_description'],
				'slug' => $category['category_slug']
			));
			if( is_array($term_array) ){
				//update category bg-color if used before
				$wpdb->query('UPDATE '.EM_META_TABLE." SET object_id='{$term_array['term_id']}' WHERE meta_key='category-bgcolor' AND object_id={$category['category_id']}");
				$wpdb->query('UPDATE '.EM_META_TABLE." SET meta_value='{$term_array['term_id']}' WHERE meta_key='event-category' AND meta_value={$category['category_id']}");
				// and assign category image url if file exists
				$dir = (EM_IMAGE_DS == '/') ? 'categories/':'';
			  	foreach(array(1 => 'gif', 2 => 'jpg', 3 => 'png') as $mime_type) {
					$file_name = $dir."category-{$category['category_id']}.$mime_type";
					if( file_exists( EM_IMAGE_UPLOAD_DIR.$file_name) ) {
			  			$wpdb->insert(EM_META_TABLE, array('object_id'=>$term_array['term_id'],'meta_key'=>'category-image','meta_value'=>EM_IMAGE_UPLOAD_URI.$file_name));
			  			break;
					}
				}
			}
		}
		//set event terms in wp tables
		foreach($category_posts as $post_id){
			wp_set_object_terms($post_id, $category['category_slug'], EM_TAXONOMY_CATEGORY, true);
		}
	}
	update_option('dbem_migrate_images_nag', 1);
	update_option('dbem_migrate_images', 1);
}

function em_migrate_events($events){
	global $wpdb;
	//disable actions
	remove_action('save_post',array('EM_Event_Recurring_Post_Admin','save_post'));
	remove_action('save_post',array('EM_Event_Post_Admin','save_post'),10,1);
	$post_fields = array('event_slug','event_owner','event_name','event_attributes','post_id','post_content');
	$event_metas = array(); //restart metas
	foreach($events as $event){
		//new post info
		$post_array = array();
		$post_array['post_type'] = $event['recurrence'] == 1 ? 'event-recurring' : EM_POST_TYPE_EVENT;
		$post_array['post_title'] = $event['event_name'];
		$post_array['post_content'] = $event['post_content'];
		$post_array['post_status'] = (!isset($event['event_status']) || $event['event_status'])  ? 'publish':'pending';
		$post_array['post_author'] = $event['event_owner'];
		$post_array['post_slug'] = $event['event_slug'];
		$event['start_ts'] = strtotime($event['event_start_date']);
		$event['end_ts'] = strtotime($event['event_end_date']);
		//Save post, register post id in index
		$post_id = wp_insert_post($post_array);
		if( is_wp_error($post_id) || $post_id == 0 ){ $post_id = 999999999999999999; }//hopefully nobody blogs that much... if you do, and you're reading this, maybe you should be hiring me for the upgrade ;) }
		if( $post_id != 999999999999999999 ){
			$wpdb->query('UPDATE '.EM_EVENTS_TABLE." SET post_id='$post_id' WHERE event_id='{$event['event_id']}'");
			//meta
	 		foreach($event as $meta_key => $meta_val){
	 			if( !in_array($meta_key, $post_fields) && $meta_key != 'event_attributes' ){
		 			$event_metas[] = $wpdb->prepare("(%d, '%s', '%s')", array($post_id, '_'.$meta_key, $meta_val));
	 			}elseif($meta_key == 'event_attributes'){
	 				$event_attributes = unserialize($meta_val); //from em table it's serialized
					if( is_array($event_attributes) ){
		 				foreach($event_attributes as $att_key => $att_val){
			 				$event_metas[] = $wpdb->prepare("(%d, '%s', '%s')", array($post_id, $att_key, $att_val));
		 				}
					}
	 			}
	 		}
		}
	}
 	//insert the metas in one go, faster than one by one
 	if( count($event_metas) > 0 ){
	 	$result = $wpdb->query("INSERT INTO ".$wpdb->postmeta." (post_id,meta_key,meta_value) VALUES ".implode(',',$event_metas));
 	}
}

function em_migrate_locations($locations){
	global $wpdb;
	//disable actions
	remove_action('save_post',array('EM_Location_Post_Admin','save_post'));
	$location_metas = array(); //restart metas
	$post_fields = array('post_id','location_slug','location_name','post_content','location_owner');
	foreach($locations as $location){
		//new post info
		$post_array = array();
		$post_array['post_type'] = EM_POST_TYPE_LOCATION;
		$post_array['post_title'] = $location['location_name'];
		$post_array['post_content'] = $location['post_content'];
		$post_array['post_status'] = 'publish';
		$post_array['post_author'] = $location['location_owner'];
		//Save post, register post id in index
		$post_id = wp_insert_post($post_array);
		if( is_wp_error($post_id) || $post_id == 0 ){ $post_id = 999999999999999999; }//hopefully nobody blogs that much... if you do, and you're reading this, maybe you should be hiring me for the upgrade ;) }
		$wpdb->query('UPDATE '.EM_LOCATIONS_TABLE." SET post_id='$post_id' WHERE location_id='{$location['location_id']}'");
		//meta
 		foreach($location as $meta_key => $meta_val){
 			if( !in_array($meta_key, $post_fields) ){
	 			$location_metas[] = $wpdb->prepare("(%d, '%s', '%s')", array($post_id, '_'.$meta_key, $meta_val));
 			}
 		}
	}
 	//insert the metas in one go, faster than one by one
 	if( count($location_metas) > 0 ){
	 	$result = $wpdb->query("INSERT INTO ".$wpdb->postmeta." (post_id,meta_key,meta_value) VALUES ".implode(',',$location_metas));
 	}
}

function em_migrate_uploads(){
	//build array of images
	global $wpdb;
	$mime_types = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');

	$pattern = (EM_IMAGE_DS == '/') ? EM_IMAGE_UPLOAD_DIR.'*/*':EM_IMAGE_UPLOAD_DIR.'*';
	$files = glob($pattern);
	$file_array = array();
	foreach($files as $file){
		$matches = array();
		if( preg_match('/\/(events|locations\/)?(event|location)-([0-9]+).([a-zA-Z]{3})/', $file, $matches) ){
			$file_array[$matches[2]][$matches[3]] = array(
				'file' => $file,
				'url' => EM_IMAGE_UPLOAD_URI.$matches[1].$matches[2].'-'.$matches[3].'.'.$matches[4],
				'type' => 'image/'.$matches[4],
			);
		}
	}
	$result = array('success'=>0, 'fail'=>0);
	if( count($file_array) > 0 ){
		foreach($file_array as $type => $file_type){
			foreach($file_type as $id => $attachment){
				if($type == 'event'){
					$post = em_get_event($id);
				}elseif($type == 'location'){
					$post = em_get_location($id);
				}
				if ( !empty($post->ID) ){
					$attachment_data = array(
						'post_mime_type' => $attachment['type'],
						'post_title' => $post->post_title,
						'post_content' => '',
						'post_status' => 'inherit'
					);
					$attachment_id = wp_insert_attachment( $attachment_data, $attachment['file'], $post->ID );
					$attachment_metadata = wp_generate_attachment_metadata( $attachment_id, $attachment['file'] );
					wp_update_attachment_metadata( $attachment_id,  $attachment_metadata );
					//delete the old attachment
					update_post_meta($post->post_id, '_thumbnail_id', $attachment_id);
					//is it recurring? If so add attachment to recurrences
					if( $type == 'event' && $post->is_recurring() ){
						$results = $wpdb->get_col('SELECT post_id FROM '.EM_EVENTS_TABLE.' WHERE recurrence_id='.$post->event_id);
						foreach ($results as $post_id){
							update_post_meta($post_id, '_thumbnail_id', $attachment_id);
						}
					}
					$result['success']++;
				}
			}
		}
	}
	delete_option('dbem_migrate_images_nag');
	delete_option('dbem_migrate_images');
	return $result;
}

function em_migrate_datetime_timezones( $reset_new_fields = true, $migrate_date_fields = true, $timezone = false ){
	global $wpdb;
	//Table names
	$db = EM_MS_GLOBAL ? $wpdb->base_prefix : $wpdb->prefix;
	//create AND and WHERE conditions for blog IDs if we're in Multisite Glboal Mode
	$blog_id_where = $blog_id_and = '';
	if( EM_MS_GLOBAL ){
		if( is_main_site() ){
			$blog_id_cond = $wpdb->prepare('(blog_id = %d OR blog_id IS NULL OR blog_id = 0)', get_current_blog_id());
		}else{
			$blog_id_cond = $wpdb->prepare('blog_id = %d', get_current_blog_id());
		}
		$blog_id_where = ' WHERE '.$blog_id_cond;
		$blog_id_and = ' AND '.$blog_id_cond;
	}
	//reset all the data for these purposes
	if( $reset_new_fields || $migrate_date_fields ) $wpdb->query('UPDATE '. $db.'em_events' .' SET event_start = NULL, event_end = NULL, event_timezone = NULL'.$blog_id_where);
	if( !$migrate_date_fields ) return true;
	
	//start migration of old date formats to new datetime formats in local and UTC mode along with a declared timezone
	$migration_results = $migration_meta_results = $migration_errors = array();
	//firstly, we do a query for all-day events and reset the times, so that UTC times are correct relative to the local time
	$migration_result = $wpdb->query('UPDATE '. $db.'em_events'." SET event_start_time = '00:00:00', event_end_time = '23:59:59' WHERE event_all_day = 1".$blog_id_and);
	if( $migration_result === false ) $migration_errors[] = array('Local datetime allday event times modification errors', $wpdb->last_error);
	
	//migration procedure depends on whether we have an actual timezone or just a manual offset of hours in the WP settings page
	if( empty($timezone) ){
		$timezone = get_option('timezone_string');
		if( empty($timezone) ){ 
			$timezone = get_option('gmt_offset');
			$timezone = preg_match('/[+\-]/', $timezone) ? 'UTC'.$timezone : 'UTC+'.$timezone;
		}
	}
	if( !preg_match('/^UTC/', $timezone) ){
		//we'll get the minimum start/end dates in our events, and get the transitions for this range
		$transitions = em_migrate_get_tz_transitions($timezone, $blog_id_where);
		//now, build the SQL statements with the transitions
		$query_data = array();
		$where_start = $where_end = array();
		//go through each transition and add it to the right offset array key
		foreach( $transitions as $t ){
			//format start/end transitions for mysql DATETIME format IN CORRECT TIMEZONE
			$start = $t['start'] ? date('Y-m-d H:i:s', $t['start'] + $t['offset']) : false;
			$end = $t['end'] ? date('Y-m-d H:i:s', $t['end'] + $t['offset']) : false;
			//set up SQL statement for offset if it doesn't exist, but without the WHERE clause, which we'll add later
			if( empty($query_data[$t['offset']]) ){
				$query_data[$t['offset']] = array(
						'start' => array(
								'sql' => $wpdb->prepare('UPDATE '. $db.'em_events'. ' SET event_start = DATE_SUB(TIMESTAMP(event_start_date,event_start_time), INTERVAL %d SECOND)', $t['offset']),
								'where' => array()
						),
						'end' => array(
								'sql' => $wpdb->prepare('UPDATE '. $db.'em_events'. ' SET event_end = DATE_SUB(TIMESTAMP(event_end_date, event_end_time), INTERVAL %d SECOND)', $t['offset']),
								'where' => array()
						)
				);
			}
			//create array of conditions, which we'll join into single statements for each unique offset amount
			if( $start && $end ){
				$query_data[$t['offset']]['start']['where'][] = $wpdb->prepare("(TIMESTAMP(event_start_date,event_start_time) BETWEEN %s AND %s)", $start, $end);
				$query_data[$t['offset']]['end']['where'][] = $wpdb->prepare("(TIMESTAMP(event_end_date, event_end_time) BETWEEN %s AND %s)", $start, $end);
			}elseif( $start ){
				$query_data[$t['offset']]['start']['where'][] = $wpdb->prepare("(TIMESTAMP(event_start_date,event_start_time) > %s)", $start);
				$query_data[$t['offset']]['end']['where'][] = $wpdb->prepare("(TIMESTAMP(event_end_date, event_end_time) > %s)", $start);
			}elseif( $end ){
				$query_data[$t['offset']]['start']['where'][] = $wpdb->prepare("(TIMESTAMP(event_start_date,event_start_time) < %s)", $end);
				$query_data[$t['offset']]['end']['where'][] = $wpdb->prepare("(TIMESTAMP(event_end_date, event_end_time) < %s)", $end);
			}
		}
		//glue the where clauses together with SQLs and create the minimum required statements to run this update
		$sql_array = array();
		foreach( $query_data as $offset => $statements ){
			$migration_result = $wpdb->query($statements['start']['sql'] .' WHERE event_start IS NULL AND ('. implode(' OR ', $statements['start']['where']).')'.$blog_id_and);
			if( $migration_result === false ) $migration_errors[] = array('Event start UTC transition',$wpdb->last_error);
			$migration_result = $wpdb->query($statements['end']['sql'] ." WHERE event_end IS NULL AND (". implode(' OR ', $statements['end']['where']).')'.$blog_id_and);
			if( $migration_result === false ) $migration_errors[] = array('Event end UTC transation', $wpdb->last_error);
		}
	}else{
		//This gets very easy... just do a single query that copies over all the times to right columns with relevant offset
		$EM_DateTimeZone = EM_DateTimeZone::create($timezone);
		$offset = $timezone == 'UTC' ? 0 : $EM_DateTimeZone->manual_offset / MINUTE_IN_SECONDS;
		$timezone = $EM_DateTimeZone->getName();
		$migration_result = $wpdb->query($wpdb->prepare('UPDATE '. $db.'em_events'. ' SET event_start = DATE_SUB(TIMESTAMP(event_start_date,event_start_time), INTERVAL %d MINUTE), event_end = DATE_SUB(TIMESTAMP(event_end_date, event_end_time), INTERVAL %d MINUTE) WHERE event_end IS NULL '.$blog_id_and, $offset, $offset));
		if( $migration_result === false ) $migration_errors[] = array('Event start/end UTC offset', $wpdb->last_error);
	}
	
	//set the timezone (on initial migration all events have same timezone of blog)
	$migration_result = $wpdb->query($wpdb->prepare('UPDATE '. $db.'em_events' .' SET event_timezone = %s WHERE event_timezone IS NULL'.$blog_id_and, $timezone));
	if( $migration_result === false ) $migration_errors[] = array('Event timezone setting', $wpdb->last_error);
	
	//reave meta data - at this point once we've copied over all of the dates, so we do 5 queries to postmeta, one for each field we've created above start/end times in local/utc and timezone
	if( empty($migration_errors) ){
		//delete all previously added fields, in case they were added before
		$sql = 'DELETE FROM '.$wpdb->postmeta." WHERE meta_key IN ('_event_start','_event_end','_event_timezone', '_event_start_local', '_event_end_local') AND post_id IN (SELECT ID FROM ".$wpdb->posts." WHERE post_type='".EM_POST_TYPE_EVENT."' OR post_type='event-recurring')";
		$migration_result = $wpdb->query($sql);
		if( $migration_result === false ) $migration_errors[] = array('Previous meta deletion', $wpdb->last_error);
		foreach( array('event_start', 'event_end', 'event_timezone', 'start', 'end') as $field ){
			if( $field == 'start' || $field == 'end' ){
				//create a timestamp combining two given fields, which we'll now use 
				$sql = 'INSERT INTO '.$wpdb->postmeta." (post_id, meta_key, meta_value) SELECT post_id, '_event_{$field}_local', TIMESTAMP(event_{$field}_date, event_{$field}_time) FROM ".$db . 'em_events'. $blog_id_where;
				$field = "event_".$field."_local";
			}else{
				$sql = 'INSERT INTO '.$wpdb->postmeta." (post_id, meta_key, meta_value) SELECT post_id, '_{$field}', {$field} FROM ". $db.'em_events'. $blog_id_where;
			}
			$migration_result = $wpdb->query($sql);
			if( $migration_result === false ) $migration_errors[] = array('Adding new meta data key <em>_'.$field.'</em>', $wpdb->last_error);
		}
	}
	
	//return the result of this migration, either true for no errors, or a string of errors.
	if( !empty($migration_errors) ){
		$string = __('There was an error whilst migrating your times to our new timezone-aware formats. Below is a list of errors:', 'events-manager');
		$string .= '<ul>';
		foreach( $migration_errors as $err ){
			$string .= '<li><strong>'. $err[0] .': </strong>'. $err[1] .'</li>';
		}
		$string .= '</ul>';
		return $string;
	}
	return true;
}

function em_migrate_get_tz_transitions( $timezone, $blog_id_where = '' ){
	global $wpdb;
	$db = EM_MS_GLOBAL ? $wpdb->base_prefix : $wpdb->prefix;
	$minmax_dates = $wpdb->get_row('SELECT MIN(event_start_date) AS mindate, MAX(event_end_date) AS maxdate FROM '. $db.'em_events' .$blog_id_where);
	$DTZ = new EM_DateTimeZone( $timezone );
	$start = strtotime($minmax_dates->mindate, current_time('timestamp')) - 60*60*24;
	$end = strtotime($minmax_dates->maxdate, current_time('timestamp')) + 60*60*24; //we add a day just to get the most comprehensive range possible
	$DTZ_Transitions = $DTZ->getTransitions($start, $end);
	//get first and next transitions to create a range, if there's only one transition we create a fake transition
	$transitions = array();
	if( count($DTZ_Transitions) == 1 ){
		$current_transition = current($DTZ_Transitions);
		$dst_offset = $current_transition['isdst'] ? 60*60 : 0;
		$transitions[] = array(
			'start' => $start,
			'end' => false,
			'offset' => $current_transition['offset']
		);
	}else{
		do{
			$current_transition = current($DTZ_Transitions);
			$transition_key = key($DTZ_Transitions);
			$next_transition = next($DTZ_Transitions);
			if( $current_transition['ts'] < $start ) continue;
			$dst_offset = $current_transition['isdst'] ? 60*60 : 0;
			if( $transition_key == 0 ){
				//add the final transition and break this loop
				$transitions[] = array(
				'start' => false,
				'end' => $next_transition['ts'] - 1 - $dst_offset,
				'offset' => $current_transition['offset']
				);
			}else{
				if( empty($next_transition) ){
					//add the final transition and break this loop
					$transitions[] = array(
					'start' => $current_transition['ts'] - $dst_offset,
					'end' => false,
					'offset' => $current_transition['offset']
					);
					break;
				}else{
					$transitions[] = array(
							'start' => $current_transition['ts'] - $dst_offset,
							'end' => $next_transition['ts'] - 1 - $dst_offset,
							'offset' => $current_transition['offset']
					);
				}
			}
			if( $current_transition['ts'] > $end ) break;
		} while( $next_transition !== false );
	}
	return $transitions;
}
?>