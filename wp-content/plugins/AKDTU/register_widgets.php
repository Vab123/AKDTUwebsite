<?php

/**
 * @var $AKDTU_WIDGETS Structure containing information about all widgets to load.
 * 
 * Structure:
 * 
 * - Groupname => array
 * > - "require_admin" => bool,
 * > - "widgets" => array
 * >> - "widgetfilename" => array
 * >>> - "id" => string,
 * >>> - "name" => string,
 * >>> - "callback" => string,
 * >> - ...
 * > - "actions" => array
 * >> - "actionfilename",
 * >> - ...
 * 
 * Keys: 
 * - "require_admin": Boolean. True if the widget should be only loaded for administrators of the website (netgruppen).
 * - "id": String. Unique id of the widget. Only used internally by Wordpress.
 * - "name": String. Title of the widget.
 * - "callback": String. Name of the function to run to get the contents of the widget.
 * - "widgetfilename": String. Filename of the file with the widget function.
 * - "actionfilename": String. Filename of the file with the action function.
 */
$AKDTU_WIDGETS = array(
	"allowed_renter_signups" => array(
		"require_admin" => false,
		"widgets" => array(
			"allowed_renter_signups.php" => array(
				"id" => "allowed_renter_signups_widget",
				"name" => "Midlertidige lejere",
				"callback" => "allowed_renter_signups_widget",
			),
		),
		"actions" => array(

		),
	),
	"allow_renter_signups" => array(
		"require_admin" => false,
		"widgets" => array(
			"allow_renter_signups.php" => array(
				"id" => "allow_renter_signup_widget",
				"name" => "Tillad ny midlertidig lejer",
				"callback" => "allow_renter_signup_widget",
			),
		),
		"actions" => array(
			"allow_renter_signup.php",
			"delete_renter_signup.php",
		),
	),
	"allowed_user_signups" => array(
		"require_admin" => false,
		"widgets" => array(
			"allowed_user_signups.php" => array(
				"id" => "allowed_user_signups_widget",
				"name" => "Tilladte brugeroprettelser",
				"callback" => "allowed_user_signups_widget",
			),
		),
		"actions" => array(

		),
	),
	"allow_user_signups" => array(
		"require_admin" => false,
		"widgets" => array(
			"allow_user_signups.php" => array(
				"id" => "allow_user_signup_widget",
				"name" => "Tillad ny brugeroprettelse",
				"callback" => "allow_user_signup_widget",
			),
		),
		"actions" => array(
			"allow_user_signup.php", 
			"delete_user_signup.php",
		),
	),
	"book_fælleshus" => array(
		"require_admin" => false,
		"widgets" => array(
			"book_fælleshus_beboer.php" => array(
				"id" => "book_fælleshus_beboer_dashboard_widget",
				"name" => "Reserver fælleshus til beboer",
				"callback" => "book_fælleshus_beboer_dashboard_widget",
			),
			"book_fælleshus_bestyrelse.php" => array(
				"id" => "book_fælleshus_bestyrelse_dashboard_widget",
				"name" => "Reserver fælleshus til bestyrelse",
				"callback" => "book_fælleshus_bestyrelse_dashboard_widget",
			),
		),
		"actions" => array(
			"book_fælleshus.php",
		),
	),
	"fjern_dokumenter" => array(
		"require_admin" => false,
		"widgets" => array(
			"fjern_dokumenter.php" => array(
				"id" => "fjern_dokument_dashboard_widget",
				"name" => "Fjern bestyrelsesdokument",
				"callback" => "fjern_dokument_dashboard_widget",
			),
		),
		"actions" => array(
			"remove_document.php",
		),
	),
	"fjern_tilmelding_til_havedag" => array(
		"require_admin" => false,
		"widgets" => array(
			"havedag_fjern_tilmelding.php" => array(
				"id" => "fjern_tilmelding_til_havedag_dashboard_widget",
				"name" => "Fjern lejlighed fra havedag",
				"callback" => "fjern_tilmelding_til_havedag_dashboard_widget",
			),
		),
		"actions" => array(
			"afmeld_lejlighed_til_havedag.php",
		),
	),
	"fælleshus_afventer" => array(
		"require_admin" => false,
		"widgets" => array(
			"fælleshus_afventer.php" => array(
				"id" => "fælleshus_afventer_dashboard_widget",
				"name" => "Ansøgninger om leje af fælleshus",
				"callback" => "fælleshus_afventer_dashboard_widget",
			),
		),
		"actions" => array(
			"publish_leje.php",
			"delete_leje.php",
		),
	),
	"fælleshus_internet_toggle" => array(
		"require_admin" => false,
		"widgets" => array(
			"fælleshus_internet_toggle.php" => array(
				"id" => "fælleshus_internet_dashboard_widget",
				"name" => "Opdater fælleshus internetforbindelse",
				"callback" => "fælleshus_internet_dashboard_widget",
			),
		),
		"actions" => array(
			"fælleshus_internet_force_update.php",
		),
	),
	"fælleshus_juster_pris" => array(
		"require_admin" => false,
		"widgets" => array(
			"fælleshus_juster_pris.php" => array(
				"id" => "fælleshus_juster_pris_widget",
				"name" => "Juster opkrævning for leje af fælleshus",
				"callback" => "fælleshus_juster_pris_widget",
			),
		),
		"actions" => array(
			"fælleshus_juster_pris.php",
		),
	),
	"fælleshus_næste_reservationer" => array(
		"require_admin" => false,
		"widgets" => array(
			"fælleshus_næste_reservationer.php" => array(
				"id" => "fælleshus_næste_reservationer_dashboard_widget",
				"name" => "Næste reservationer af fælleshus",
				"callback" => "fælleshus_næste_reservationer_dashboard_widget",
			),
		),
		"actions" => array(
			
		),
	),
	"fælleshus_tidligere_reservationer" => array(
		"require_admin" => false,
		"widgets" => array(
			"fælleshus_tidligere_reservationer.php" => array(
				"id" => "fælleshus_tidligere_reservationer_dashboard_widget",
				"name" => "Tidligere reservationer af fælleshus",
				"callback" => "fælleshus_tidligere_reservationer_dashboard_widget",
			),
		),
		"actions" => array(
			
		),
	),
	"fælleshus_total" => array(
		"require_admin" => false,
		"widgets" => array(
			"fælleshus_total.php" => array(
				"id" => "fælleshus_total_widget",
				"name" => "Samlet indtægt for leje af fælleshus, år",
				"callback" => "fælleshus_total_widget",
			),
		),
		"actions" => array(
			
		),
	),
	"fælleshus_total_month" => array(
		"require_admin" => false,
		"widgets" => array(
			"fælleshus_total_month.php" => array(
				"id" => "fælleshus_total_month_widget",
				"name" => "Samlet indtægt for leje af fælleshus, måned",
				"callback" => "fælleshus_total_month_widget",
			),
		),
		"actions" => array(
			
		),
	),
	"fælleshus_vis_foreløbig_pris" => array(
		"require_admin" => false,
		"widgets" => array(
			"fælleshus_vis_foreløbig_pris.php" => array(
				"id" => "fælleshus_vis_foreløbig_pris_widget",
				"name" => "Foreløbig opkrævning for leje af fælleshus",
				"callback" => "fælleshus_vis_foreløbig_pris_widget",
			),
		),
		"actions" => array(
			
		),
	),
	"havedag_future" => array(
		"require_admin" => false,
		"widgets" => array(
			"havedag_future.php" => array(
				"id" => "havedag_future_dashboard_widget",
				"name" => "Næste havedage",
				"callback" => "havedag_future_dashboard_widget",
			),
		),
		"actions" => array(
			
		),
	),
	"havedag_past" => array(
		"require_admin" => false,
		"widgets" => array(
			"havedag_past.php" => array(
				"id" => "havedag_past_dashboard_widget",
				"name" => "Tidligere havedage",
				"callback" => "havedag_past_dashboard_widget",
			),
		),
		"actions" => array(
			
		),
	),
	"moves_future" => array(
		"require_admin" => false,
		"widgets" => array(
			"moves_future.php" => array(
				"id" => "moves_future_dashboard_widget",
				"name" => "Fremtidige overdragelser",
				"callback" => "moves_future_dashboard_widget",
			),
		),
		"actions" => array(
			
		),
	),
	"moves_past" => array(
		"require_admin" => false,
		"widgets" => array(
			"moves_past.php" => array(
				"id" => "moves_past_dashboard_widget",
				"name" => "Tidligere overdragelser",
				"callback" => "moves_past_dashboard_widget",
			),
		),
		"actions" => array(
			
		),
	),
	"opret_havedage" => array(
		"require_admin" => false,
		"widgets" => array(
			"opret_havedage.php" => array(
				"id" => "opret_havedag_dashboard_widget",
				"name" => "Opret havedage",
				"callback" => "opret_havedag_dashboard_widget",
			),
		),
		"actions" => array(
			"opret_havedage.php",
		),
	),
	"tilføj_dokumenter" => array(
		"require_admin" => false,
		"widgets" => array(
			"tilføj_dokumenter.php" => array(
				"id" => "tilføj_dokument_dashboard_widget",
				"name" => "Upload bestyrelsesdokument",
				"callback" => "tilføj_dokument_dashboard_widget",
			),
		),
		"actions" => array(
			"upload_document.php",
		),
	),
	"tilføj_til_havedag" => array(
		"require_admin" => false,
		"widgets" => array(
			"havedag_tilføj_tilmelding.php" => array(
				"id" => "tilføj_til_havedag_dashboard_widget",
				"name" => "Tilføj lejlighed til havedag",
				"callback" => "tilføj_til_havedag_dashboard_widget",
			),
		),
		"actions" => array(
			"tilmeld_lejlighed_til_havedag.php",
		),
	),
	"show_board" => array(
		"require_admin" => true,
		"widgets" => array(
			"show_board.php" => array(
				"id" => "show_board_widget",
				"name" => "Bestyrelsesmedlemmer",
				"callback" => "show_board_widget",
			),
		),
		"actions" => array(
			"remove_boardmember.php",
		),
	),
	"add_boardmember" => array(
		"require_admin" => true,
		"widgets" => array(
			"add_boardmember.php" => array(
				"id" => "add_boardmember_widget",
				"name" => "Tilføj bestyrelsesmedlem",
				"callback" => "add_boardmember_widget",
			),
		),
		"actions" => array(
			"add_boardmember.php",
		),
	),
	"fælleshus_vlan_toggle" => array(
		"require_admin" => true,
		"widgets" => array(
			"fælleshus_vlan_toggle.php" => array(
				"id" => "fælleshus_vlan_dashboard_widget",
				"name" => "Fælleshus VLAN",
				"callback" => "fælleshus_vlan_dashboard_widget",
			),
		),
		"actions" => array(
			"fælleshus_vlan_tænd.php",
			"fælleshus_vlan_sluk.php",
		),
	),
	"show_vicevært" => array(
		"require_admin" => true,
		"widgets" => array(
			"show_vicevært.php" => array(
				"id" => "show_vicevært_widget",
				"name" => "Viceværter",
				"callback" => "show_vicevært_widget",
			),
		),
		"actions" => array(
			"remove_vicevært.php",
		),
	),
	"add_vicevært" => array(
		"require_admin" => true,
		"widgets" => array(
			"add_vicevært.php" => array(
				"id" => "add_vicevært_widget",
				"name" => "Tilføj vicevært",
				"callback" => "add_vicevært_widget",
			),
		),
		"actions" => array(
			"add_vicevært.php",
		),
	),
	"fælleshus_internet_set" => array(
		"require_admin" => true,
		"widgets" => array(
			"fælleshus_internet_set.php" => array(
				"id" => "fælleshus_internet_set_dashboard_widget",
				"name" => "Indstil fælleshus internetforbindelse",
				"callback" => "fælleshus_internet_set_dashboard_widget",
			),
		),
		"actions" => array(
			"fælleshus_internet_force_set.php",
		),
	),
);

## Add widgets
add_action('init', 'add_widgets');

/**
 * Loads all files required for widgets defined in `$AKDTU_WIDGETS`, and schedules setup of all the widgets.
 */
function add_widgets() {
	global $AKDTU_WIDGETS;

	if (current_user_can('add_users')) { # Only admins
		$is_administrator = current_user_can('edit_others_events');

		foreach ($AKDTU_WIDGETS as $group_name => $group_spec) {
			if (!$group_spec["require_admin"] || $is_administrator) {
				foreach ($group_spec["widgets"] as $widget_file => $widget_spec) {
					include_once "widgets/" . $widget_file;
				}
				
				foreach ($group_spec["actions"] as $action_file) {
					include_once "actions/" . $action_file;
				}
			}
		}
		
		add_action('wp_dashboard_setup', 'AKDTU_widgets');
	}
}

/**
 * Setups all widgets defined in `$AKDTU_WIDGETS`. Should be added to the 'wp_dashboard_setup' action.
 */
function AKDTU_widgets() {
	global $AKDTU_WIDGETS;

	if (current_user_can('add_users')) { # Only admins
		$is_administrator = current_user_can('edit_others_events');

		foreach ($AKDTU_WIDGETS as $group_name => $group_spec) {
			if (!$group_spec["require_admin"] || $is_administrator) {
				foreach ($group_spec["widgets"] as $widget_file => $widget_spec) {
					wp_add_dashboard_widget($widget_spec["id"], $widget_spec["name"], $widget_spec["callback"]);
				}
			}
		}
	}
}
