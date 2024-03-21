<?php

/**
 * @file Widget to display the current settings to the router in the common house, and forcefully update these
 */

function fælleshus_internet_dashboard_widget() {
	global $wpdb;

	$now = (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s');

	$events = $wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_start <= "' . $now . '" AND event_end >= "' . $now . '" AND event_status = 1');

	if (count($events) > 0) {
		$events = array_map(function ($event_id) {
			return em_get_event($event_id, 'event_id');
		}, $events);
		$events = array_filter($events, function ($event) {
			return count(pll_get_post_translations(em_get_event($event->post_id))) == 1 || pll_get_post_language($event->post_id, "slug") == "da";
		});

		$event_owners = array_map(function ($event) {
			return (is_apartment_from_id($event->owner) ? 'lejlighed ' . padded_apartment_number_from_id($event->owner) : 'bestyrelsen');
		}, $events);
		
		$last = array_pop($event_owners);
    	$event_owners = implode(', ', $event_owners) . ' og ' . $last;

		$rented = true;
	} else {
		$event_owners = 'Ingen';
		$rented = false;
	}

	$router_settings = get_router_settings();
	$password_struct = generate_password_info(); ?>
		<table width="100%" class="widefat">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead>
				<tr>
					<th colspan="2" style="font-weight:bold;">
					Bemærk: Internetforbindelsen i fælleshuset opdateres automatisk klokken 12:01 i starten og slutningen af lejeperioder. Nedenstående bør være de korrekte informationer.<br>
					Hvis ikke, så kan informationerne gennemtvinges ved at trykke på knappen. Dette sender også en mail med login-informationer til lejeren!
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="alternate">
					<td style="vertical-align:middle">
						SSID
					</td>
					<td style="vertical-align:middle">
						<b><?php echo $router_settings["ssid"]; ?></b>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:middle">
						Adgangskode
					</td>
					<td style="vertical-align:middle">
						<b><?php echo $password_struct["password"]; ?></b>
					</td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle">
						Fælleshus status
					</td>
					<td style="vertical-align:middle">
						<b><?php echo ($rented ? "Lejet af " . $event_owners : "Ledigt"); ?></b>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:middle">
						Mail sendes i dag
					</td>
					<td style="vertical-align:middle">
						<b><?php echo ($password_struct["should_be_changed"] ? "Ja" : "Nej"); ?></b>
					</td>
				</tr>
				<tr class="alternate">
					<td style="vertical-align:middle"></td>
					<form action="" method="post">
						<input type="hidden" name="action" value="fælleshus_internet_force_update" />
						<td style="vertical-align:middle"><input type="submit" class="button-secondary" value="Tving opdatering" /></td>
					</form>
				</tr>
			</tbody>
		</table>
<?php } ?>