<?php
use \core\Application;

// error_reporting(0);

define('APP_PATH', dirname(__DIR__));

require_once APP_PATH . '/core/Autoload.php';

// Application::sayHi();

Application::register()->run();
