<?php
namespace AppTest;

use App\AppBootstrap;
use Silex\WebTestCase;

class AppWebTestCase
    extends WebTestCase
{

    public function createApplication()
    {
        $app = new AppBootstrap(AppBootstrap::ENV_TESTING);
        $app = $app->getConfiguredApplication();
        unset($app['exception_handler']);
        return $app;
    }
}