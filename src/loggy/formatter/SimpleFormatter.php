<?php

namespace loggy\formatter;

use loggy\Message;

use DateTime;

class SimpleFormatter extends AbstractFormatter
{
	public function format (Message $message)
	{
		$format = '[%s %s] %s: %s';

		$date = $this->formatTimestamp($message);
		$levelName = $this->formatLevelName($message);
		$facility = $this->formatFacility($message);
		$str = $this->formatMessage($message);
		
		return sprintf($format, $date, $levelName, $facility, $str);
	}
}