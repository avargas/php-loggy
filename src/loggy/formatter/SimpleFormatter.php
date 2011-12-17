<?php

namespace loggy\formatter;

use loggy\Message;

use DateTime;

class SimpleFormatter extends AbstractFormatter
{
	const DATE_FORMAT = 'Y-m-d D H:i:s O';

	public static function format (Message $message)
	{
		$format = '[%s - %s] %s';

		$date = $message->getDateTime()->format(static::DATE_FORMAT);
		$levelName = $message->getLevelName();
		$str = static::formatLogMessage($message->getLogMessage());

		return sprintf($format, $date, $levelName, $str);
	}
}