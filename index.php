<?php
chdir(dirname(__DIR__));

require_once 'server/vendor/autoload.php';

use App\AppBootstrap;

if(!defined('APPLICATION_ENVIRONMENT')) {
    define('APPLICATION_ENVIRONMENT', AppBootstrap::ENV_PRODUCTION);
}

$app = new AppBootstrap(APPLICATION_ENVIRONMENT);
$app->getConfiguredApplication()->run();