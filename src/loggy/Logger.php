<?php

namespace loggy;

defined('LOGGY_START_MS') or define('LOGGY_START_MS', microtime(true));

const INFO = 0x1;   # information (useful)
const DEBUG = 0x2;  # debug (for developers)
const ERROR = 0x4;  # handleable error
const WARN = 0x8;   # a warning
const FATAL = 0x10; # # fatal!

const ALL = 31;

const DEFAULT_WRITER = 'loggy\writer\DummyWriter';
const DEFAULT_FORMATTER = 'loggy\formatter\SimpleFormatter';

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

		if (is_object($facility)) {
			$facility = get_class($facility);
		}

		return static::getWriter()->createMessageCreator($facility);
	}

	public static function setWriter ($writer = null)
	{
		return static::$instance = $writer;
	}

	public static function getWriter ()
	{
		if (static::$instance) { 
			return static::$instance;
		}

		$class = DEFAULT_WRITER;
		return static::setWriter(new $class);
	}
}
