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

use IndexEngine\Driver\AbstractCollection;
use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;
use IndexEngine\Driver\Exception\InvalidNameException;
use IndexEngine\Driver\Task\Exception\InvalidTaskCodeException;
use IndexEngine\Driver\Task\Exception\TaskAlreadyRegisteredException;

/**
 * Class TaskRegistry
 * @package IndexEngine\Driver\Task
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class TaskRegistry extends AbstractCollection implements TaskRegistryInterface
{
    /**
     * @var array
     */
    private $tasks = array();

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
    public function addTask(TaskInterface $task, $mode = self::MODE_OVERRIDE)
    {
        $this->checkAddMode($mode, __METHOD__);
        $taskExists = $this->hasTask($task);

        if ($taskExists) {
            if ($mode === static::MODE_THROW_EXCEPTION_IF_EXISTS) {
                throw new TaskAlreadyRegisteredException(sprintf("The task '%s' already exists", $task->getCode()));
            }
        }

        if (($taskExists && $mode !== static::MODE_IGNORE_OVERRIDE) || !$taskExists) {
            $this->tasks[$task->getCode()] = $task;
        }

        return $this;
    }

    /**
     * @param  mixed $codeOrTask It can be a string, an object that implements __toString or a TaskInterface
     * @return bool
     *
     * This method checks if the task is already registered and return a boolean.
     */
    public function hasTask($codeOrTask)
    {
        return isset($this->tasks[$this->resolveCode($codeOrTask, __METHOD__)]);
    }

    /**
     * @param $codeOrTask
     * @param  int  $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Task\Exception\InvalidTaskCodeException
     *
     * This method removes a task of the collection
     */
    public function deleteTask($codeOrTask, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR)
    {
        $this->checkDeleteMode($mode, __METHOD__);
        $resolvedCode = $this->resolveCode($codeOrTask, __METHOD__);

        if (!$this->hasTask($resolvedCode)) {
            if ($mode === static::MODE_THROW_EXCEPTION_ON_ERROR) {
                throw new InvalidTaskCodeException(sprintf("The task code '%s' doesn't exist", $resolvedCode));
            }

            return false;
        }

        unset($this->tasks[$resolvedCode]);

        return true;
    }

    /**
     * @param $name
     * @param  int                        $mode
     * @return false|null|TaskInterface
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     *
     * This method return a task with its name
     */
    public function getTask($code, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR)
    {
        $this->checkGetMode($mode, __METHOD__);

        if (false === $this->hasTask($code)) {
            if ($mode === static::MODE_THROW_EXCEPTION_ON_ERROR) {
                throw new InvalidNameException(sprintf("The task code '%s' doesn't exist", $code));
            } elseif ($mode === static::MODE_RETURN_BOOLEAN) {
                return false;
            } elseif ($mode === static::MODE_RETURN_NULL) {
                return;
            }
        }

        return $this->tasks[$code];
    }

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
    public function isTaskCodeValid(TaskInterface $task, $mode = self::MODE_RETURN_BOOLEAN)
    {
        return $this->isValid($task->getCode(), $mode, __METHOD__);
    }

    /**
     * @return TaskInterface[]
     *
     * Dump all the tasks into an array
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @return string[]
     *
     * Dump all the task codes into an array
     */
    public function getTaskCodes()
    {
        return array_keys($this->tasks);
    }

    /**
     * @param  mixed  $codeOrTask
     * @return string
     *
     * @throws \IndexEngine\Exception\InvalidArgumentException
     *
     * This method transforms the given $codeOrTask into a proper string.
     */
    protected function resolveCode($codeOrTask, $method)
    {
        if ($codeOrTask instanceof TaskInterface) {
            $codeOrTask = $codeOrTask->getCode();
        }

        return $this->resolveString($codeOrTask, $method);
    }

    /**
     * @param string|array|TaskInterface[] $codesOrTasks Collection of strings, or TaskInterface, or both.
     * @param ArgumentCollectionInterface $parameters
     *
     * Run successively the given tasks
     */
    public function run($codesOrTasks, ArgumentCollectionInterface $parameters)
    {
        if (!is_array($codesOrTasks)) {
            $codesOrTasks = [$codesOrTasks];
        }

        foreach ($codesOrTasks as $codeOrTask) {
            if ($codeOrTask instanceof TaskInterface) {
                $task = $codeOrTask;
            } else {
                $task = $this->getTask($codeOrTask);
            }

            $task->run($parameters);
        }
    }
}
