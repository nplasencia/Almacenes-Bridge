<?php

namespace Bridge;

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Bridge\Commons\Configuration;
use Bridge\Helpers\Logger;

ini_set('memory_limit', '256M');

Helpers\showErrors(Configuration::DEBUG, E_ALL ^ E_NOTICE);

$logger = new Logger('BridgeLogger', $_SERVER['DOCUMENT_ROOT'].Configuration::LOG_FILE);
$logger->writeInfo('Program Bridge starts');

//$files = Helpers\getFilesFromFolder($_SERVER['DOCUMENT_ROOT'].Configuration::CSVs_FOLDER);
$files = ['EXGRUPOS.csv', 'EXSUBGRU.csv', 'EXART.csv'];

foreach ($files as $fileName) {
	$rows = Helpers\readCSVFile($logger, $fileName);
	Helpers\curlConnection($logger, $fileName, $rows, Configuration::SEND_WS_DATA_LIMIT);
}

$rowsStoresFile = Helpers\readCSVFile($logger, 'EXALMACEN.csv');
$stores = null;
foreach ( $rowsStoresFile as $row ) {
	$stores[ $row['ALCOD'] ] = $row['ALDESC'];
}

$rows = Helpers\readCSVFile($logger, 'MOVIMIENTOS.csv', $stores);
$response = Helpers\curlConnection($logger, 'MOVIMIENTOS.csv', $rows, Configuration::SEND_WS_DATA_LIMIT);
var_dump($response);

$logger->writeInfo('Program Bridge finished');
$logger->writeInfo('-------------------------------------------------------------------------------------------------');
