<?php

namespace test;

use loggy;

require_once __DIR__ . '/../src/loggy/Autoloader.php';


loggy\Logger::factory();

$logger = loggy\Logger::get();

$logger->debug('debug');
$logger->error('an error');
$logger->warn('warning!');
$logger->info('info');

$logger->fatal('fatal message on %s', basename(__FILE__));

$messages = loggy\formatter\SimpleFormatter::formatLoggerMessages(loggy\Logger::getInstance());

foreach ($messages as $msg) {
	echo $msg . "\n";
}