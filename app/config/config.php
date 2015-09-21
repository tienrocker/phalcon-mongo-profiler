<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

// Config file by environment
$configFile = (defined('ENV') && ENV == 'development') ? APP_PATH . '/app/config/development/config.ini' : APP_PATH . '/app/config/production/config.ini';

// Create the new object
return new \Phalcon\Config\Adapter\Ini($configFile);