<?php
namespace App\Main;

use Silex\Application;
use Silex\ServiceProviderInterface;


class ServiceProvider
    implements ServiceProviderInterface
{
    public function register(Application $app) {
        $app['twig.path'] = array_merge($app['twig.path'], array(
            'main' => __DIR__ . '/views'
        ));
    }

    public function boot(Application $app) {
    }
}

