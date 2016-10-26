<?php

namespace Bridge\Helpers;

use Bridge\Commons\Configuration;

if (!function_exists('showErrors')) {

	function showErrors( $show = false, $reports = E_ALL )
	{
		if ($show) {
			error_reporting($reports);
			ini_set('display_errors', 1);
		}
	};
}

if (!function_exists('getFilesFromFolder')) {

	function getFilesFromFolder($folder)
	{
		$filesNotFiltered = scandir(realpath($folder));

		$files = array_filter($filesNotFiltered, function($item) {
			return $item[0] !== '.';
		});

		return $files;
	};
}

if (!function_exists('readCSVFile')) {

	function readCSVFile( Logger $logger, $fileName, Array $stores = null )
	{
		$file      = $_SERVER['DOCUMENT_ROOT'] . Configuration::CSVs_FOLDER . $fileName;
		$csvReader = new CSVReader( $file, Configuration::CSV_FIELD_SEPARATOR, $logger );
		$rows      = $csvReader->read( $stores );

		return $rows;
	}
}

if (!function_exists('curlConnection')) {

	function curlConnection ( Logger $logger, $fileName, $data, $limit = 1000)
	{
		$response = null;
		$dataCount = sizeof($data);
		$j = 0;
		for ($i=0; $i < $dataCount; $i++) {
			$subData [] = $data[$i];
			$j++;
			if ($j==$limit || $i == ($dataCount-1)) {
				$connection = new CurlConnection(Configuration::WS_URL, $fileName, $logger);
				$response = $connection->connect($subData);
				$connection->disconnect();

				var_dump($response);
				$j=0;
				$subData = [];
			}
		}

		return $response;
	}

}
