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

namespace IndexEngine\Entity;

use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;
use IndexEngine\Driver\Task\TaskInterface;

/**
 * Class TaskConfiguration
 * @package IndexEngine\Entity
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class TaskConfiguration
{
    /** @var TaskInterface */
    private $task;

    /** @var ArgumentCollectionInterface */
    private $configuration;

    function __construct(TaskInterface $task, ArgumentCollectionInterface $configuration = null)
    {
        $this->task = $task;
        $this->configuration = $configuration;
    }


    /**
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param TaskInterface $task
     * @return $this
     */
    public function setTask(TaskInterface $task)
    {
        $this->task = $task;
        return $this;
    }

    /**
     * @return ArgumentCollectionInterface
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param ArgumentCollectionInterface $configuration
     * @return $this
     */
    public function setConfiguration(ArgumentCollectionInterface $configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }
}
