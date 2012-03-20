<?php

namespace loggy;

define('LOGGY_START_MS', microtime(true));

$vendor = __DIR__ . '/../../vendor/.composer/autoload.php';

if (is_dir($vendor)) {
	require_once $vendor . '/.composer/autoload.php';
}

spl_autoload_register(function ($class) {
	if ($class == 'Colors') {
		$file = realpath(__DIR__ . '/../../vendor/') . '/Bash-Color/colors.class.php';
		goto test;
	}

	if (strpos($class, 'loggy') !== 0) {
		return;
	}

	$file = realpath(__DIR__ . '/../') . '/' . str_replace('\\', '/', $class) . '.php';

	test:
	if (file_exists($file)) {
		require_once $file;
	}
});