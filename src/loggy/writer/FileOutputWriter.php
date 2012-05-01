<?php

namespace loggy\writer;

use loggy\Message;
use loggy\formatter\SimpleFormatter;

class FileOutputWriter extends AbstractWriter
{
	protected $fp;

	public function addMessage (Message $message)
	{
		if (!$this->isReportableLevel($message->getLevel())) {
			return;
		}

		if ($this->fp === null) {
			if (!($file = $this->getConfig('file'))) {
				$this->fp = false;

				return;
			}

			$this->fp = fopen($file, $this->getConfig('mode') ? : 'ab');
		}

		if ($this->fp) {
			fwrite($this->fp, $this->getFormatter()->format($message) . "\n");
		}
	}
}