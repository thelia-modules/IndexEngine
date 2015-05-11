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

namespace IndexEngine\Driver\Task\Core;

use IndexEngine\Driver\Configuration\ArgumentCollection;
use IndexEngine\Driver\Configuration\StringArgument;
use IndexEngine\Driver\Task\TaskInterface;
use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;
use IndexEngine\Driver\Task\TaskRegistryInterface;

/**
 * Class UpdateTask
 * @package IndexEngine\Driver\Task\Core
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class UpdateTask implements TaskInterface
{
    /** @var TaskRegistryInterface */
    private $taskRegistry;

    public function __construct(TaskRegistryInterface $taskRegistry)
    {
        $this->taskRegistry = $taskRegistry;
    }

    /**
     * @return mixed
     *
     * This method is executed when the task is called.
     */
    public function run(ArgumentCollectionInterface $parameters)
    {
        return $this->taskRegistry->run(["delete", "create", "update"], $parameters);
    }

    /**
     * @return ArgumentCollectionInterface
     *
     * In this method, you have to return the needed parameters for the run method.
     */
    public function getParameters()
    {
        $collection = new ArgumentCollection();
        $collection->addArgument(new StringArgument("index_configuration_code"));

        return $collection;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return "update";
    }
}
