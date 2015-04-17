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
 * Interface CollectionInterface
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface CollectionInterface
{
    /**
     * You can use this mode with "add" method
     *
     * with this mode, the value is always stored
     */
    const MODE_OVERRIDE = 1;

    /**
     * You can use this mode with "add" method
     *
     * With this mode, an \IndexEngine\Driver\Exception\DriverAlreadyRegisteredException
     * exception should be thrown if the value already exists
     */
    const MODE_THROW_EXCEPTION_IF_EXISTS = 2;

    /**
     * You can use this mode with "add" method
     *
     * With this one, it will add the value to the collection if it doesn't exist,
     * and will be ignored if it already exists.
     */
    const MODE_IGNORE_OVERRIDE = 3;

    /**
     * You can use this mode with "isValid", "delete" or "get" method
     *
     * If the name is not valid (or doesn't exist for "delete" or "get"), an \IndexEngine\Driver\Exception\InvalidNameException
     * exception, or a child class, should be thrown
     */
    const MODE_THROW_EXCEPTION_ON_ERROR = 4;

    /**
     * You can use this mode with "isValid" or "delete" method
     *
     * If the name is not valid (or doesn't exist for "delete"), the method will return false, and true is the name is valid
     */
    const MODE_RETURN_BOOLEAN = 5;

    /**
     * You can use this mode with "get" method
     *
     * If the value doesn't exist, return null
     */
    const MODE_RETURN_NULL = 6;
}
