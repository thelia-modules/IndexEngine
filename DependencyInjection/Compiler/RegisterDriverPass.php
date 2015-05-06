<?php
/*************************************************************************************/
/* This file is part of the Thelia package.                                          */
/*                                                                                   */
/* Copyright (c) OpenStudio                                                          */
/* email : dev@thelia.net                                                            */
/* web : http://www.thelia.net                                                       */
/*                                                                                   */
/* For the full copyright and license information, please view the LICENSE.txt       */
/* file that was distributed with this source code.                                  */
/*************************************************************************************/

namespace IndexEngine\DependencyInjection\Compiler;

use IndexEngine\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RegisterDriverPass
 * @package IndexEngine\DependencyInjection\Compiler
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class RegisterDriverPass implements CompilerPassInterface
{
    const TAG_NAME = "index_engine.driver";
    const REGISTRY_NAME = "index_engine.driver.registry";

    const EVENT_DISPATCHER_NAME = "index_engine.event_dispatcher";
    const LISTENER_TAG_PARAMETER = "listener";

    const DRIVER_LISTENER_INTERFACE = "IndexEngine\\Driver\\DriverEventSubscriberInterface";

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(static::REGISTRY_NAME) || !$container->hasDefinition(static::EVENT_DISPATCHER_NAME)) {
            return;
        }

        $registry = $container->getDefinition(static::REGISTRY_NAME);
        $dispatcherReference = new Reference(static::EVENT_DISPATCHER_NAME);
        $parameterBag = $container->getParameterBag();

        foreach ($container->findTaggedServiceIds(static::TAG_NAME) as $id => $tags) {
            $registry->addMethodCall("addDriver", [new Reference($id)]);

            // Inject the dispatcher in the driver
            $driver = $container->getDefinition($id);
            $driver->addMethodCall("setDispatcher", [$dispatcherReference]);

            foreach ($tags as $tag) {
                if (isset($tag[static::LISTENER_TAG_PARAMETER])) {
                    $listenerCode = $parameterBag->resolveValue($tag[static::LISTENER_TAG_PARAMETER]);

                    if (!$container->hasDefinition($listenerCode)) {
                        throw new ServiceNotFoundException(sprintf("The service '%s' doesn't exist", $listenerCode));
                    }

                    $listener = $container->getDefinition($listenerCode);
                    $listenerClass = $parameterBag->resolveValue($listener->getClass());

                    $reflection = new \ReflectionClass($listenerClass);

                    if (false === $reflection->implementsInterface(static::DRIVER_LISTENER_INTERFACE)) {
                        throw new InvalidArgumentException(sprintf(
                            "The service '%s' must implement interface '%s' but doesn't",
                            $listenerCode,
                            static::DRIVER_LISTENER_INTERFACE
                        ));
                    }

                    $listener->addMethodCall("setDriver", [new Reference($id)]);
                }
            }
        }
    }
}
