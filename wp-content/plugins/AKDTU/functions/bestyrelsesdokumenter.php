<?php 
$bestyrelsesdocuments_document_types = array(
	'board' => array(
		'short' => 'board',
		'name' => 'Referat af bestyrelsesmøde',
		'has_type' => false,
		'type_options' => NULL,
		'folder' => WORKING_DIR . BESTYRELSE_FOLDER,
		'date-format' => "Y-m-d",
		'after-date-text' => ' Referat.pdf'
	),
	'GF_REF' => array(
		'short' => 'GF_REF',
		'name' => 'Referat af generalforsamling',
		'has_type' => true,
		'type_options' => array('OGF' => 'Ordinær', 'XGF' => 'Ekstraordinær'),
		'folder' => WORKING_DIR . GF_FOLDER,
		'date-format' => "Y-m-d",
		'after-date-text' => ' %1$s Referat.pdf'
	),
	'aar' => array(
		'short' => 'aar',
		'name' => 'Årsrapport',
		'has_type' => false,
		'type_options' => NULL,
		'folder' => WORKING_DIR . ÅRSRAPPORT_FOLDER,
		'date-format' => "Y",
		'after-date-text' => ' Årsrapport.pdf'
	),
	'budget' => array(
		'short' => 'budget',
		'name' => 'Vedtaget budget',
		'has_type' => false,
		'type_options' => NULL,
		'folder' => WORKING_DIR . BUDGET_FOLDER,
		'date-format' => "Y",
		'after-date-text' => ' Budget.pdf'
	)
);
?>