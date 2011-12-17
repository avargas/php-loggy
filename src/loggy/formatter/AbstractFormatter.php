<?php

namespace loggy\formatter;

use loggy\writer\AbstractWriter;
use loggy\Message;

abstract class AbstractFormatter
{
	public static function formatLogMessage ($args)
	{
		$args = is_array($args) ? $args: array($args);
		$argc = count($args);

		if ($argc > 1) {
			return call_user_func_array('sprintf', $args);
		}

		if ($argc > 0) {
			return $args[0];
		}

		return '';
	}

	public static function formatLoggerMessages ($messages)
	{
		if ($messages instanceof AbstractWriter) {
			$messages = $messages->getMessages();
		}

		$all = array();

		foreach ($messages as $message) {
			$all[] = static::format($message);
		}

		return $all;
	}

	public static function format (Message $message)
	{
		return $message->getLogMessage();
	}
}