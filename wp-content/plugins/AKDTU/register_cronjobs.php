<?php

/**
 * @var $AKDTU_CRONJOBS Structure containing information about all cronjobs to load.
 * 
 * Structure:
 * 
 * - "cronjobfilename" => array
 * > - "tag" => string
 * > - "function_to_add" => string
 * 
 * Keys: 
 * - "tag": String. Name of the cronjob to add.
 * - "function_to_add": String. Name of the function to run when the cronjob executes.
 * - "cronjobfilename": String. Filename of the file with the cronjob function.
 */
$AKDTU_CRONJOBS = array(
	"Fjern_brugeradgang.php" => array(
		"tag" => "AKDTUcronjob_fjern_brugeradgang",
		"function_to_add" => "send_fjern_brugeradgang",
	),
	"Fjern_lejeradgang.php" => array(
		"tag" => "AKDTUcronjob_fjern_lejeradgang",
		"function_to_add" => "send_fjern_lejeradgang",
	),
	"Opkrævning_fælleshus.php" => array(
		"tag" => "AKDTUcronjob_opkrævning_fælleshus",
		"function_to_add" => "send_opkrævning_fælleshus",
	),
	"Opdater_fælleshus_internet.php" => array(
		"tag" => "AKDTUcronjob_opdater_fælleshus_internet",
		"function_to_add" => "send_opdater_fælleshus_internet",
	),
	"Opkrævning_havedag.php" => array(
		"tag" => "AKDTUcronjob_opkrævning_havedag",
		"function_to_add" => "send_opkrævning_havedag",
	),
);

foreach ($AKDTU_CRONJOBS as $cronjob_file => $cronjob_spec) {
	require_once "cronjobs/" . $cronjob_file;

	add_action($cronjob_spec["tag"], $cronjob_spec["function_to_add"]);
}
