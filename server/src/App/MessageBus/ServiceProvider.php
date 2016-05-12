<?php
namespace App\MessageBus;

use Psr\Log\LogLevel;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Logging\LoggingMiddleware;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;


class ServiceProvider
    implements ServiceProviderInterface
{
    public function register(Application $app) {
        $app['messageBus.enable_log'] = false;

        if ($app->offsetExists('messageBus.handlerMap')) {
            $app['messageBus.handlerMap'] = array_merge($app['messageBus.handlerMap'], array());
        } else {
            $app['messageBus.handlerMap'] = array();
        }

        $app['messageBus.commandBus'] = $app->share(function() use ($app) {
            $serviceLocator = function($serviceId) use ($app) {
                return $app[$serviceId];
            };

            $commandHandlerMap = new CallableMap(
                $app['messageBus.handlerMap'],
                new ServiceLocatorAwareCallableResolver($serviceLocator)
            );

            $commandNameResolver = new ClassBasedNameResolver();

            $commandHandlerResolver = new NameBasedMessageHandlerResolver(
                $commandNameResolver,
                $commandHandlerMap
            );

            $commandBus = new MessageBusSupportingMiddleware();
            $commandBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
            $commandBus->appendMiddleware(
                new DelegatesToMessageHandlerMiddleware(
                    $commandHandlerResolver
                )
            );

            if($app['messageBus.enable_log']) {
                $commandBus->appendMiddleware(
                    new LoggingMiddleware($app['monolog'], LogLevel::DEBUG)
                );
            }

            return $commandBus;
        });

        if ($app->offsetExists('messageBus.eventMap')) {
            $app['messageBus.eventMap'] = array_merge($app['messageBus.eventMap'], array());
        } else {
            $app['messageBus.eventMap'] = array();
        }

        $app['messageBus.eventBus'] = $app->share(function() use ($app) {
            $serviceLocator = function ($serviceId) use ($app) {
                return $app[$serviceId];
            };

            $eventSubscriberCollection = new CallableCollection(
                $app['messageBus.eventMap'],
                new ServiceLocatorAwareCallableResolver($serviceLocator)
            );

            $eventNameResolver = new ClassBasedNameResolver();

            $eventSubscribersResolver = new NameBasedMessageSubscriberResolver(
                $eventNameResolver,
                $eventSubscriberCollection
            );

            $eventBus = new MessageBusSupportingMiddleware();
            $eventBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
            $eventBus->appendMiddleware(
                new NotifiesMessageSubscribersMiddleware(
                    $eventSubscribersResolver
                )
            );

            if($app['messageBus.enable_log']) {
                $eventBus->appendMiddleware(
                    new LoggingMiddleware($app['monolog'], LogLevel::DEBUG)
                );
            }

            return $eventBus;
        });
    }

    public function boot(Application $app) {
    }
}

