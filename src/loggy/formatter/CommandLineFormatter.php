<?php

namespace loggy\formatter;

use loggy\Message;

use DateTime;
use Colors;
use loggy;

class CommandLineFormatter extends SimpleFormatter
{
	protected $colors;
	protected $colorLevelMapping;

	public function __construct ()
	{
		if ($this->canDoColors()) {
			$this->colors = new Colors;
		}

		$this->colorLevelMapping = array(
			'INFO'=>array('green', null),
			'DEBUG'=>array('cyan', null),
			'ERROR'=>array('red', null),
			'WARN'=>array('purple', null),
			'FATAL'=>array('red', null)
		);
	}

	public function format (Message $message)
	{
		# date - sec - memory - level - facility - str
		$format = '[%s - %s - %smb - %s] %s: %s';

		$date = $this->formatTimestamp($message);
		$levelName = $this->formatLevelName($message);
		$facility = $this->formatFacility($message);
		$str = $this->formatMessage($message);

		$time = $this->formatRunTime($message);
		$mem = $this->formatMemory($message);

		return sprintf($format, $date, $time, $mem, $levelName, $facility, $str);;
	}

	public function formatLevelName (Message $message)
	{
		$level = $message->getLevelName();

		if (!$this->canDoColors()) {
			return $level;
		}

		$colorLevel = &$this->colorLevelMapping;

		if (isset($colorLevel[$level])) {
			return $this->colors->colorize($level, $colorLevel[$level][0], $colorLevel[$level][1]);
		}
	}

	public function canDoColors ()
	{
		return class_exists('\Colors') || (function_exists('posix_isatty') && posix_isatty());
	}
}