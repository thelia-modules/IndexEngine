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

use IndexEngine\Driver\DriverRegistryInterface;
use IndexEngine\Driver\Exception\OutOfBoundsException;
use IndexEngine\Entity\DriverConfiguration;
use IndexEngine\Model\IndexEngineDriverConfigurationQuery;
use Thelia\Core\HttpFoundation\Request;

/**
 * Class ConfigurationManager
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ConfigurationManager implements ConfigurationManagerInterface
{
    const CONFIGURATION_ID_QUERY_PARAMETER = "index_engine_driver_configuration_id";

    /** @var Request */
    private $request;

    /** @var DriverRegistryInterface */
    private $driverRegistry;

    public function __construct(Request $request, DriverRegistryInterface $driverRegistry)
    {
        $this->request = $request;
        $this->driverRegistry = $driverRegistry;
    }

    /**
     * @return mixed
     *
     * Get the current page's configuration ID.
     * It can be retrieved from the request.
     */
    public function getCurrentConfigurationId()
    {
        return $this->request->query->get(static::CONFIGURATION_ID_QUERY_PARAMETER);
    }

    /**
     * @param bool $loadIntoDriver If true, the found configuration will be loaded into the driver
     * @return \IndexEngine\Entity\DriverConfiguration
     *
     * @throws \IndexEngine\Driver\Exception\InvalidDriverCodeException
     * @throws \IndexEngine\Driver\Exception\OutOfBoundsException
     *
     * Retrieve the current configuration object
     */
    public function getCurrentConfiguration($loadIntoDriver = false)
    {
        $configurationId = $this->getCurrentConfigurationId();
        $dbConfiguration = IndexEngineDriverConfigurationQuery::create()->findPk($configurationId);

        if (null === $dbConfiguration) {
            throw new OutOfBoundsException(sprintf("The driver configuration id '%s' doesn't exist", $configurationId));
        }

        $driver = $this->driverRegistry->getDriver($dbConfiguration->getDriverCode());

        $configuration = $driver->getConfiguration();

        if (null !== $configuration) {
            $storedConfiguration = $dbConfiguration->getConfiguration();
            $configuration->loadValues($storedConfiguration);

            if (true === $loadIntoDriver) {
                $driver->loadConfiguration($configuration);
            }
        }

        return new DriverConfiguration($driver, $configuration);
    }
}
