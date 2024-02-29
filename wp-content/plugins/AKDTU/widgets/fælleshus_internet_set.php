<?php

/**
 * @file Widget to display the current settings to the router in the common house, and forcefully update these
 */

function fælleshus_internet_set_dashboard_widget() {
	global $wpdb;

	$now = (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s');

	$event_ids = $wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_start <= "' . $now . '" AND event_end >= "' . $now . '" AND event_status = 1');

	if (count($event_ids) > 0) {
		$event_owners = array_map(function ($event_id) {
			return (is_apartment_from_id(em_get_event($event_id, 'event_id')->owner) ? 'lejlighed ' . padded_apartment_number_from_id(em_get_event($event_id, 'event_id')->owner) : 'Bestyrelsen');
		}, $event_ids);

		$rented = true;
	} else {
		$event_owners = array('Ingen');
		$rented = false;
	}

	$router_settings = get_router_settings(); ?>
		<table width="100%" class="widefat">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead>
				<tr>
					<th colspan="2" style="font-weight:bold;">
						Bemærk: Internetforbindelsen i fælleshuset opdateres automatisk klokken 12:01 i starten og slutningen af lejeperioder. Det du sætter adgangskoden til herunder vil derfor i fremtiden blive overskrevet.
					</th>
				</tr>
			</thead>
			<tbody>
				<form action="" method="post">
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
							<input type="text" name="new_password" placeholder="Ny adgangskode" />
						</td>
					</tr>
					<tr class="alternate">
						<td style="vertical-align:middle">
							Fælleshus status
						</td>
						<td style="vertical-align:middle">
							<b><?php echo ($rented ? "Lejet af " . implode(", ", $event_owners) : "Ledigt"); ?></b>
						</td>
					</tr>
					<tr>
						<td style="vertical-align:middle"></td>
						<input type="hidden" name="action" value="fælleshus_internet_force_set" />
						<td style="vertical-align:middle"><input type="submit" class="button-secondary" value="Sæt adgangskode" /></td>
					</tr>
				</form>
			</tbody>
		</table>
<?php } ?>