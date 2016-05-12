<?php
namespace App;

use Silex;
use Sorien;

class AppBootstrap
{
    const ENV_DEVELOPMENT = 'development';
    const ENV_PRODUCTION  = 'production';
    const ENV_TESTING     = 'testing';

    /**
     * @var App
     */
    private $_app = null;

    /**
     * @var string
     */
    private $_env = '';

    private $_config = array();

    /**
     * @param $env string
     */
    public function __construct($env) {
        $this->_app = new App();
        $this->_env = strtolower($env);
    }

    private function _getConfigs() {
        $config = array();
        $configPath = sprintf(__DIR__ . '/../../config/{,*.}{global,%1$s,default.local,%1$s.local}.php', $this->_env);
        foreach (glob($configPath, GLOB_BRACE) as $filename) {
            $config = array_replace_recursive($config, require $filename);
        }

        return $config;
    }

    private function _getApplicationConfig() {
        $buildConfig = function($this) {
            return array_merge_recursive(
                $this->_getConfigs()
            );
        };

        if(count($this->_config) == 0) {
            if ($this->_env == self::ENV_PRODUCTION) {
                $cacheDir = __DIR__ . '/../../cache/configs/';
                $cacheFileName = $this->_env . '.config';

                if (file_exists($cacheDir . $cacheFileName)) {
                    $config = unserialize(file_get_contents($cacheDir . $cacheFileName));
                } else {
                    $config = $buildConfig($this);
                    if(!file_exists($cacheDir)) {
                        mkdir($cacheDir);
                    }
                    file_put_contents($cacheDir . $cacheFileName, serialize($config));
                }

                $this->_config = $config;
            } else {
                $this->_config = $buildConfig($this);
            }
        }
        
        return $this->_config;
    }

    private function _registerProvides() {
        $app = $this->_app;

        $app->register(new Silex\Provider\TwigServiceProvider());
        $app->register(new Silex\Provider\UrlGeneratorServiceProvider());
        $app->register(new Silex\Provider\SessionServiceProvider());
        $app->register(new Silex\Provider\ValidatorServiceProvider());
        $app->register(new Silex\Provider\FormServiceProvider());
        $app->register(new Silex\Provider\HttpFragmentServiceProvider());
        $app->register(new Silex\Provider\SwiftmailerServiceProvider());
        $app->register(new Silex\Provider\MonologServiceProvider());
        $app->register(new Silex\Provider\TranslationServiceProvider());
        $app->register(new Silex\Provider\DoctrineServiceProvider());
        $app->register(new Silex\Provider\ServiceControllerServiceProvider());

        if($this->_env == self::ENV_DEVELOPMENT) {
            $app->register(new Silex\Provider\WebProfilerServiceProvider());
            $app->register(new Sorien\Provider\DoctrineProfilerServiceProvider());
            $app->register(new Debug\ServiceProvider());
        }

        // app modules
        $app->register(new Example\ServiceProvider());
        $app->register(new Main\ServiceProvider());
        $app->register(new MessageBus\ServiceProvider());
    }

    private function _setApplicationConfig() {
        $app = $this->_app;
        $configs = $this->_getApplicationConfig();
        foreach ($configs as $key => $value) {
            $app[$key] = $value;
        }
    }

    public function run() {
        $this->_registerProvides();
        $this->_setApplicationConfig();
        
        $this->_app->run();
    }
}