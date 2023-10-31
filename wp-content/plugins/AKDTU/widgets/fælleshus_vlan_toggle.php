<?php

function fælleshus_vlan_dashboard_widget() {
	global $wpdb;

	try {
		$now = (new DateTime('now', new DateTimeZone('Europe/Copenhagen')))->format('Y-m-d H:i:s');

		$event_ids = $wpdb->get_col('SELECT event_id FROM ' . EM_EVENTS_TABLE . ' WHERE event_start <= "' . $now . '" AND event_end >= "' . $now . '" AND event_status = 1');

		if (count($event_ids) > 0) {
			$event_owners = array_map(function ($event_id) {
				return (is_apartment_from_id(em_get_event($event_id, 'event_id')->owner) ? 'lejlighed ' . apartment_number_from_id(em_get_event($event_id, 'event_id')->owner) : 'Bestyrelsen');
			}, $event_ids);

			$rented = true;
		} else {
			$event_owners = array('Ingen');
			$rented = false;
		}

		$state = get_fælleshus_vlan()->state; ?>
		<table width="100%" class="widefat">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<thead>
				<tr>
					<th colspan="3" style="font-weight:bold;text-align:center;">
						Bemærk: Dette er en test, og styrer i øjeblikket et ubrugt VLAN. Denne widget afventer at internettet i fælleshuset bliver sat ordentligt op, og bliver efterfølgende ændret.
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="alternate">
					<td style="vertical-align:middle">
						Internet forbindelse:
					</td>
					<td style="vertical-align:middle">
						<?php echo ($state ? "Tændt" : "Slukket"); ?>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:middle">
						Fælleshus status:
					</td>
					<td style="vertical-align:middle">
						<?php echo ($rented ? "Lejet af " . implode(", ", $event_owners) : "Ledigt"); ?>
					</td>
				</tr>
				<tr class="alternate">
					<td></td>
					<td style="vertical-align:middle">
						<?php if (!$state) : ?>
							<form action="" method="post" style="display:inline-block">
								<input type="hidden" name="action" value="fælleshus_vlan_tænd" />
								<input type="submit" class="button-secondary" value="Tænd" />
							</form>
						<?php else : ?>
							<form action="" method="post" style="display:inline-block">
								<input type="hidden" name="action" value="fælleshus_vlan_sluk" />
								<input type="submit" class="button-secondary" value="Sluk" />
							</form>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>
<?php
	} catch (Exception $e) {
		echo "Cronjob 'Opdater fælleshus VLAN' fejlet.\nFejlinfo: " . $e->getMessage();
	}
}

?>