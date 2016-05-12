<?php
return array(
    'debug' => false,

    // WebProfilerServiceProvider
    'profiler.cache_dir' => __DIR__ . '/../cache/profiler',

    // TwigServiceProvider
    'twig.options' => array(
        'cache' => __DIR__ . '/../cache/twig',
    ),

    // MonologServiceProvider
    'monolog.logfile' => __DIR__ . '/../logs/production.log',

    // SessionServiceProvider
    'session.storage.options' => array(
        'cookie_secure' => true,
        'cookie_httponly' => true,
    ),

    // DoctrineServiceProvider

);