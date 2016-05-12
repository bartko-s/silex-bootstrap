<?php
chdir(dirname(__DIR__));

require_once 'server/vendor/autoload.php';

use \App\AppBootstrap;

if(file_exists(__DIR__ . '/env.php')) {
    require __DIR__ . '/env.php';
}

if(!defined('APPLICATION_ENVIRONMENT')) {
    define('APPLICATION_ENVIRONMENT', AppBootstrap::ENV_PRODUCTION);
}

$app = new AppBootstrap(APPLICATION_ENVIRONMENT);
$app->run();