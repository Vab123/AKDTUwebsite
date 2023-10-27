<?php

function send_opdater_fælleshus_vlan($debug = false) {
	global $wpdb;
	require_once WP_PLUGIN_DIR . '/AKDTU/definitions.php';

	if (FÆLLESHUS_VLAN_TO != '' || $debug) {
		require_once WP_PLUGIN_DIR . '/AKDTU/functions/vlan.php';
		require_once WP_PLUGIN_DIR . '/AKDTU/functions/send_mail.php';
		require_once WP_PLUGIN_DIR . '/AKDTU/functions/users.php';


		try {
			$current_state = get_fælleshus_vlan();
		} catch (Exception $e) {
			if ($debug) {
				echo "Cronjob 'Opdater fælleshus VLAN' fejlet.\nFejlinfo: " . $e->getMessage();
				return;
			} else {
				wp_mail("netgruppen@akdtu.dk", "Hjemmeside: Cronjob 'Opdater fælleshus VLAN' fejlet.", "Fejlinfo: " . $e->getMessage());
			}
			return;
		}
		$current_state = $current_state->state;

		$now = (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s');

		$event_ids = $wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_start <= "' . $now . '" AND event_end >= "' . $now . '" AND event_status = 1');

		if (count($event_ids) > 0) {
			$event_owners = array_map(function ($event_id) {
				return get_user_by('id', em_get_event($event_id, 'event_id')->owner);
			}, $event_ids);
			$event_owners = array_map(function ($event_owner) {
				return (is_apartment_from_username($event_owner->user_login) ? 'lejlighed ' . apartment_number_from_username($event_owner->user_login) : 'Bestyrelsen');
			}, $event_owners);
		} else {
			$event_owners = array('Ingen');
		}

		$desired_state = count($event_ids) > 0;

		if (!$debug && $desired_state != $current_state) {
			$new_state = set_fælleshus_vlan($desired_state)->state;
		} else {
			$new_state = $desired_state;
		}

		$subject_replaces = array();

		$content_replaces = array(
			'#OLDSTATUS' => ($current_state ? "Tændt" : "Slukket"),
			'#DESIREDSTATUS' => ($desired_state ? "Tændt" : "Slukket"),
			'#NEWSTATUS' => ($new_state ? "Tændt" : "Slukket"),
			'#CURRENTRENTER' => join(', ', $event_owners),
			'#CURRENTRENTALSTATUS' => (count($event_ids) > 0 ? 'lejet af ' . join(', ', $event_owners) : 'ledigt')
		);

		if ($debug || $desired_state != $current_state) {
			send_AKDTU_email($debug, $subject_replaces, $content_replaces, 'FÆLLESHUS_VLAN');
		}
	}
}
