<?php

namespace Bridge\Helpers;

use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;
use Carbon\Carbon;

class Logger extends MonoLogger
{
	public function __construct( $name, $file )
	{
		parent::__construct( $name );
		parent::pushHandler(new StreamHandler( $file, Logger::INFO ));
	}

	public function writeInfo ( $message )
	{
		parent::addInfo( $message );
		echo Carbon::now()." - INFO - $message.".PHP_EOL;
	}
}
