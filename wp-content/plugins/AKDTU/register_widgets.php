<?php

## Add widgets
add_action('init', 'add_widgets');

function add_widgets() {
	if (current_user_can('edit_others_events')) {
		## Actions
		# allow_user_signups widget
		include_once "actions/allow_user_signup.php";
		include_once "actions/delete_user_signup.php";

		# allow_renter_signups widget
		include_once "actions/allow_renter_signup.php";
		include_once "actions/delete_renter_signup.php";

		# book_fælleshus widget
		include_once "actions/book_fælleshus.php";

		# opret_havedage widget
		include_once "actions/opret_havedage.php";

		# tilføj_til_havedag widget
		include_once "actions/tilmeld_lejlighed_til_havedag.php";

		# fjern_tilmelding_til_havedag widget
		include_once "actions/afmeld_lejlighed_til_havedag.php";

		# fælleshus_juster_pris widget
		include_once "actions/fælleshus_juster_pris.php";

		# fælleshus_afventer widget
		include_once "actions/publish_leje.php";
		include_once "actions/delete_leje.php";

		# tilføj_dokumenter widget
		include_once "actions/upload_document.php";

		# fjern_dokumenter widget
		include_once "actions/remove_document.php";

		# fælleshus_internet widget
		include_once "actions/fælleshus_internet_force_update.php";

		## Widgets
		include_once "widgets/allowed_renter_signups.php";
		include_once "widgets/allowed_user_signups.php";
		include_once "widgets/allow_renter_signups.php";
		include_once "widgets/allow_user_signups.php";
		include_once "widgets/book_fælleshus_beboer.php";
		include_once "widgets/book_fælleshus_bestyrelse.php";
		include_once "widgets/fælleshus_afventer.php";
		include_once "widgets/fælleshus_internet_toggle.php";
		include_once "widgets/fælleshus_næste_reservationer.php";
		include_once "widgets/fælleshus_tidligere_reservationer.php";
		include_once "widgets/fælleshus_vis_foreløbig_pris.php";
		include_once "widgets/fælleshus_juster_pris.php";
		include_once "widgets/fælleshus_total.php";
		include_once "widgets/fælleshus_total_month.php";
		include_once "widgets/havedag_future.php";
		include_once "widgets/havedag_past.php";
		include_once "widgets/opret_havedage.php";
		include_once "widgets/havedag_tilføj_tilmelding.php";
		include_once "widgets/havedag_fjern_tilmelding.php";
		include_once "widgets/moves_future.php";
		include_once "widgets/moves_past.php";
		include_once "widgets/tilføj_dokumenter.php";
		include_once "widgets/fjern_dokumenter.php";

		if (current_user_can('add_users')) { # Only admins
			# show_board widget
			include_once "widgets/show_board.php";
			include_once "actions/remove_boardmember.php";

			# add_boardmember widget
			include_once "widgets/add_boardmember.php";
			include_once "actions/add_boardmember.php";

			# fælleshus_vlan_toggle widget
			include_once "widgets/fælleshus_vlan_toggle.php";
			include_once "actions/fælleshus_vlan_tænd.php";
			include_once "actions/fælleshus_vlan_sluk.php";

			# show_vicevært widget
			include_once "widgets/show_vicevært.php";
			include_once "actions/remove_vicevært.php";

			# add_vicevært widget
			include_once "widgets/add_vicevært.php";
			include_once "actions/add_vicevært.php";

			# fælleshus_internet_set widget
			include_once "widgets/fælleshus_internet_set.php";
			include_once "actions/fælleshus_internet_force_set.php";
		}

		add_action('wp_dashboard_setup', 'AKDTU_widgets');
	}
}

function AKDTU_widgets() {
	global $wp_meta_boxes;

	wp_add_dashboard_widget('book_fælleshus_beboer_dashboard_widget', 'Reserver fælleshus til beboer', 'book_fælleshus_beboer_dashboard_widget');
	wp_add_dashboard_widget('book_fælleshus_bestyrelse_dashboard_widget', 'Reserver fælleshus til bestyrelse', 'book_fælleshus_bestyrelse_dashboard_widget');
	wp_add_dashboard_widget('fælleshus_næste_reservationer_dashboard_widget', 'Næste reservationer af fælleshus', 'fælleshus_næste_reservationer_dashboard_widget');
	wp_add_dashboard_widget('fælleshus_tidligere_reservationer_dashboard_widget', 'Tidligere reservationer af fælleshus', 'fælleshus_tidligere_reservationer_dashboard_widget');
	wp_add_dashboard_widget('fælleshus_afventer_dashboard_widget', 'Ansøgninger om leje af fælleshus', 'fælleshus_afventer_dashboard_widget');

	wp_add_dashboard_widget('havedag_future_dashboard_widget', 'Næste havedage', 'havedag_future_dashboard_widget');
	wp_add_dashboard_widget('havedag_past_dashboard_widget', 'Tidligere havedage', 'havedag_past_dashboard_widget');
	wp_add_dashboard_widget('tilføj_til_havedag_dashboard_widget', 'Tilføj lejlighed til havedag', 'tilføj_til_havedag_dashboard_widget');
	wp_add_dashboard_widget('fjern_tilmelding_til_havedag_dashboard_widget', 'Fjern lejlighed fra havedag', 'fjern_tilmelding_til_havedag_dashboard_widget');

	wp_add_dashboard_widget('fælleshus_vis_foreløbig_pris_widget', 'Foreløbig opkrævning for leje af fælleshus', 'fælleshus_vis_foreløbig_pris_widget');
	wp_add_dashboard_widget('fælleshus_juster_pris_widget', 'Juster opkrævning for leje af fælleshus', 'fælleshus_juster_pris_widget');
	wp_add_dashboard_widget('fælleshus_total_widget', 'Samlet indtægt for opkrævning for leje af fælleshus, årsbasis', 'fælleshus_total_widget');
	wp_add_dashboard_widget('fælleshus_total_month_widget', 'Samlet indtægt for opkrævning for leje af fælleshus, månedsbasis', 'fælleshus_total_month_widget');

	wp_add_dashboard_widget('opret_havedag_dashboard_widget', 'Opret havedage', 'opret_havedag_dashboard_widget');

	wp_add_dashboard_widget('allow_user_signup_widget', 'Tillad ny brugeroprettelse', 'allow_user_signup_widget');
	wp_add_dashboard_widget('allowed_user_signups_widget', 'Tilladte brugeroprettelser', 'allowed_user_signups_widget');

	wp_add_dashboard_widget('allow_renter_signup_widget', 'Tillad ny midlertidig lejer', 'allow_renter_signup_widget');
	wp_add_dashboard_widget('allowed_renter_signups_widget', 'Midlertidige lejere', 'allowed_renter_signups_widget');

	wp_add_dashboard_widget('moves_future_dashboard_widget', 'Fremtidige overdragelser', 'moves_future_dashboard_widget');
	wp_add_dashboard_widget('moves_past_dashboard_widget', 'Tidligere overdragelser', 'moves_past_dashboard_widget');

	wp_add_dashboard_widget('tilføj_dokument_dashboard_widget', 'Upload bestyrelsesdokument', 'tilføj_dokument_dashboard_widget');
	wp_add_dashboard_widget('fjern_dokument_dashboard_widget', 'Fjern bestyrelsesdokument', 'fjern_dokument_dashboard_widget');

	wp_add_dashboard_widget('fælleshus_internet_dashboard_widget', 'Fælleshus internetforbindelse', 'fælleshus_internet_dashboard_widget');

	if (current_user_can('add_users')) { # Only admins
		wp_add_dashboard_widget('show_board_widget', 'Bestyrelsesmedlemmer', 'show_board_widget');
		wp_add_dashboard_widget('add_boardmember_widget', 'Tilføj bestyrelsesmedlem', 'add_boardmember_widget');

		wp_add_dashboard_widget('fælleshus_vlan_dashboard_widget', 'Fælleshus VLAN', 'fælleshus_vlan_dashboard_widget');

		wp_add_dashboard_widget('fælleshus_internet_set_dashboard_widget', 'Fælleshus internetforbindelse adgangskode', 'fælleshus_internet_set_dashboard_widget');
		
		wp_add_dashboard_widget('show_vicevært_widget', 'Viceværter', 'show_vicevært_widget');
		wp_add_dashboard_widget('add_vicevært_widget', 'Tilføj vicevært', 'add_vicevært_widget');
	}
}
