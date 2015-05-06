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

use IndexEngine\Driver\CollectionInterface;

/**
 * Interface ArgumentCollectionInterface
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface ArgumentCollectionInterface extends CollectionInterface, \Countable
{
    /**
     * @param  ArgumentInterface $argument
     * @param  int               $mode
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Configuration\Exception\ArgumentAlreadyDefinedException
     *
     * This method stores an argument into the collection..
     * You can change its behavior using the $mode parameter, modes are explained in the comments on the top of the constants
     * of CollectionInterface
     */
    public function addArgument(ArgumentInterface $argument, $mode = self::MODE_OVERRIDE);

    /**
     * @param  mixed $argumentNameOrInterface It can be a string, an object that implements __toString or a ArgumentInterface
     * @return bool
     *
     * This method checks if the argument is already defined and return a boolean.
     */
    public function hasArgument($argumentNameOrInterface);

    /**
     * @param $argumentNameOrInterface
     * @param  int  $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     *
     * This method removes an argument of the collection
     */
    public function deleteArgument($argumentNameOrInterface, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR);

    /**
     * @param $name
     * @param  int                          $mode
     * @return false|null|ArgumentInterface
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     *
     * This method return an argument with its name
     */
    public function getArgument($name, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR);

    /**
     * @param  ArgumentInterface $argument
     * @param  int               $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     *
     * This method has to validate the argument name.
     */
    public function isArgumentNameValid(ArgumentInterface $argument, $mode = self::MODE_RETURN_BOOLEAN);

    /**
     * @return ArgumentInterface[]
     *
     * Dump all the arguments into an array
     */
    public function getArguments();

    /**
     * @return string[]
     *
     * Dump all the argument names into an array
     */
    public function getArgumentNames();

    /**
     * @param  array $defaults
     * @return $this
     *
     * This method has to store arguments defaults and return itself
     */
    public function setDefaults(array $defaults);

    /**
     * @return array
     *
     * This method returns previously defined defaults.
     * If setDefaults has neverBeenCalled, return an empty array
     */
    public function getDefaults();

    /**
     * @param  array $values
     * @return $this
     *
     * Load the values into the arguments.
     * In the given array, keys are the argument names
     */
    public function loadValues(array $values);

    /**
     * @return array
     *
     * This method exports the argument values as an array
     */
    public function exportConfiguration();
}
