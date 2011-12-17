<?php

namespace loggy\writer;

use loggy;
use loggy\formatter\AbstractFormatter;
use loggy\MessageCreator;
use loggy\Message;

abstract class AbstractWriter
{
	protected $config;
	protected $formatter;
	protected $messages = array();

	public function setConfig ($config)
	{
		$this->config = $config;
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
}