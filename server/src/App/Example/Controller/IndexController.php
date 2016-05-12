<?php
namespace App\Example\Controller;

use App\App;

class IndexController
{
    private $_app = null;

    public function __construct(App $app) {
        $this->_app = $app;
    }

    public function indexAction() {
        $date = new \DateTime();

        $previousRequestTime = $this->_app['session']->get('RequestTime');
        $currentRequestTime = $date->format('Y-m-d H:i:s');
        $this->_app['session']->set('RequestTime', $currentRequestTime);

        return $this->_app->render('example/index.twig', array(
            'session' => array(
                'previousRequestTime' => $previousRequestTime,
            ),
        ));
    }
}