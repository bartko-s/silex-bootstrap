<?php
namespace App\Example;

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
        $app['app.example.mount_prefix'] = '/example';

        $app['twig.path'] = array_merge($app['twig.path'], array(
            'example' => __DIR__ . '/views'
        ));

        $app['example.indexController'] = $app->share(function() use ($app) {
            return new Controller\IndexController($app);
        });
    }

    public function connect(Application $app) {
        $controllers = $this->_getControllerCollectionService($app);

        $controllers->get('/', 'example.indexController:indexAction'
        )->bind('example/index');

        return $controllers;
    }

    public function boot(Application $app) {
        $app->mount($app['app.example.mount_prefix'], $this->connect($app));
    }
}

