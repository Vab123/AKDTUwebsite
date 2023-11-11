<?php

function fælleshus_internet_dashboard_widget() {
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
						Bemærk: Internetforbindelsen i fælleshuset opdateres automatisk hver dag klokken 12:01. Nedenstående bør være de korrekte informationer.<br>
						Hvis ikke, så kan informationerne gennemtvinges ved at trykke på knappen. Dette sender også en mail med login-informationer til lejeren!
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="alternate">
					<td style="vertical-align:middle">
						SSID:
					</td>
					<td style="vertical-align:middle">
						<b><?php echo $router_settings["ssid"]; ?></b>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:middle">
						Adgangskode:
					</td>
					<td style="vertical-align:middle">
						<b><?php echo $password_struct["password"]; ?></b>
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