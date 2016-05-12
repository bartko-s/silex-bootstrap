<?php
namespace App\Debug\Controller;

use App\App;

class IndexController
{
    private $_app = null;

    public function __construct(App $app) {
        $this->_app = $app;
    }

    public function configAction() {
        $app = $this->_app;
        $config = array();

        foreach($app->keys() as $key) {
            try {
                $config[$key] = $this->_readableConfig($app[$key]);
            } catch (\InvalidArgumentException $e) {
                $config[$key] = '!!! Error !!!';
            }
        }

        return $this->_app->render('debug/config.twig', array(
            'configData' => print_r($config, true),
        ));
    }

    private function _readableConfig($data) {
        if(is_object($data)) {
            return 'Object - ' . get_class($data);
        } elseif (is_array($data)) {
            $arr = array();
            foreach($data as $key => $value) {
                $arr[$key] = $this->_readableConfig($value);
            }
            return $arr;
        } elseif (is_callable($data)) {
            return gettype($data);
        } else {
            return  $data;
        }
    }
}