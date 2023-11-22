<?php

/**
 * @file Action to add a board document from the website
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'upload_dokument' && isset($_REQUEST['document_type']) && isset($_FILES['file']) && isset($_REQUEST['document_date'])){
		upload_dokument(
			$_REQUEST['document_type'],
			$_FILES["file"],
			new DateTime($_REQUEST['document_date']),
			(isset($_REQUEST['document_typetype']) ? $_REQUEST['document_typetype'] : '')
		);
	}
}

/**
 * Add a new board document to the system
 * 
 * Uses definitions from "wp-content\plugins\AKDTU\functions\bestyrelsesdokumenter.php"
 * 
 * @param string $document_type Internal representation of the type of document to be deleted
 * @param array $file File to be uploaded
 * @param DateTime $document_date Date for the document (e.g. date of meeting)
 * @param string $document_typetype Additional type info for the uploaded document, or empty string if such is not relevant
 */
function upload_dokument($document_type, $file, $document_date, $document_typetype = ''){
	global $bestyrelsesdocuments_document_types;

	# Check if the uploaded file is a pdf
	if( strtolower(pathinfo($file["name"], PATHINFO_EXTENSION)) != "pdf" ) {
		# Uploaded file is not a pdf. Write error message to admin interface
		new AKDTU_notice('error','Kun pdf-filer kan uploades.');
		return;
	}

	# Check if this is a valid document type
	if (isset($bestyrelsesdocuments_document_types[$document_type])) {
		# A valid type of document matched. Get info about the document
		$typedef = $bestyrelsesdocuments_document_types[$document_type];

		# Create global path for the file
		$target_file = $typedef['folder'] . $document_date->format($typedef['date-format']) . sprintf($typedef['after-date-text'], $document_typetype);

		# Check if the file already exists
		if (!file_exists($target_file)) {
			# File does not exist. Attempt to save the uploaded document
			if (move_uploaded_file($file["tmp_name"], $target_file)) {
				# Uploaded document was saved. Write success message to admin interface
				new AKDTU_notice('success','Dokumentet blev gemt.');
			} else {
				# Uploaded document was not saved. Write error message to admin interface
				new AKDTU_notice('error','Dokumentet kunne ikke gemmes.');
			}
		} else {
			# File already exists. Write error message to admin interface
			new AKDTU_notice('error','Der findes allerede et dokument af samme type fra samme dato. Slet dette før du uploader et nyt. Dokumentet er ikke gemt.');
		}
	} else {
		# No valid type of document matched. Write error message to admin interface
		new AKDTU_notice('error','Forkert information modtaget. Dokumentet er ikke gemt.' . json_encode($bestyrelsesdocuments_document_types));
	}
}
