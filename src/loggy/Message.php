<?php

namespace loggy;

use DateTime;

class Message
{
	protected $level;
	protected $facility;
	protected $logMessage;
	protected $datetime;

	public function __construct ($level = null, $facility = null, $logMessage = null, $date = null)
	{
		if ($level) {
			$this->setLevel($level);
		}

		if ($facility) {
			$this->setFacility($facility);
		}

		if ($logMessage) {
			$this->setLogMessage($logMessage);
		}

		$this->setDateTime($date instanceof DateTime ? $date : new DateTime($date ? : null));
	}

	public function setLevel ($level)
	{
		$this->level = $level;
		return $this;
	}

	public function getLevel ()
	{
		return $this->level;
	}

	public function getLevelName ()
	{
		return Logger::$LEVELS[$this->getLevel()];
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

	public function setLogMessage ($logMessage)
	{
		$this->logMessage = is_array($logMessage) ? $logMessage : array($logMessage);
	}

	public function getLogMessage ()
	{
		return $this->logMessage;
	}

	public function setDateTime (DateTime $datetime)
	{
		$this->datetime = $datetime;
		return $this;
	}

	public function getDateTime ()
	{
		return $this->datetime;
	}
}