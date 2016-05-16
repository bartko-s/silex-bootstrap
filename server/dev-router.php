<?php
// dev server router

$request_uri = __DIR__ . '/../' . $_SERVER['REQUEST_URI'];
if (file_exists($request_uri)) {
    return false;
} else {
    include_once __DIR__ . '/vendor/autoload.php';
    define('APPLICATION_ENVIRONMENT', \App\AppBootstrap::ENV_DEVELOPMENT);

    include __DIR__ . '/../index.php';
}