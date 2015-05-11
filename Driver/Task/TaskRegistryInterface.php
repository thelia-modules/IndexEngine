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

namespace IndexEngine\Driver\Task;

use IndexEngine\Driver\CollectionInterface;
use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;

/**
 * Interface TaskRegistryInterface
 * @package IndexEngine\Task\Task
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface TaskRegistryInterface extends CollectionInterface
{
    /**
     * @param  TaskInterface    $task
     * @param  int             $mode
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Task\Exception\TaskAlreadyRegisteredException
     *
     * This method stores a task into the registry.
     * You can change its behavior using the $mode parameter, modes are explained in the comments on the top of the constants
     * of CollectionInterface
     */
    public function addTask(TaskInterface $task, $mode = self::MODE_OVERRIDE);

    /**
     * @param  mixed $codeOrTask It can be a string, an object that implements __toString or a TaskInterface
     * @return bool
     *
     * This method checks if the task is already registered and return a boolean.
     */
    public function hasTask($codeOrTask);

    /**
     * @param $codeOrTask
     * @param  int  $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Task\Exception\InvalidTaskCodeException
     *
     * This method removes a task of the collection
     */
    public function deleteTask($codeOrTask, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR);

    /**
     * @param $name
     * @param  int                        $mode
     * @return false|null|TaskInterface
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     *
     * This method return a task with its name
     */
    public function getTask($code, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR);

    /**
     * @param  TaskInterface   $task
     * @param  int             $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Task\Exception\InvalidTaskCodeException
     *
     * This method has to validate the task code.
     */
    public function isTaskCodeValid(TaskInterface $task, $mode = self::MODE_RETURN_BOOLEAN);

    /**
     * @return TaskInterface[]
     *
     * Dump all the tasks into an array
     */
    public function getTasks();

    /**
     * @return string[]
     *
     * Dump all the task codes into an array
     */
    public function getTaskCodes();

    /**
     * @param string|array|TaskInterface[] $codesOrTasks Collection of strings, or TaskInterface, or both.
     * @param ArgumentCollectionInterface $parameters
     * @return array Tasks outputs
     *
     * Run successively the given tasks
     */
    public function run($codesOrTasks, ArgumentCollectionInterface $parameters);
}
