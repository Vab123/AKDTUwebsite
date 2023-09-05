<?php
require_once WP_PLUGIN_DIR . '/AKDTU/functions/notice.php';

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'remove_dokument' && isset($_REQUEST['document_type']) && isset($_REQUEST['document_name'])){
		fjern_dokument();
	}
}

function fjern_dokument(){
	include WP_PLUGIN_DIR . '/AKDTU/functions/bestyrelsesdokumenter.php';

	if (!isset($bestyrelsesdocuments_document_types[$_REQUEST['document_type']])) {
		new AKDTU_notice('error','Denne type af dokumenter kunne ikke behandles');
		return;
	} else {
		$typedef = $bestyrelsesdocuments_document_types[$_REQUEST['document_type']];

		$target_dir = $typedef['folder'];
		$target_file = $target_dir . '/' . $_REQUEST['document_name'];

		if (file_exists($target_file)) {
			if (unlink($target_file)) {
				new AKDTU_notice('success','Dokumentet blev fjernet.');
			} else {
				new AKDTU_notice('error','Dokumentet kunne ikke fjernes.');
			}
		} else {
			new AKDTU_notice('error','Der findes ingen dokumenter af denne type. Noget er gået galt.');
		}
	}
}
