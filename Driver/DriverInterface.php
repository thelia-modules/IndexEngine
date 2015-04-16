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
 * Interface DriverInterface
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface DriverInterface
{
    /**
     * @return \IndexEngine\Driver\Configuration\ArgumentCollectionInterface
     *
     * This method is used to build the driver configuration form.
     * Set all your the configuration fields you need here (server address, port, authentication, ...)
     */
    public function getConfiguration();

    /**
     * @return string
     *
     * This method returns the driver name.
     * It must be a unique string, less than 64 characters and only composed of
     * lower and upper case letters, numbers, underscores, dashes and points.
     *
     * Example: Elasticsearch, OpenSearchServer, ...
     */
    public function getCode();
}
