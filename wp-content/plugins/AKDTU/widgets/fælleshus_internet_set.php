<?php

/**
 * @file Widget to display the current settings to the router in the common house, and forcefully update these
 */

function fælleshus_internet_set_dashboard_widget() {
	$rental_info = get_current_common_house_renters();

	$event_owners = $rental_info['renters'];
	$rented = $rental_info['rented'];

	$router_settings = get_router_settings(); ?>
		<table width="100%" class="widefat">
			<colgroup>
				<col span="1" style="width: 60%">
				<col span="1" style="width: 40%">
			</colgroup>
			<thead>
				<tr>
					<th colspan="2" style="font-weight:bold;">
						Bemærk: Internetforbindelsen i fælleshuset opdateres automatisk hver time. Det du sætter adgangskoden til herunder vil derfor i fremtiden blive overskrevet.
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
							Nuværende adgangskode
						</td>
						<td style="vertical-align:middle">
							<b><?php echo get_fælleshus_password()['wl0_wpa_psk']; ?></b>
						</td>
					</tr>
					<tr class="alternate">
						<td style="vertical-align:middle">
							Ny adgangskode
						</td>
						<td style="vertical-align:middle">
							<input type="text" name="new_password" placeholder="Ny adgangskode" />
						</td>
					</tr>
					<tr>
						<td style="vertical-align:middle">
							Fælleshus status
						</td>
						<td style="vertical-align:middle">
							<b><?php echo ($rented ? "Lejet af " . $event_owners : "Ledigt"); ?></b>
						</td>
					</tr>
					<tr class="alternate">
						<td style="vertical-align:middle"></td>
						<input type="hidden" name="action" value="fælleshus_internet_force_set" />
						<td style="vertical-align:middle"><input type="submit" class="button-secondary" value="Sæt adgangskode" /></td>
					</tr>
				</form>
			</tbody>
		</table>
<?php } ?>