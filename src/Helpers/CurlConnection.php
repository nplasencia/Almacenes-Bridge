<?php

namespace Bridge\Helpers;

class CurlConnection
{
	protected $url;
	protected $logger;
	protected $curl;

	public function __construct($urlBase, $file, Logger $logger)
	{
		$this->url    = "$urlBase/$file";
		$this->logger = $logger;
	}

	public function connect(Array $content)
	{
		$this->logger->writeInfo("Connecting to {$this->url}");

		$contentJson = $this->jsonEncode($content);

		$this->curl = curl_init($this->url);
		curl_setopt($this->curl, CURLOPT_HEADER, false);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_POST, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $contentJson);
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($contentJson))
		);

		$json_response = curl_exec($this->curl);

		return json_decode($json_response, true);
	}

	public function getInfo()
	{
		return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
	}

	public function disconnect()
	{
		curl_close($this->curl);
		$this->logger->writeInfo("Disconnected from {$this->url}");
	}

	private function jsonEncode($content) {
		$contentJson = json_encode($content);
		if ($contentJson === false) {
			$this->logger->writeInfo($this->getJsonEncodingProblem('There was a problem encoding the data'));
			return 'Problem encoding...';
		}
		return $contentJson;
	}

	private function getJsonEncodingProblem($msg)
	{
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				$this->logger->writeInfo($msg.' - No errors');
				break;
			case JSON_ERROR_DEPTH:
				$this->logger->writeInfo($msg.' - Maximum stack depth exceeded');
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$this->logger->writeInfo($msg.' - Underflow or the modes mismatch');
				break;
			case JSON_ERROR_CTRL_CHAR:
				$this->logger->writeInfo($msg.' - Unexpected control character found');
				break;
			case JSON_ERROR_SYNTAX:
				$this->logger->writeInfo($msg.' - Syntax error, malformed JSON');
				break;
			case JSON_ERROR_UTF8:
				$this->logger->writeInfo($msg.' - Malformed UTF-8 characters, possibly incorrectly encoded');
				break;
			default:
				$this->logger->writeInfo(' - Unknown error');
				break;
		}
	}

}
