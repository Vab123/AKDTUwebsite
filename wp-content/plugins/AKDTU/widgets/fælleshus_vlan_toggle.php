<?php

/**
 * @file Widget to toggle the status of the VLAN corresponding to the internet connection in the common house
 */

function fælleshus_vlan_dashboard_widget() {
	$rental_info = get_current_common_house_renters();

	$event_owners = $rental_info['renters'];
	$rented = $rental_info['rented'];

		$state = get_fælleshus_vlan()->state; ?>
		<table width="100%" class="widefat">
			<colgroup>
				<col span="1" style="width: 40%">
				<col span="1" style="width: 60%">
			</colgroup>
			<tbody>
				<tr class="alternate">
					<td style="vertical-align:middle">
						Internet forbindelse:
					</td>
					<td style="vertical-align:middle">
						<b><?php echo ($state ? "Tændt" : "Slukket"); ?></b>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:middle">
						Fælleshus status:
					</td>
					<td style="vertical-align:middle">
						<b><?php echo ($rented ? "Lejet af " . $event_owners : "Ledigt"); ?></b>
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
}

?>