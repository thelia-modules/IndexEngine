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
interface DriverRegistryInterface extends CollectionInterface
{
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
     * of CollectionInterface
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