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

namespace IndexEngine\Driver\Configuration;

use IndexEngine\Driver\AbstractCollection;
use IndexEngine\Driver\Configuration\Exception\ArgumentAlreadyDefinedException;
use IndexEngine\Driver\Exception\InvalidNameException;
use IndexEngine\Exception\InvalidArgumentException;

/**
 * Class ArgumentCollection
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ArgumentCollection extends AbstractCollection implements ArgumentCollectionInterface
{
    private $defaults = array();
    private $collection = array();

    /**
     * Create the instance with defined fields
     */
    public function __construct()
    {
        foreach (func_get_args() as $argument) {
            $this->loadArgument($argument, __METHOD__);
        }
    }

    protected function loadArgument($argument, $method)
    {
        if (is_array($argument)) {
            foreach ($argument as $subArgument) {
                $this->loadArgument($subArgument, $method);
            }
        } elseif ($argument instanceof ArgumentInterface) {
            $this->addArgument($argument);
        } else {
            throw new InvalidArgumentException(sprintf(
                "The parameters given to %s must implement %s",
                $method,
                'IndexEngine\Driver\Configuration\ArgumentInterface'
            ));
        }
    }

    /**
     * @param ArgumentInterface $driver
     * @param int $mode
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Configuration\Exception\ArgumentAlreadyDefinedException
     *
     * This method stores an argument into the collection..
     * You can change its behavior using the $mode parameter, modes are explained in the comments on the top of the constants
     * of CollectionInterface
     */
    public function addArgument(ArgumentInterface $argument, $mode = self::MODE_OVERRIDE)
    {
        $this->checkAddMode($mode, __METHOD__);
        $argumentExists = $this->hasArgument($argument);

        if ($argumentExists) {
            if ($mode === static::MODE_THROW_EXCEPTION_IF_EXISTS) {
                throw new ArgumentAlreadyDefinedException(sprintf("The argument '%s' is defined twice", $argument->getName()));
            }
        }

        if (($argumentExists && $mode !== static::MODE_IGNORE_OVERRIDE) || !$argumentExists) {
            $this->collection[$argument->getName()] = $argument;
        }

        return $this;
    }

    /**
     * @param mixed $argumentNameOrInterface It can be a string, an object that implements __toString or a ArgumentInterface
     * @return bool
     *
     * This method checks if the argument is already defined and return a boolean.
     */
    public function hasArgument($argumentNameOrInterface)
    {
        return isset($this->collection[$this->resolveName($argumentNameOrInterface, __METHOD__)]);
    }

    /**
     * @param $argumentNameOrInterface
     * @param int $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     *
     * This method removes an argument of the collection
     */
    public function deleteArgument($argumentNameOrInterface, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR)
    {
        $this->checkDeleteMode($mode, __METHOD__);
        $resolvedName = $this->resolveName($argumentNameOrInterface, __METHOD__);

        if (!$this->hasArgument($resolvedName)) {
            if ($mode === static::MODE_THROW_EXCEPTION_ON_ERROR) {
                throw new InvalidNameException(sprintf("The argument name '%s' doesn't exist", $resolvedName));
            }

            return false;
        }

        unset ($this->collection[$resolvedName]);

        return true;
    }

    /**
     * @param $name
     * @param int $mode
     * @return false|null|ArgumentInterface
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     *
     * This method return an argument with its name
     */
    public function getArgument($name, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR)
    {
        $this->checkGetMode($mode, __METHOD__);

        if (false === $this->hasArgument($name)) {
            if ($mode === static::MODE_THROW_EXCEPTION_ON_ERROR) {
                throw new InvalidNameException(sprintf("The argument name '%s' doesn't exist", $name));
            } elseif ($mode === static::MODE_RETURN_BOOLEAN) {
                return false;
            } elseif ($mode === static::MODE_RETURN_NULL) {
                return null;
            }
        }

        return $this->collection[$name];
    }

    /**
     * @param ArgumentInterface $driver
     * @param int $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     *
     * This method has to validate the argument name.
     */
    public function isArgumentNameValid(ArgumentInterface $argument, $mode = self::MODE_RETURN_BOOLEAN)
    {
        return $this->isValid($argument->getName(), $mode, __METHOD__);
    }

    /**
     * @return ArgumentInterface[]
     *
     * Dump all the arguments into an array
     */
    public function getArguments()
    {
        return $this->collection;
    }

    /**
     * @return string[]
     *
     * Dump all the argument names into an array
     */
    public function getArgumentNames()
    {
        return array_keys($this->collection);
    }

    /**
     * @param array $defaults
     * @return $this
     *
     * This method has to store arguments defaults and return itself
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @return array
     *
     * This method returns previously defined defaults.
     * If setDefaults has neverBeenCalled, return an empty array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param mixed $argumentNameOrInterface
     * @return string
     *
     * @throws \IndexEngine\Exception\InvalidArgumentException
     *
     * This method transforms the given $codeOrDriver into a proper string.
     */
    protected function resolveName($argumentNameOrInterface, $method)
    {
        if ($argumentNameOrInterface instanceof ArgumentInterface) {
            $argumentNameOrInterface = $argumentNameOrInterface->getName();
        }

        return $this->resolveString($argumentNameOrInterface, $method);
    }

    /**
     * @param array $values
     * @return $this
     *
     * Load the values into the arguments.
     * In the given array, keys are the argument names
     */
    public function loadValues(array $values)
    {
        foreach ($values as $name => $value) {
            $argument = $this->getArgument($name, static::MODE_RETURN_NULL);

            if (null !== $argument) {
                $argument->setValue($value);
            }
        }

        return $this;
    }

    /**
     * @return array
     *
     * This method exports the argument values as an array
     */
    public function exportConfiguration()
    {
        $configuration = [];

        foreach ($this->getArguments() as $name => $argument) {
            $configuration[$name] = $argument->getValue();
        }

        return $configuration;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->collection);
    }
}
