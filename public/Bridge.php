<?php

namespace Bridge;

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Bridge\Commons\Configuration;
use Bridge\Helpers\CSVReader;
use Bridge\Helpers\CurlConnection;
use Bridge\Helpers\Logger;


Helpers\showErrors(Configuration::DEBUG, E_ALL ^ E_NOTICE);

$logger = new Logger('BridgeLogger', $_SERVER['DOCUMENT_ROOT'].Configuration::LOG_FILE);
$logger->writeInfo('Program Bridge starts');

$files = Helpers\getFilesFromFolder($_SERVER['DOCUMENT_ROOT'].Configuration::CSVs_FOLDER);
$stores = [];

foreach ($files as $fileName) {
	$logger->writeInfo("Starting to read file $fileName");

	$file = $_SERVER['DOCUMENT_ROOT'].Configuration::CSVs_FOLDER.$fileName;
	$csvReader = new CSVReader($file, Configuration::CSV_FIELD_SEPARATOR, $logger);
	if ($fileName != 'MOVIMIENTOS.csv') {
		$rows = $csvReader->read();
	} else {
		$rows = $csvReader->read($stores);
	}
	$logger->writeInfo("End of read file $file");

	if ($fileName == 'EXALMACEN.csv') {
		foreach ( $rows as $row ) {
			$stores[ $row['ALCOD'] ] = $row['ALDESC'];
		}
	} else {
		$connection = new CurlConnection(Configuration::WS_URL, $fileName, $logger);
		$response = $connection->connect($rows);
		var_dump($response);
		$connection->disconnect();
	}
	
}
$logger->writeInfo('Program Bridge finished');
$logger->writeInfo('-------------------------------------------------------------------------------------------------');
