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

namespace IndexEngine\Driver;

use IndexEngine\Driver\Exception\UnknownModeException;
use IndexEngine\Exception\InvalidArgumentException;

/**
 * Class AbstractCollection
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class AbstractCollection implements CollectionInterface
{
    /**
     * @var array
     */
    private static $addModes = [
        self::MODE_OVERRIDE,
        self::MODE_IGNORE_OVERRIDE,
        self::MODE_THROW_EXCEPTION_IF_EXISTS,
    ];

    /**
     * @var array
     */
    private static $isValidModes = [
        self::MODE_THROW_EXCEPTION_ON_ERROR,
        self::MODE_RETURN_BOOLEAN,
    ];

    private static $deleteModes = [
        self::MODE_THROW_EXCEPTION_ON_ERROR,
        self::MODE_RETURN_BOOLEAN,
    ];

    private static $getModes = [
        self::MODE_THROW_EXCEPTION_ON_ERROR,
        self::MODE_RETURN_NULL,
        self::MODE_RETURN_BOOLEAN,
    ];

    protected function checkAddMode($mode, $method)
    {
        if (!in_array($mode, self::$addModes)) {
            $this->throwUnknownModeException($mode, $method);
        }
    }

    protected function checkDeleteMode($mode, $method)
    {
        if (!in_array($mode, self::$deleteModes)) {
            $this->throwUnknownModeException($mode, $method);
        }
    }

    protected function checkGetMode($mode, $method)
    {
        if (!in_array($mode, self::$getModes)) {
            $this->throwUnknownModeException($mode, $method);
        }
    }

    /**
     * @param mixed  $currentMode
     * @param string $method
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     *
     * This method is a helper for formatting UnknownModeException
     */
    protected function throwUnknownModeException($currentMode, $method)
    {
        throw new UnknownModeException(sprintf(
            "Invalid mode '%s' given to %s",
            $currentMode,
            $method
        ));
    }

    /**
     * @param  DriverInterface $driver
     * @param  int             $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Exception\InvalidDriverCodeException
     *
     * This method has to validate the driver code.
     */
    protected function isValid($value, $mode = self::MODE_RETURN_BOOLEAN, $method)
    {
        if (!in_array($mode, self::$isValidModes)) {
            $this->throwUnknownModeException($mode, $method);
        }

        return (bool) preg_match("/^[a-z\d\-_\.]{1,64}$/i", $value);
    }

    /**
     * @param  mixed  $codeOrDriver
     * @return string
     *
     * @throws \IndexEngine\Exception\InvalidArgumentException
     *
     * This method transforms the given $codeOrDriver into a proper string.
     */
    protected function resolveString($value, $method)
    {
        if (!is_scalar($value) || (is_object($value) && !method_exists($value, "__toString"))) {
            throw new InvalidArgumentException(sprintf(
                "Invalid argument given to %s, expected a string, %s given",
                $method,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return (string) $value;
    }
}
