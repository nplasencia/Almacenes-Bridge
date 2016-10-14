<?php

namespace Bridge\Helpers;

class CSVReader
{
	protected $fieldSeparator;
	protected $file;
	protected $logger;

	public function __construct($file, $fieldSeparator = ';', Logger $logger)
	{
		$this->fieldSeparator = $fieldSeparator;
		$this->file           = $file;
		$this->logger         = $logger;
	}

	public function read()
	{
		$csv = file_get_contents($this->file, FILE_USE_INCLUDE_PATH);
		$lines = explode("\n", $csv);

		// Remove the first element from the array
		$head = str_getcsv(array_shift($lines));

		$data = array();
		$i = 1;
		foreach ($lines as $line) {
			$row = str_getcsv( $line );
			if (sizeof( $head ) == sizeof( $row )) {
				$data[] = array_combine( $head, $row );
			} else {
				$this->logger->writeInfo("There is some problems in the line $i cause the number of fields between the head and row are differents");
			}
			$i++;
		}

		return $data;
	}

}
