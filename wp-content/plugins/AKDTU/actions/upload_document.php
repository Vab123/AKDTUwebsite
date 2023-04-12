<?php

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

	$types = array(
		'board' => array(
			'folder' => WORKING_DIR . BESTYRELSE_FOLDER,
			'date-format' => "Y-m-d",
			'after-date-text' => ' Referat.pdf'
		),
		'GF_REF' => array(
			'folder' => WORKING_DIR . GF_FOLDER,
			'date-format' => "Y-m-d",
			'after-date-text' => ' %1$s Referat.pdf'
		),
		'aar' => array(
			'folder' => WORKING_DIR . ÅRSRAPPORT_FOLDER,
			'date-format' => "Y",
			'after-date-text' => ' Årsrapport.pdf'
		),
		'budget' => array(
			'folder' => WORKING_DIR . BUDGET_FOLDER,
			'date-format' => "Y",
			'after-date-text' => ' Budget.pdf'
		)
	);

	$document_date = new DateTime($_REQUEST['document_date']);

	if (isset($types[$_REQUEST['document_type']])) {
		$target_dir = $types[$_REQUEST['document_type']]['folder'];
		$target_file = $target_dir . $document_date->format($types[$_REQUEST['document_type']]['date-format']) . sprintf($types[$_REQUEST['document_type']]['after-date-text'],(isset($_REQUEST['document_typetype']) ? $_REQUEST['document_typetype'] : ''));

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
