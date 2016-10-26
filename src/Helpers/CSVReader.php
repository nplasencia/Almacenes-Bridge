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

	public function read(array $stores = null)
	{
		$this->logger->writeInfo( "Starting to read file {$this->file}" );

		$csv = utf8_encode(file_get_contents($this->file, FILE_USE_INCLUDE_PATH));
		$lines = explode("\n", $csv);

		// Remove the first element from the array
		$head = str_getcsv(array_shift($lines), $this->fieldSeparator);

		$data = array();
		$i = 1;
		foreach ($lines as $line) {
			$row = str_getcsv( $line, $this->fieldSeparator );
			if (sizeof( $head ) == sizeof( $row )) {
				if ($stores != null) {
					$row[0] = $stores[$row[0]];
				}
				$data[] = array_combine( $head, $row );
			} else {
				$this->logger->writeInfo("There is some problems in the line $i cause the number of fields between the head and row are differents");
			}
			$i++;
		}

		$this->logger->writeInfo( "End of read file {$this->file}" );

		return $data;
	}

}
