<?php

/**
 * Shows all booking data for a single event 
 */
function em_bookings_print_event() {
	global $EM_Event, $EM_Person, $EM_Notices, $wpdb;

	//check that user can access this page
	if (is_object($EM_Event) && !$EM_Event->can_manage('manage_bookings', 'manage_others_bookings')) {
?>
		<div class="wrap">
			<h2><?php esc_html_e('Unauthorized Access', 'events-manager'); ?></h2>
			<p><?php esc_html_e('You do not have the rights to manage this event.', 'events-manager'); ?></p>
		</div>
	<?php
		return false;
	}
	$header_button_classes = is_admin() ? 'page-title-action' : 'button add-new-h2';
	if (empty($_REQUEST['event_ticket_id'])) {
		$_REQUEST['event_ticket_id'] = array_keys(em_get_event($_REQUEST['event_id'], 'event_id')->get_tickets()->tickets)[0];
	}
	$translations = pll_get_post_translations($EM_Event->post_id);

	$tickets = array();
	foreach ($translations as $translation) {
		foreach (em_get_event($translation, 'post_id')->get_bookings()->get_tickets()->tickets as $translated_ticket) {
			$tickets[$translated_ticket->ticket_id] = $translated_ticket;
		}
	}
	if (!array_key_exists($_REQUEST['event_ticket_id'], $tickets) && strtolower($_REQUEST['event_ticket_id']) != 'total') {
		echo "<div class='statusnotice'><div class='em-warning em-warning-errors notice notice-error'>This ticket was not found on this event!</div></div>";
	} else {
		if (array_key_exists($_REQUEST['event_ticket_id'], $tickets)) {
			$ticket = $tickets[$_REQUEST['event_ticket_id']];
			$bookings = array();
			foreach ($tickets as $translated_ticket) {
				if ($translated_ticket->ticket_name == $ticket->ticket_name) {
					foreach ($translated_ticket->get_bookings()->bookings as $booking) {
						if ($booking->booking_status == 1) {
							array_push($bookings, $booking);
						}
					}
				}
			}
		} else {
			$bookings = array();
			foreach ($tickets as $translated_ticket) {
				foreach ($translated_ticket->get_bookings()->bookings as $booking) {
					if ($booking->booking_status == 1) {
						array_push($bookings, $booking);
					}
				}
			}
		}
		usort($bookings, function ($a, $b) {
			return (get_userdata($a->person_id)->get("user_login") < get_userdata($b->person_id)->get("user_login")) ? -1 : 1;
		});
		//columns to show in table
		$cols = array('checkbox' => "Deltager", 'user_login' => "Lejlighed", 'user_name' => "Navn", 'booking_comment' => "Kommentar");
		$widths = array("checkbox" => "5%", "user_login" => "10%", "user_name" => "20%", "booking_comment" => "65%");
	}

	$res = $wpdb->get_results('SELECT ticket_id,showed_up FROM wp_em_tilmeldinger WHERE event_id = ' . $_REQUEST['event_id'] . ' AND ticket_id = ' . $_REQUEST['event_ticket_id']);
	$showed_up = (count($res) == 0 ? array() : json_decode($res[0]->showed_up));
	for ($floor = 0; $floor <= 2; $floor++) {
		for ($apartment = 1; $apartment <= 24; $apartment++) {
			if (array_key_exists(get_user_by('login', username_from_apartment_number($apartment) . '_archive')->ID, $showed_up)) {
				$showed_up[get_user_by('login', username_from_apartment_number($apartment))->ID] = $showed_up[get_user_by('login', username_from_apartment_number($apartment) . '_archive')->ID];
			}
			if (array_key_exists(get_user_by('login', username_from_apartment_number($apartment))->ID, $showed_up)) {
				$showed_up[get_user_by('login', username_from_apartment_number($apartment) . '_archive')->ID] = $showed_up[get_user_by('login', username_from_apartment_number($apartment))->ID];
			}
		}
	}
	
	$havedag_formatter = new IntlDateFormatter('da_DK', IntlDateFormatter::LONG, IntlDateFormatter::NONE);

	?>
	<div class='wrap'>
		<?php if (is_admin()) : ?><h1 class="wp-heading-inline"><?php else : ?><h2><?php endif; ?>
				<?php echo sprintf(__('Tilmeldingsliste til %s', 'events-manager'), "'{$EM_Event->event_name}'"); ?>
				<?php if (!is_admin()) : ?></h2><?php else : ?></h1>
			<hr class="wp-header-end" /><?php endif; ?>
		<?php if (!is_admin()) echo $EM_Notices; ?>
		<h2><?php esc_html_e('Date', 'events-manager'); ?></h2>
		<input type='hidden' name='selected_id' id='selected_id' value='<?php echo $_REQUEST['event_ticket_id']; ?>' />
		<form method='GET' action=''>
			<input type="hidden" name="post_type" value="event" />
			<input type="hidden" name="page" value="events-manager-print-bookings" />
			<input type='hidden' name='event_id' value='<?php echo $EM_Event->event_id; ?>' />
			<select name="event_ticket_id" id="event_ticket_id" onchange="document.getElementById('post-query-print').disabled = this.value != document.getElementById('selected_id').value">
				<?php
				$res = $EM_Event->get_bookings()->get_tickets();
				foreach ($res as $key => $value) : ?>
					<option value="<?php echo $key; ?>" <?php if (!empty($_REQUEST['event_ticket_id']) && $_REQUEST['event_ticket_id'] == $key) {
															echo " selected";
														} ?>><?php echo ((bool)strtotime($value->__get('ticket_name')) ? $havedag_formatter->format(new DateTime($value->__get('ticket_name'))) : $value->__get('ticket_name')); ?></option>
				<?php endforeach; ?>
				<option value="total" <?php if (!empty($_REQUEST['event_ticket_id']) && strtolower($_REQUEST['event_ticket_id']) == 'total') {
											echo " selected";
										} ?>>Samlet</option>
			</select>
			<input id="post-query-submit" class="button-secondary" type="submit" value="Vis">
			<?php if (!empty($_REQUEST['event_ticket_id']) && (array_key_exists($_REQUEST['event_ticket_id'], $tickets) || strtolower($_REQUEST['event_ticket_id']) == 'total')) : ?>
				<input id="post-query-print" class="button-secondary print-button" type="button" onclick="window.print()" value="Print">
			<?php endif; ?>
			</p>
		</form>
		<?php
		if (!empty($_REQUEST['event_ticket_id'])) :
			if (array_key_exists($_REQUEST['event_ticket_id'], $tickets)) :
		?>
				<div class='em-bookings-table em_obj' id="em-bookings-table">
					<div class='table-wrap'>
						<table id='dbem-bookings-table' class='widefat post ' style="max-width: 75em">
							<colgroup>
								<?php foreach ($cols as $action => $header) : ?>
									<col span="1" style="width: <?php echo $widths[$action]; ?>" />
								<?php endforeach; ?>
							</colgroup>
							<thead>
								<tr>
									<?php foreach ($cols as $action => $header) {
										echo "<th class='manage-column' scope='col'>" . $header . "</th>";
									} ?>
								</tr>
							</thead>
							<?php if (count($bookings) > 0) : ?>
								<tbody id="tilmeldingsliste">
									<?php
									$rowno = 0;
									$event_count = (!empty($event_count)) ? $event_count : 0;
									foreach ($bookings as $booking) {
										$user = get_userdata($booking->person_id);
									?>
										<tr <?php if ($rowno % 2 == 0) {echo ' class="alternate"';}; ?> >
											<?php
											foreach ($cols as $action => $header) {
												if ($action == "checkbox") {
													$id = $user->ID;
													echo "<td width='" . $widths[$action] . "'><input data-userid='" . $id . "' onclick='update_arrivalstatus(this)' type='checkbox'" . (isset($showed_up->$id) && $showed_up->$id ? ' checked' : '') . " /></td>";
												} elseif ($action == "booking_spaces") {
													echo "<td width='" . $widths[$action] . "'>" . $booking->get_spaces() . "</td>";
												} elseif ($action == "booking_comment") {
													echo "<td width='" . $widths[$action] . "'>" . $booking->booking_comment . "</td>";
												} elseif ($action == "user_name") {
													echo "<td width='" . $widths[$action] . "'>" . $booking->get_person()->get_name() . "</td>";
												} elseif ($action == "user_login") {
													$user_login = $user->get($action);
													if (substr($user_login, 0, 4) == "lejl") {
														echo "<td width='" . $widths[$action] . "'>" . substr($user_login, 4, 3) . "</td>";
													}
												} else {
													echo "<td width='" . $widths[$action] . "'>" . $user->get($action) . "</td>";
												}
											} ?>
										</tr>
									<?php
									$rowno++;
									}
									?>
								</tbody>
							<?php else : ?>
								<tbody>
									<tr>
										<td scope="row" colspan="<?php echo count($cols); ?>"><?php esc_html_e('No bookings.', 'events-manager'); ?></td>
									</tr>
								</tbody>
							<?php endif; ?>
						</table>
					</div>
				</div>
	</div>
	<script>
		function update_arrivalstatus(checkbox) {
			const xhttp = new XMLHttpRequest();
			xhttp.open("post", ajaxurl, true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			// xhttp.onreadystatechange = function(){
			// 	if(xhttp.readyState == 4 && xhttp.status == 200)
			// 	{
			// 		console.log(xhttp.responseText);
			// 	}
			// }
			xhttp.send('action=update_arrivalstatus&userid=' + checkbox.dataset.userid + '&status=' + checkbox.checked + '&event_id=<?php echo $_REQUEST['event_id']; ?>&event_ticket_id=<?php echo $_REQUEST['event_ticket_id']; ?>');
		}
	</script>
	<style>
		@media print {

			#adminmenumain,
			#wpadminbar,
			#wpfooter {
				display: none !important;
			}

			#wpcontent {
				margin-left: 0;
			}

			.update-nag,
			.notice,
			.notice-warning {
				display: none;
			}

			html.wp-toolbar {
				padding-top: 0;
			}
		}
	</style>
<?php elseif (strtolower($_REQUEST['event_ticket_id']) == 'total') :
	require_once WP_PLUGIN_DIR . "/AKDTU/functions/users.php";

	$status = array();
	foreach (all_apartments() as $apartment) {
		$status[$apartment] = false;
	}

	$res = $wpdb->get_col('SELECT showed_up FROM wp_em_tilmeldinger WHERE event_id = ' . $_REQUEST['event_id']);
	$res = array_map(function ($a) {
		return json_decode($a);
	}, $res);
	foreach ($res as $arr) {
		foreach ($arr as $user_id => $stat) {
			if (is_apartment_from_id($user_id)) {
				$status[apartment_number_from_id($user_id)] = $stat || $status[apartment_number_from_id($user_id)];
			}
		}
	}

	$booking_info = array_map(function ($booking) {
		$user_login = get_userdata($booking->person_id)->get('user_login');
		if (is_apartment_from_username($user_login)) {
			return array(
				"apt" => apartment_number_from_username($user_login),
				"date" => array_values(
					array_map(
						function($ticket) {
							return new DateTime($ticket->ticket_name, new DateTimeZone("Europe/Copenhagen"));
						},
						$booking->get_tickets()->tickets
					)
				)[0]
			);
		}
	}, $bookings);

	$booked_users = array();
	array_walk(
		$booking_info,
		function($value, $key) use(&$booked_users) {
			$booked_users[$value["apt"]] = $value["date"];
		}
	);

	$latest_signup_date = em_get_event($bookings[0]->event_id, 'event_id')->rsvp_date;

	$moved_users = all_moved_after_apartment_numbers($latest_signup_date);
	
	$showed_up_users = array_filter(array_keys($booked_users), function($apartment) use($status) { return $status[$apartment] == 1; });
?>
<div class="table-container">
	<table class="widefat showeduptable">
		<colgroup>
			<col span="1" width="35%">
			<col span="1" width="25%">
			<col span="1" width="20%">
			<col span="1" width="20%">
		</colgroup>
		<thead>
			<td>Lejlighed</td>
			<td>Tilmeldt</td>
			<td>Mødt op</td>
			<td>Opkræves</td>
		</thead>
		<tbody>
			<?php
				foreach (all_apartments() as $apartment) {
					if ($apartment < 100) {
						echo '<tr' . ($apartment % 2 == 1 ? ' class="alternate"' : '') . '>';

						echo '<td>' . padded_apartment_number_from_apartment_number($apartment) . (in_array($apartment, $moved_users) ? ' (Tidligere beboer)' : '') . (in_array($apartment, array_keys($booked_users)) && was_boardmember_from_apartment_number($apartment, $booked_users[$apartment]) ? ' - <u><b>Bestyrelsesmedlem</b></u>' : '') . (in_array($apartment, array_keys($booked_users)) && was_board_deputy_from_apartment_number($apartment, $booked_users[$apartment]) ? ' - <u><b>Bestyrelsessuppleant</b></u>' : '') . '</td>';
						echo '<td>' . (in_array($apartment, array_keys($booked_users)) ? $havedag_formatter->format($booked_users[$apartment]) : '<span style="font-weight: bold">✕</span>') . '</td>';
						echo '<td style="font-weight: bold">' . (in_array($apartment, $showed_up_users) ? "✓" : "✕") . '</td>';
						echo '<td>' . (!in_array($apartment, $showed_up_users) ? number_format(gardenday_price($apartment), 2, ",", ".") : number_format(0, 2, ",", ".")) . ' kr.</td>';

						echo '</tr>';
					}
				}
			?>
		</tbody>
	</table>
	<table class="widefat showeduptable">
		<colgroup>
			<col span="1" width="35%">
			<col span="1" width="25%">
			<col span="1" width="20%">
			<col span="1" width="20%">
		</colgroup>
		<thead>
			<td>Lejlighed</td>
			<td>Tilmeldt</td>
			<td>Mødt op</td>
			<td>Opkræves</td>
		</thead>
		<tbody>
			<?php
				foreach (all_apartments() as $apartment) {
					if ($apartment > 100 && $apartment < 200) {
						echo '<tr' . ($apartment % 2 == 1 ? ' class="alternate"' : '') . '>';

						echo '<td>' . padded_apartment_number_from_apartment_number($apartment) . (in_array($apartment, $moved_users) ? ' (Tidligere beboer)' : '') . (in_array($apartment, array_keys($booked_users)) && was_boardmember_from_apartment_number($apartment, $booked_users[$apartment]) ? ' - <u><b>Bestyrelsesmedlem</b></u>' : '') . (in_array($apartment, array_keys($booked_users)) && was_board_deputy_from_apartment_number($apartment, $booked_users[$apartment]) ? ' - <u><b>Bestyrelsessuppleant</b></u>' : '') . '</td>';
						echo '<td>' . (in_array($apartment, array_keys($booked_users)) ? $havedag_formatter->format($booked_users[$apartment]) : '<span style="font-weight: bold">✕</span>') . '</td>';
						echo '<td style="font-weight: bold">' . (in_array($apartment, $showed_up_users) ? "✓" : "✕") . '</td>';
						echo '<td>' . (!in_array($apartment, $showed_up_users) ? number_format(gardenday_price($apartment), 2, ",", ".") : number_format(0, 2, ",", ".")) . ' kr.</td>';

						echo '</tr>';
					}
				}
			?>
		</tbody>
	</table>
	<table class="widefat showeduptable">
		<colgroup>
			<col span="1" width="35%">
			<col span="1" width="25%">
			<col span="1" width="20%">
			<col span="1" width="20%">
		</colgroup>
		<thead>
			<td>Lejlighed</td>
			<td>Tilmeldt</td>
			<td>Mødt op</td>
			<td>Opkræves</td>
		</thead>
		<tbody>
			<?php
				foreach (all_apartments() as $apartment) {
					if ($apartment > 200) {
						echo '<tr' . ($apartment % 2 == 1 ? ' class="alternate"' : '') . '>';

						echo '<td>' . padded_apartment_number_from_apartment_number($apartment) . (in_array($apartment, $moved_users) ? ' (Tidligere beboer)' : '') . (in_array($apartment, array_keys($booked_users)) && was_boardmember_from_apartment_number($apartment, $booked_users[$apartment]) ? ' - <u><b>Bestyrelsesmedlem</b></u>' : '') . (in_array($apartment, array_keys($booked_users)) && was_board_deputy_from_apartment_number($apartment, $booked_users[$apartment]) ? ' - <u><b>Bestyrelsessuppleant</b></u>' : '') . '</td>';
						echo '<td>' . (in_array($apartment, array_keys($booked_users)) ? $havedag_formatter->format($booked_users[$apartment]) : '<span style="font-weight: bold">✕</span>') . '</td>';
						echo '<td style="font-weight: bold">' . (in_array($apartment, $showed_up_users) ? "✓" : "✕") . '</td>';
						echo '<td>' . (!in_array($apartment, $showed_up_users) ? number_format(gardenday_price($apartment), 2, ",", ".") : number_format(0, 2, ",", ".")) . ' kr.</td>';

						echo '</tr>';
					}
				}
			?>
		</tbody>
	</table>
</div>
<style>
	.table-container{
		display: flex;
		justify-content: space-between;
	}
	.showeduptable {
		width: 33%;
		display: inline-table; 
		vertical-align: top;
	}
	@media screen and (max-width: 1265px) {
		.showeduptable {
			width: 100%;
		}
		.showeduptable:not(:first-child) {
			margin-top: 20px;
		}
		.table-container {
			display: initial;
		}
	}
</style>
<?php endif;
		endif;
	}

	/**
	 * Determines whether to show event page or events page, and saves any updates to the event or events
	 * @return null
	 */
	function em_bookings_events_print_table() {
		//TODO Simplify panel for events, use form flags to detect certain actions (e.g. submitted, etc)
		global $wpdb;

		$scope_names = array(
			'past' => __('Past events', 'events-manager'),
			'all' => __('All events', 'events-manager'),
			'future' => __('Future events', 'events-manager')
		);

		$action_scope = (!empty($_REQUEST['em_obj']) && $_REQUEST['em_obj'] == 'em_bookings_events_print_table');
		$action = ($action_scope && !empty($_GET['action'])) ? $_GET['action'] : '';
		$order = ($action_scope && !empty($_GET['order'])) ? $_GET['order'] : 'ASC';
		$limit = ($action_scope && !empty($_GET['limit'])) ? $_GET['limit'] : 20; //Default limit
		$page = ($action_scope && !empty($_GET['pno'])) ? $_GET['pno'] : 1;
		$offset = ($action_scope && $page > 1) ? ($page - 1) * $limit : 0;
		$scope = ($action_scope && !empty($_GET['scope']) && array_key_exists($_GET['scope'], $scope_names)) ? $_GET['scope'] : 'all';

		// No action, only showing the events list
		switch ($scope) {
			case "past":
				$title = __('Past Events', 'events-manager');
				break;
			case "all":
				$title = __('All Events', 'events-manager');
				break;
			default:
				$title = __('Future Events', 'events-manager');
				$scope = "future";
		}
		$owner = !current_user_can('manage_others_bookings') ? get_current_user_id() : false;
		$events = array_filter(EM_Events::get(array('scope' => $scope, 'limit' => $limit, 'offset' => $offset, 'order' => $order, 'orderby' => 'event_start', 'bookings' => true, 'owner' => $owner, 'pagination' => 1)), function ($event) {
			return pll_get_post_language($event->post_id, "slug") == "da";
		}); # Get Danish Events with booking enabled
		$events_count = EM_Events::$num_rows_found;

		$use_events_end = get_option('dbem_use_event_end');
?>
<div class="wrap em_bookings_events_table em_obj">
	<form id="posts-filter" action="" method="post">
		<input type="hidden" name="em_obj" value="em_bookings_events_print_table" />
		<?php if (!empty($_GET['page'])) : ?>
			<input type='hidden' name='page' value='events-manager-print-bookings' />
			<input type="hidden" name="action" value="events-manager-print-bookings" />
		<?php endif; ?>
		<div class="tablenav">
			<div class="alignleft actions">
				<select name="scope">
					<?php
					foreach ($scope_names as $key => $value) {
						$selected = "";
						if ($key == $scope)
							$selected = "selected='selected'";
						echo "<option value='$key' $selected>$value</option>  ";
					}
					?>
				</select>
				<input id="post-query-submit" class="button-secondary" type="submit" value="<?php esc_html_e('Filter') ?>" />
			</div>
			<?php
			if ($events_count >= $limit) {
				$events_nav = em_admin_paginate($events_count, $limit, $page, array('em_ajax' => 0, 'em_obj' => 'em_bookings_events_print_table'));
				echo $events_nav;
			}
			?>
		</div>
		<div class="clear"></div>
		<?php
		if (empty($events)) {
			// TODO localize
			_e('no events', 'events-manager');
		} else {
		?>
			<div class='table-wrap'>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php esc_html_e('Event', 'events-manager'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$rowno = 0;
						foreach ($events as $EM_Event) {
							/* @var $event EM_Event */
							$rowno++;
							$class = ($rowno % 2) ? ' class="alternate"' : '';
							$style = "";

							if ($EM_Event->start()->getTimestamp() < time() && $EM_Event->end()->getTimestamp() < time()) {
								$style = "style ='background-color: #FADDB7;'";
							}
						?>
							<tr <?php echo "$class $style"; ?>>
								<td>
									<strong>
										<?php echo str_replace("events-manager-bookings", "events-manager-print-bookings", $EM_Event->output('#_BOOKINGSLINK')); ?>
									</strong>
									&ndash;
									<?php $translations = pll_get_post_translations($EM_Event->post_id);
									$booked_spaces = 0;
									foreach ($translations as $lang => $post_id) {
										$booked_spaces += em_get_event($post_id, 'post_id')->get_bookings()->get_booked_spaces();
									}
									esc_html_e("Booked Spaces", 'events-manager'); ?>: <?php echo $booked_spaces . "/" . $EM_Event->get_spaces(); ?>
									<?php if (get_option('dbem_bookings_approval') == 1) : ?>
										| <?php esc_html_e("Pending", 'events-manager') ?>: <?php echo $EM_Event->get_bookings()->get_pending_spaces(); ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		<?php
		} // end of table
		?>
		<div class='tablenav'>
			<div class="alignleft actions">
				<br class='clear' />
			</div>
			<?php if (!empty($events_nav) &&  $events_count >= $limit) : ?>
				<div class="tablenav-pages">
					<?php
					echo $events_nav;
					?>
				</div>
			<?php endif; ?>
			<br class='clear' />
		</div>
	</form>
</div>
<?php
	}
?>