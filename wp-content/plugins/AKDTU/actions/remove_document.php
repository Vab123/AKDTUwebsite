<?php

/**
 * @file Action to remove a board document from the website
 */

# Register custom action
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'remove_dokument' && isset($_REQUEST['document_type']) && isset($_REQUEST['document_name'])){
		fjern_dokument($_REQUEST['document_type'], $_REQUEST['document_name']);
	}
}

/**
 * Remove a board document from the system
 * 
 * Uses definitions from "wp-content\plugins\AKDTU\functions\bestyrelsesdokumenter.php"
 * 
 * @param string $document_type Internal representation of the type of document to be deleted
 * @param string $filename Filename of the document to be deleted
 */
function fjern_dokument($document_type, $filename){
	# Check if a valid type of document was requested deleted
	if (!isset($bestyrelsesdocuments_document_types[$document_type])) {
		# No valid type of document matched. Write error message to admin interface
		new AKDTU_notice('error','Denne type af dokumenter kunne ikke behandles');
		return;
	} else {
		# A valid type of document matched. Get info about the document
		$typedef = $bestyrelsesdocuments_document_types[$document_type];

		# Create global path for the file
		$target_file = $typedef['folder'] . '/' . $filename;

		# Check if the file exists
		if (file_exists($target_file)) {
			# The file existed. Attempt to delete it
			if (unlink($target_file)) {
				# The file was deleted. Write success message to admin interface
				new AKDTU_notice('success','Dokumentet blev fjernet.');
			} else {
				# The file was not deleted. Write error message to admin interface
				new AKDTU_notice('error','Dokumentet kunne ikke fjernes.');
			}
		} else {
			# The file did not exist. Write error message to admin interface
			new AKDTU_notice('error','Der findes ingen dokumenter af denne type. Noget er gået galt.');
		}
	}
}
