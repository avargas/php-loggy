<?php

namespace loggy\writer;

use loggy\MessageCreator;

abstract class AbstractWriter
{
	protected $config;
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

	public function addMessage ($message)
	{
		# check to see if we're filtering out flags
		if (($only = $this->getConfig('only')) && !($message->getLevel() & $only)) {
			return false;
		}
		
		$this->messages[] = $message;
		return true;
	}
	
	public function getMessages ()
	{
		return $this->messages;
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