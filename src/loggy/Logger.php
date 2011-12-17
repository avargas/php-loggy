<?php

namespace loggy;

const INFO = 0x1;
const DEBUG = 0x2;
const ERROR = 0x4;
const WARN = 0x8;
const FATAL = 0x10;

const DEFAULT_WRITER = 'loggy\writer\DummyWriter';

var_dump(FATAL);

abstract class Logger
{
	public static $LEVELS = array(
		INFO=>'INFO',
		DEBUG=>'DEBUG',
		ERROR=>'ERROR',
		WARN=>'WARN',
		FATAL=>'FATAL'
	);

	protected static $instance;

	private function __construct ()
	{}

	public static function get ($facility = null)
	{
		if ($facility === null && ($trace = debug_backtrace())) {
			if (isset($trace[0]['class'])) {
				$facility = $trace[0]['class'] . '::' . $trace[0]['function'];
			} else {
				$facility = $trace[0]['function'];
			}
		}

		return static::getInstance()->createMessageCreator($facility);
	}

	public static function factory ($writer = null, $config = null)
	{
		$class = $writer ? : DEFAULT_WRITER;
		return static::$instance = $class::createInstance($config);
	}

	public static function getInstance ()
	{
		if (static::$instance) { 
			return static::$instance;
		}

		return static::factory();
	}
}