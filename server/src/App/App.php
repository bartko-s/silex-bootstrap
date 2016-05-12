<?php
namespace App;

use App\MessageBus\MessageBusTrait;
use Silex\Application as SilexApplication;
use Silex\Application\TwigTrait;
use Silex\Application\UrlGeneratorTrait;
use Silex\Application\MonologTrait;
use Silex\Application\TranslationTrait;
use Silex\Application\FormTrait;


class App
    extends SilexApplication
{
    use TwigTrait;
    use UrlGeneratorTrait;
    use MonologTrait;
    use TranslationTrait;
    use FormTrait;
    use MessageBusTrait;
}