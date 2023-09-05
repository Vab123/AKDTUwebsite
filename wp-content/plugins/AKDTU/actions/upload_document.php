<?php
require_once WP_PLUGIN_DIR . '/AKDTU/functions/notice.php';

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'upload_dokument' && isset($_REQUEST['document_type']) && isset($_FILES['file']) && isset($_REQUEST['document_date'])){
		upload_dokument();
	}
}

function upload_dokument(){
	if( strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION)) != "pdf" ) {
		new AKDTU_notice('error','Kun pdf-filer kan uploades.');
		return;
	}

	include WP_PLUGIN_DIR . '/AKDTU/functions/bestyrelsesdokumenter.php';

	$document_date = new DateTime($_REQUEST['document_date']);

	if (isset($bestyrelsesdocuments_document_types[$_REQUEST['document_type']])) {
		$typedef = $bestyrelsesdocuments_document_types[$_REQUEST['document_type']];

		$target_dir = $typedef['folder'];
		$target_file = $target_dir . $document_date->format($typedef['date-format']) . sprintf($typedef['after-date-text'],(isset($_REQUEST['document_typetype']) ? $_REQUEST['document_typetype'] : ''));

		if (!file_exists($target_file)) {
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
				new AKDTU_notice('success','Dokumentet blev gemt.');
			} else {
				new AKDTU_notice('error','Dokumentet kunne ikke gemmes.');
			}
		} else {
			new AKDTU_notice('error','Der findes allerede et dokument af samme type fra samme dato. Dokumentet er ikke gemt.');
		}
	} else {
		new AKDTU_notice('error','Forkert information modtaget. Dokumentet er ikke gemt.');
	}
}
