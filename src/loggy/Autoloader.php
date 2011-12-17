<?php

namespace loggy;

class AutoloaderException extends \Exception
{}

spl_autoload_register(function ($class) {
	if (strpos($class, 'loggy') !== 0) {
		return;
	}

	$file = realpath(__DIR__ . '/../') . '/' . str_replace('\\', '/', $class) . '.php';

	if (file_exists($file)) {
		require_once $file;
	}
});