<?php

namespace loggy\writer;

use loggy;
use loggy\Logger;
use loggy\formatter\AbstractFormatter;
use loggy\MessageCreator;
use loggy\Message;

abstract class AbstractWriter
{
	protected $config;
	protected $formatter;
	protected $messages = array();

	public function __construct ($config = array())
	{
		$this->config = $config;
	}

	public function setConfig ($k, $v = null)
	{
		if (is_array($k)) {
			$this->config = $k;
		} else {
			$this->config[$k] = $v;
		}
		
		return $this;
	}

	public function getConfig ($key = null)
	{
		if ($key === null) {
			return $this->config;
		}

		return isset($this->config[$key]) ? $this->config[$key] : null;
	}

	public function isReportableLevel ($level)
	{
		# check to see if we're filtering out flags
		return !(($only = $this->getConfig('only')) && !($level & $only));
	}

	public function addMessage (Message $message)
	{
		if (!$this->isReportableLevel($message->getLevel())) {
			return false;
		}

		$this->messages[] = $message;
		return true;
	}
	
	public function getMessages ()
	{
		return $this->messages;
	}

	public function setFormatter (AbstractFormatter $formatter)
	{
		$this->formatter = $formatter;
		return $this;
	}

	public function getFormatter ()
	{
		if (!$this->formatter) {
			$class = loggy\DEFAULT_FORMATTER;
			$this->setFormatter(new $class);
		}

		return $this->formatter;
	}

	public function createMessageCreator ($facility = null)
	{
		return new MessageCreator($this, $facility);
	}

	public static function createInstance ($config)
	{
		$obj = new static;

		if ($config) {
			$obj->setConfig($config);
		}

		return $obj;
	}

	public function registerErrorHandler ()
	{
		set_error_handler(function ($no, $str, $file, $line, $context) {
			if (!(error_reporting() & $no)) {
				return;
			}

			$log = Logger::get('PHP Error');

			$assoc = array(
				E_ERROR=>array('E_ERROR', 'error'),
				E_WARNING=>array('E_WARNING', 'warn'),
				E_PARSE=>array('E_PARSE', 'fatal'),
				E_NOTICE=>array('E_NOTICE', 'warn'),
				E_STRICT=>array('E_STRICT', 'warn'),
				E_DEPRECATED=>array('E_DEPRECATED', 'warn'),

				E_CORE_ERROR=>array('E_CORE_NOTICE', 'error'),
				E_CORE_WARNING=>array('E_CORE_WARNING', 'warn'),
				E_COMPILE_ERROR=>array('E_COMPILE_ERROR', 'fatal'),
				
				E_USER_ERROR=>array('E_USER_ERROR', 'error'),
				E_USER_WARNING=>array('E_USER_WARNING', 'warn'),
				E_USER_NOTICE=>array('E_USER_NOTICE', 'warn'),

				E_RECOVERABLE_ERROR=>array('E_RECOVERABLE_ERROR', 'error'),
			);


			if (!isset($assoc[$no])) {
				$format = 'Unknown error type:%d, str:%s, file:%s, line:%d';
				throw new LoggyException(sprintf($format, $no, $str, $file, $line));
			}

			$message = $assoc[$no][0] . ': ' . $str . ' in ' . $file . ' on line ' . $line;
			$log->{$assoc[$no][1]}($message);

			return true;
		});

		return $this;
	}

	public function registerExceptionHandler ()
	{
		set_exception_handler(function ($exception) {
			$log = Logger::get('Exception');
			$str = $exception->getMessage();
			$file = $exception->getFile();
			$line = $exception->getLine();
			$log->fatal($str . ' in ' . $file . ' on line ' . $line);

			return true;
		});

		return $this;
	}
}