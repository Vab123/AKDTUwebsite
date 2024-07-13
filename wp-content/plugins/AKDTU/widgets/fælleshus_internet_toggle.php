<?php

/**
 * @file Widget to display the current settings to the router in the common house, and forcefully update these
 */

function fælleshus_internet_dashboard_widget() {
	$rental_info = get_current_common_house_renters();

	$event_owners = $rental_info['renters'];
	$rented = $rental_info['rented'];

	$router_settings = get_router_settings();
	$password_struct = generate_password_info(); ?>
		<table width="100%" class="widefat">
			<colgroup>
				<col span="1" style="width: 60%">
				<col span="1" style="width: 40%">
			</colgroup>
			<thead>
				<tr>
					<th colspan="2" style="font-weight:bold;">
					Bemærk: Internetforbindelsen i fælleshuset opdateres automatisk hver time. Nedenstående bør være de korrekte informationer.<br>
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
						<b><?php echo $rented ? "Lejet af " . $event_owners : "Ledigt"; ?></b>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:middle">
						Adgangskode skal opdateres
					</td>
					<td style="vertical-align:middle">
						<b><?php echo $password_struct["should_be_changed"] ? "Ja" : "Nej"; ?></b>
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