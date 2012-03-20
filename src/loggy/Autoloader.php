<?php

namespace loggy;

define('LOGGY_START_MS', microtime(true));

$vendor = __DIR__ . '/../../vendor/.composer/autoload.php';

if (file_exists($vendor)) {
	require_once $vendor;
}