<?php

namespace loggy\formatter;

use loggy\writer\AbstractWriter;
use loggy\Message;

abstract class AbstractFormatter
{
	const DATE_FORMAT = DATE_ATOM;

	public function formatLogMessage ($args)
	{
		$args = is_array($args) ? $args: array($args);
		$argc = count($args);

		if ($argc > 1) {
			# pretty arrays
			foreach ($args as $e=>$v) {
				# print arrays with json_encode
				if (is_array($v)) {
					$args[$e] = json_encode($v);
					continue;
				}

				# print object __toString or class
				if (is_object($v)) {
					if (method_exists($v, '__toString')) {
						$args[$e] = $v->__toString();
					} else {
						$args[$e] = get_class($v);
					}

					continue;
				}

				if (!is_string($v) && !is_numeric($v)) {
					$args[$e] = varstr($v) . '(' . $v . ')';
				}
			}

			return call_user_func_array('sprintf', $args);
		}

		if ($argc > 0) {
			return $args[0];
		}

		return '';
	}

	public function formatLoggerMessages ($messages)
	{
		if ($messages instanceof AbstractWriter) {
			$messages = $messages->getMessages();
		}

		$all = array();

		foreach ($messages as $message) {
			$all[] = $this->format($message);
		}

		return $all;
	}

	public function format (Message $message)
	{
		return $message->getLogMessage();
	}

	public function formatTimestamp (Message $message)
	{
		return $message->getDateTime()->format(static::DATE_FORMAT);
	}

	public function formatLevelName (Message $message)
	{
		return $message->getLevelName();
	}

	public function formatFacility (Message $message)
	{
		return $message->getFacility();
	}

	public function formatMessage (Message $message)
	{
		return $this->formatLogMessage($message->getLogMessage());
	}

	public function formatMemory (Message $m)
	{
		return $mem = number_format(memory_get_usage(true) / 1024 / 1024, 2);
	}

	public function formatRuntime (Message $m)
	{
		return number_format(microtime(true) - LOGGY_START_MS, 2);
	}
}