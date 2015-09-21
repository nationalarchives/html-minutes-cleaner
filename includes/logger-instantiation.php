<?php
/**
 * Created by PhpStorm.
 * User: gwynjones
 * Date: 17/09/15
 * Time: 13:12
 */

use Monolog\Logger;
use Monolog\Handler\BrowserConsoleHandler;

// Create the logger
$logger = new Logger('TNA logger');

// Now add some handlers
$logger->pushHandler(new BrowserConsoleHandler());

// Message confirming that log is ready for use
$logger->addInfo('Monolog logger ready for use');
