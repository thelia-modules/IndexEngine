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

namespace IndexEngine\Manager;


/**
 * Interface ConfigurationManagerInterface
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface ConfigurationManagerInterface
{
    /**
     * @return mixed
     *
     * Get the current page's configuration ID.
     * It can be retrieved from the request.
     */
    public function getCurrentConfigurationId();

    /**
     * @param bool $loadIntoDriver If true, the found configuration will be loaded into the driver
     * @return \IndexEngine\Entity\DriverConfiguration
     *
     * @throws \IndexEngine\Driver\Exception\InvalidDriverCodeException
     * @throws \IndexEngine\Driver\Exception\OutOfBoundsException
     *
     * Retrieve the current configuration object
     */
    public function getCurrentConfiguration($loadIntoDriver = false);
}
