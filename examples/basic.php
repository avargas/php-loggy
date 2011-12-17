<?php

namespace test;

use loggy;

require_once __DIR__ . '/../src/loggy/Autoloader.php';


$writer = new loggy\writer\CommandLineWriter;
$writer->setFormatter(new loggy\formatter\CommandLineFormatter);

loggy\Logger::setWriter($writer);


# get facility logger space
$logger = loggy\Logger::get();
$logger->debug('debug');
$logger->error('an error');
$logger->warn('warning!');
$logger->info('info');

$logger->fatal('fatal message on %s', basename(__FILE__));