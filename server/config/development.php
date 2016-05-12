<?php
return array(
    'debug' => true,

    // TwigServiceProvider
    'twig.options' => array(
        'cache' => false,
    ),

    // MonologServiceProvider
    'monolog.logfile' => __DIR__ . '/../logs/development.log',

    //MessageBus
    'messageBus.enable_log' => true,
);