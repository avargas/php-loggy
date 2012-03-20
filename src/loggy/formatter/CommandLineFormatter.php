<?php

namespace loggy\formatter;

use loggy\Message;

use DateTime;
use Colors;
use loggy;

class CommandLineFormatter extends SimpleFormatter
{
	protected $canDoColors;
	protected $colors;
	protected $colorLevelMapping;

	public function __construct ()
	{
		if ($this->canDoColors()) {
			$this->colors = new Colors\Color;
		}

		$this->colorLevelMapping = array(
			'INFO'=>array('green', null),
			'DEBUG'=>array('cyan', null),
			'ERROR'=>array('red', null),
			'WARN'=>array('magenta', null),
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

		$colorLevel = $this->colorLevelMapping;

		if (isset($colorLevel[$level])) {
			$colors = $this->colors;
			$colors = $colors($level);

			$colors->fg($colorLevel[$level][0]);

			# background?
			if ($colorLevel[$level][1]) {
				$colors->highlight($colorLevel[$level][1]);
			}

			return (string)$colors;
		}
	}

	public function canDoColors ()
	{
		if ($this->canDoColors !== null) {
			return $this->canDoColors;
		}

		return $this->canDoColors = class_exists('Colors\Color') && defined('STDOUT') && function_exists('posix_isatty') && posix_isatty(STDIN);
	}
}