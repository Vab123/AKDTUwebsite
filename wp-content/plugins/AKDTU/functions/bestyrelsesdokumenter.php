<?php

/**
 * @file Functionality related to the storage and display of Association documents on the website
 */

/**
 * @var $bestyrelsesdocuments_document_types Information about the types of documents published on the website by the Board
 * 
 * Keys:
 * 	- `short` Internal reference to the type of document. Same as the key to the array
 * 	- `name` External reference to the type of document.
 * 	- `has_type` Flag, if the document has multiple sub-types, such as minutes from ordinary or extra-ordinary general assemblies
 * 	- `type_options` Array of key-values for the sub-types of the document, if these exists, or NULL otherwise
 * 	- `folder` Folder containing all of the document-files
 * 	- `date-format` Format string for the date of the document
 * 	- `after-date-text` Text which should be added after the date in the document file-name. "%1$s" is replaced with the specific type of document, if `has_type` is true
 */
$bestyrelsesdocuments_document_types = [
	'board' => [
		'short' => 'board',
		'name' => 'Referat af bestyrelsesmøde',
		'has_type' => false,
		'type_options' => NULL,
		'folder' => WORKING_DIR . BESTYRELSE_FOLDER,
		'date-format' => "Y-m-d",
		'after-date-text' => ' Referat.pdf'
	],
	'GF_REF' => [
		'short' => 'GF_REF',
		'name' => 'Referat af generalforsamling',
		'has_type' => true,
		'type_options' => ['OGF' => 'Ordinær', 'XGF' => 'Ekstraordinær'],
		'folder' => WORKING_DIR . GF_FOLDER,
		'date-format' => "Y-m-d",
		'after-date-text' => ' %1$s Referat.pdf'
	],
	'aar' => [
		'short' => 'aar',
		'name' => 'Årsrapport',
		'has_type' => false,
		'type_options' => NULL,
		'folder' => WORKING_DIR . ÅRSRAPPORT_FOLDER,
		'date-format' => "Y",
		'after-date-text' => ' Årsrapport.pdf'
	],
	'budget' => [
		'short' => 'budget',
		'name' => 'Vedtaget budget',
		'has_type' => false,
		'type_options' => NULL,
		'folder' => WORKING_DIR . BUDGET_FOLDER,
		'date-format' => "Y",
		'after-date-text' => ' Budget.pdf'
	]
];
