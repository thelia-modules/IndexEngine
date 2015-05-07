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
 * Class RegisterTaskPass
 * @package IndexEngine\DependencyInjection\Compiler
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class RegisterTaskPass implements CompilerPassInterface
{
    const TASK_TAG = "index_engine.task";
    const TASK_REGISTRY = "index_engine.task.registry";


    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(static::TASK_REGISTRY)) {
            return;
        }

        $registry = $container->getDefinition(static::TASK_REGISTRY);

        foreach ($container->findTaggedServiceIds(static::TASK_TAG) as $id => $tag) {
            $registry->addMethodCall("addTask", [new Reference($id)]);
        }
    }
}
