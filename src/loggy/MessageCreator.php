<?php

namespace loggy;

class MessageCreator
{
	protected $facility;
	protected $writer;
	protected $messages = array();

	public function __construct ($writer, $facility = null)
	{
		if ($writer) {
			$this->setWriter($writer);
		}

		if ($facility) {
			$this->setfacility($facility);
		}
	}

	public function setWriter ($writer)
	{
		$this->writer = $writer;
		return $this;
	}

	public function getWriter ()
	{
		return $this->writer;
	}

	public function setFacility ($facility)
	{
		$this->facility = $facility;
		return $this;
	}

	public function getFacility ()
	{
		return $this->facility;
	}

	public function info ()
	{
		return $this->log(INFO, func_get_args());
	}

	public function debug ()
	{
		return $this->log(DEBUG, func_get_args());
	}

	public function error ()
	{
		return $this->log(ERROR, func_get_args());
	}

	public function warn ()
	{
		return $this->log(WARN, func_get_args());
	}

	public function fatal ()
	{
		return $this->log(FATAL, func_get_args());
	}

	public function log ($level, $messageArgs)
	{
		return $this->getWriter()->addMessage(new Message($level, $this->getFacility(), $messageArgs));
	}
}