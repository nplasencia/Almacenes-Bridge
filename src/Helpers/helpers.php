<?php

namespace Bridge\Helpers;

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
