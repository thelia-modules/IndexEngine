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

/**
 * Interface ArgumentInterface
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface ArgumentInterface
{
    /**
     * Argument type string.
     *
     * The value must be casted as a string.
     * If the given one is an object, check that it implements __toString
     */
    const TYPE_STRING = "string";

    /**
     * Argument type integer.
     *
     * The value must be casted as an integer.
     * If the given one is an object, check that it implements __toString,
     * Then try to cast the string as an integer.
     *
     * Floats are floored
     */
    const TYPE_INTEGER = "integer";

    /**
     * Argument type float.
     *
     * The value must be casted as an integer.
     * If the given one is an object, check that it implements __toString,
     * Then try to cast the string as an float.
     */
    const TYPE_FLOAT = "float";

    /**
     * Argument type boolean.
     *
     * The value must be casted as an integer.
     * If the given one is an object, check that it implements __toString,
     * Then try to cast the string as an bool.
     *
     * If the string contains "true" or an integer != 0, cast it as true.
     * If the string is empty or contains "false" or 0, cast it as false.
     * All the other values leads to an exception
     */
    const TYPE_BOOLEAN = "boolean";

    /**
     * Argument type enum.
     *
     * The given value must be in the choice possibilities
     */
    const TYPE_ENUM = "enum";

    /**
     * @param $value
     * @return $this
     *
     * @throws \IndexEngine\Exception\InvalidArgumentException
     *
     * This method returns the object itself.
     */
    public function setValue($value);

    /**
     * @return mixed
     *
     * This method return the casted value of the argument
     */
    public function getValue();

    /**
     * @return string
     *
     * This method return the argument type.
     * It must be one of the constants that begins with "TYPE_" defined in the interface
     *
     * If the argument is a vector, it must return: Vector<TYPE>
     */
    public function getType();

    /**
     * @return string
     *
     * This method returns the argument name.
     * It must be a unique string, less than 64 characters and only composed of
     * lower and upper case letters, numbers, underscores, dashes and points.
     */
    public function getName();
}
