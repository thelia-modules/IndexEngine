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

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(static::REGISTRY_NAME)) {
            return;
        }

        $registry = $container->getDefinition(static::REGISTRY_NAME);

        foreach ($container->findTaggedServiceIds(static::TAG_NAME) as $id => $tag) {
            $registry->addMethodCall("addDriver", [new Reference($id)]);
        }
    }
}