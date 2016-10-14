<?php

namespace Bridge;

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Bridge\Commons\Configuration;
use Bridge\Helpers\CSVReader;
use Bridge\Helpers\Logger;

Helpers\showErrors(Configuration::DEBUG);

$logger = new Logger('BridgeLogger', $_SERVER['DOCUMENT_ROOT'].Configuration::LOG_FILE);
$logger->writeInfo('Program Bridge starts');

$files = Helpers\getFilesFromFolder($_SERVER['DOCUMENT_ROOT'].Configuration::CSVs_FOLDER);

foreach ($files as $file) {
	$logger->writeInfo("Starting to read file $file");

	$file = $_SERVER['DOCUMENT_ROOT'].Configuration::CSVs_FOLDER.$file;
	$csvReader = new CSVReader($file, Configuration::CSV_FIELD_SEPARATOR, $logger);
	$rows = $csvReader->read();

	$logger->writeInfo("End of read file $file");
}
$logger->writeInfo('Program Bridge finished');
$logger->writeInfo('-------------------------------------------------------------------------------------------------');
