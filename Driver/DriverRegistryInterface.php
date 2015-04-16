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


/**
 * Class DriverRegistry
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface DriverRegistryInterface
{
    /**
     * You can use this mode with addDriver method
     *
     * with this mode, the driver is always stored
     */
    const MODE_OVERRIDE = 1;

    /**
     * You can use this mode with addDriver method
     *
     * With this mode, an \IndexEngine\Driver\Exception\DriverAlreadyRegisteredException
     * exception should be thrown if the driver code already exists
     */
    const MODE_THROW_EXCEPTION_IF_EXISTS = 2;

    /**
     * You can use this mode with addDriver method
     *
     * With this one, it will add the driver to the collection if the code doesn't exist,
     * and will be ignored if the code already exists.
     */
    const MODE_IGNORE_OVERRIDE = 3;

    /**
     * You can use this mode with isDriverCodeValid or deleteDriver method
     *
     * If the driver code name is not valid (or doesn't exist for deleteDriver), an \IndexEngine\Driver\Exception\InvalidDriverCodeException
     * exception should be thrown
     */
    const MODE_THROW_EXCEPTION_ON_ERROR = 4;

    /**
     * You can use this mode with isDriverCodeValid or deleteDriver method
     *
     * If the driver code name is not valid (or doesn't exist for deleteDriver), the method will return false, and true is the name is valid
     */
    const MODE_RETURN_BOOLEAN = 5;

    /**
     * @param DriverInterface $driver
     * @param int $mode
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Exception\DriverAlreadyRegisteredException
     *
     * This method stores a driver into the registry.
     * You can change its behavior using the $mode parameter, modes are explained in the comments on the top of the constants
     */
    public function addDriver(DriverInterface $driver, $mode = self::MODE_OVERRIDE);

    /**
     * @param mixed $codeOrDriver It can be a string, an object that implements __toString or a DriverInterface
     * @return bool
     *
     * This method checks if the driver is already registered and return a boolean.
     */
    public function hasDriver($codeOrDriver);

    /**
     * @param $codeOrDriver
     * @param int $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Exception\InvalidDriverCodeException
     *
     * This method removes a driver of the collection
     */
    public function deleteDriver($codeOrDriver, $mode = self::MODE_THROW_EXCEPTION_ON_ERROR);

    /**
     * @param DriverInterface $driver
     * @param int $mode
     * @return bool
     *
     * @throws \IndexEngine\Driver\Exception\UnknownModeException
     * @throws \IndexEngine\Driver\Exception\InvalidDriverCodeException
     *
     * This method has to validate the driver code.
     */
    public function isDriverCodeValid(DriverInterface $driver, $mode = self::MODE_RETURN_BOOLEAN);

    /**
     * @return array
     *
     * Dump all the drivers into an array
     */
    public function getDrivers();

    /**
     * @return string[]
     *
     * Dump all the driver codes into an array
     */
    public function getDriverCodes();
}