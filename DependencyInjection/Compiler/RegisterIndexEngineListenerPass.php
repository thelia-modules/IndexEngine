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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RegisterIndexEngineListenerPass
 * @package IndexEngine\DependencyInjection\Compiler
 * @author Benjamin Perche <benjamin@thelia.net>
 *
 * Based on Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\RegisterKernelListenersPass class
 */
class RegisterIndexEngineListenerPass implements CompilerPassInterface
{
    const LISTENER_TAG_NAME = "index_engine.event_listener";
    const SUBSCRIBER_TAG_NAME = "index_engine.event_subscriber";

    const DISPATCHER_NAME = "index_engine.event_dispatcher";

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(static::DISPATCHER_NAME)) {
            return;
        }

        $dispatcher = $container->getDefinition(static::DISPATCHER_NAME);

        foreach ($container->findTaggedServiceIds(static::LISTENER_TAG_NAME) as $id => $tagsData) {
            foreach ($tagsData as $event) {
                $priority = isset($event['priority']) ? $event['priority'] : 0;

                if (!isset($event['event'])) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "event" attribute on "%s" tags.', $id, static::LISTENER_TAG_NAME));
                }

                if (!isset($event['method'])) {
                    $callback = function ($matches) {
                        return strtoupper($matches[0]);
                    };
                    $event['method'] = 'on'.preg_replace_callback(array(
                            '/(?<=\b)[a-z]/i',
                            '/[^a-z0-9]/i',
                        ), $callback, $event['event']);
                    $event['method'] = preg_replace('/[^a-z0-9]/i', '', $event['method']);
                }

                $dispatcher->addMethodCall('addListenerService', array($event['event'], array($id, $event['method']), $priority));
            }
        }

        foreach ($container->findTaggedServiceIds(static::SUBSCRIBER_TAG_NAME) as $id => $attributes) {
            // We must assume that the class value has been correctly filled, even if the service is created by a factory
            $class = $container->getDefinition($id)->getClass();

            if (0 !== preg_match('/^%([a-z_\-\.]+)%$/i', $class, $match)) {
                $class = $container->getParameter($match[1]);
            }

            $refClass = new \ReflectionClass($class);
            $interface = 'Symfony\Component\EventDispatcher\EventSubscriberInterface';
            if (!$refClass->implementsInterface($interface)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $interface));
            }

            $dispatcher->addMethodCall('addSubscriberService', array($id, $class));
        }
    }
}
