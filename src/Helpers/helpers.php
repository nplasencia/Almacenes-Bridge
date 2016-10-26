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

if (!function_exists('array_utf8_encode')) {
	/**
	 * Encode array to utf8 recursively
	 *
	 * @param $dat
	 *
	 * @return array|string
	 */
	function array_utf8_encode( $dat ) {
		if ( is_string( $dat ) ) {
			return utf8_encode( $dat );
		}
		if ( ! is_array( $dat ) ) {
			return $dat;
		}
		$ret = array();
		foreach ( $dat as $i => $d ) {
			$ret[ $i ] = array_utf8_encode( $d );
		}

		return $ret;
	}
}
