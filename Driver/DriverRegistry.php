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

use IndexEngine\Driver\Exception\DriverAlreadyRegisteredException;
use IndexEngine\Driver\Exception\InvalidDriverCodeException;
use IndexEngine\Driver\Exception\InvalidNameException;
use IndexEngine\Driver\Exception\UnknownModeException;
use IndexEngine\Exception\InvalidArgumentException;

/**
 * Class DriverRegistry
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class DriverRegistry extends AbstractCollection implements DriverRegistryInterface
{
    /**
     * @var array
     */
    private $drivers = array();

    /**
     * @param  DriverInterface $driver
     * @param  int             $mode
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Exception\DriverAlreadyRegisteredException
     *
     * This method stores a driver into the registry.
     * You can change its behavior using the $mode parameter, modes are explained in the comments on the top of the constants
     */
    public function addDriver(DriverInterface $driver, $mode = self::MODE_OVERRIDE)
    {
        $this->checkAddMode($mode, __METHOD__);
        $driverExists = $this->hasDriver($driver);

        if ($driverExists) {
            if ($mode === static::MODE_THROW_EXCEPTION_IF_EXISTS) {
                throw new DriverAlreadyRegisteredException(sprintf("The driver '%s' already exists", $driver->getCode()));
            }
        }

        if (($driverExists && $mode !== static::MODE_IGNORE_OVERRIDE) || !$driverExists) {
            $this->drivers[$driver->getCode()] = $driver;
        }

        return $this;
    }

    /**
     * @param  mixed $codeOrDriver It can be a string, an object that implements __toString or a DriverInterface
     * @return bool
     *
     * This method checks if the driver is already registered and return a boolean.
     */
    public function hasDriver($codeOrDriver)
    {
        return isset($this->drivers[$this->resolveCode($codeOrDriver, __METHOD__)]);
    }

    /**
     * @param $codeOrDriver
     * @param  int  $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Exception\InvalidDriverCodeException
     *
     * This method removes a driver of the collection
     */
    public function deleteDriver($codeOrDriver, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR)
    {
        $this->checkDeleteMode($mode, __METHOD__);
        $resolvedCode = $this->resolveCode($codeOrDriver, __METHOD__);

        if (!$this->hasDriver($resolvedCode)) {
            if ($mode === static::MODE_THROW_EXCEPTION_ON_ERROR) {
                throw new InvalidDriverCodeException(sprintf("The driver code '%s' doesn't exist", $resolvedCode));
            }

            return false;
        }

        unset($this->drivers[$resolvedCode]);

        return true;
    }

    /**
     * @param $name
     * @param  int                        $mode
     * @return false|null|DriverInterface
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     *
     * This method return a driver with its name
     */
    public function getDriver($code, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR)
    {
        $this->checkGetMode($mode, __METHOD__);

        if (false === $this->hasDriver($code)) {
            if ($mode === static::MODE_THROW_EXCEPTION_ON_ERROR) {
                throw new InvalidNameException(sprintf("The driver code '%s' doesn't exist", $code));
            } elseif ($mode === static::MODE_RETURN_BOOLEAN) {
                return false;
            } elseif ($mode === static::MODE_RETURN_NULL) {
                return;
            }
        }

        return $this->drivers[$code];
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
    public function isDriverCodeValid(DriverInterface $driver, $mode = self::MODE_RETURN_BOOLEAN)
    {
        return $this->isValid($driver->getCode(), $mode, __METHOD__);
    }

    /**
     * @return DriverInterface[]
     *
     * Dump all the drivers into an array
     */
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * @return string[]
     *
     * Dump all the driver codes into an array
     */
    public function getDriverCodes()
    {
        return array_keys($this->drivers);
    }

    /**
     * @param  mixed  $codeOrDriver
     * @return string
     *
     * @throws \IndexEngine\Exception\InvalidArgumentException
     *
     * This method transforms the given $codeOrDriver into a proper string.
     */
    protected function resolveCode($codeOrDriver, $method)
    {
        if ($codeOrDriver instanceof DriverInterface) {
            $codeOrDriver = $codeOrDriver->getCode();
        }

        return $this->resolveString($codeOrDriver, $method);
    }
}
