<?php

namespace loggy\writer;

use loggy\Message;
use loggy\formatter\SimpleFormatter;

class CommandLineWriter extends AbstractWriter
{
	public function addMessage (Message $message)
	{
		if (!$this->isReportableLevel($message->getLevel())) {
			return;
		}

		echo $this->getFormatter()->format($message) . "\n";
	}
}