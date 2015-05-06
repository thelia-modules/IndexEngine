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

namespace IndexEngine\Entity;

use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;
use IndexEngine\Driver\DriverInterface;

/**
 * Class DriverConfiguration
 * @package IndexEngine\Entity
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class DriverConfiguration
{
    /** @var null|ArgumentCollectionInterface */
    protected $configuration;

    /** @var DriverInterface */
    protected $driver;

    public function __construct(DriverInterface $driver, ArgumentCollectionInterface $configuration = null)
    {
        $this->setConfiguration($configuration);
        $this->setDriver($driver);
    }

    /**
     * @return DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param  DriverInterface $driver
     * @return $this
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return ArgumentCollectionInterface|null
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param  ArgumentCollectionInterface|null $configuration
     * @return $this
     */
    public function setConfiguration(ArgumentCollectionInterface $configuration = null)
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function getDriverArgumentCollection()
    {
        return $this->driver->getConfiguration();
    }
}
