<?php
namespace App\Debug;

use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use Silex\Application;
use App\App;


class ServiceProvider
    implements ControllerProviderInterface, ServiceProviderInterface
{
    /**
     * @param App $app
     * @return ControllerCollection
     */
    private function _getControllerCollectionService(App $app) {
        return $app['controllers_factory'];
    }

    public function register(Application $app) {
        $app['app.debug.mount_prefix'] = '/_debug';

        $app['twig.path'] = array_merge($app['twig.path'], array(
            'debug' => __DIR__ . '/views'
        ));

        $app['debug.indexController'] = $app->share(function() use ($app) {
            return new Controller\IndexController($app);
        });
    }

    public function connect(Application $app) {
        $controllers = $this->_getControllerCollectionService($app);

        $controllers->get('/config', 'debug.indexController:configAction'
        )->bind('debug/config');

        return $controllers;
    }

    public function boot(Application $app) {
        $app->mount($app['app.debug.mount_prefix'], $this->connect($app));
    }
}

