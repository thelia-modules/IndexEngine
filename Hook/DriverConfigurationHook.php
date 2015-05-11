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

namespace IndexEngine\Hook;

use IndexEngine\Driver\DriverRegistryInterface;
use IndexEngine\Manager\ConfigurationRenderManagerInterface;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Event\Hook\HookRenderEvent;

/**
 * Class DriverConfigurationHook
 * @package IndexEngine\Hook
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class DriverConfigurationHook extends BaseHook
{
    /** @var DriverRegistryInterface */
    private $registry;

    /** @var ConfigurationRenderManagerInterface  */
    private $configurationRenderManager;

    public function __construct(DriverRegistryInterface $registry, ConfigurationRenderManagerInterface $configurationRenderManager)
    {
        $this->registry = $registry;
        $this->configurationRenderManager = $configurationRenderManager;
    }

    /**
     * @param HookRenderEvent $event
     *
     * Add string vector JS
     */
    public function onIndexEngineDriverFormJavascript(HookRenderEvent $event)
    {
        $driverCode = $event->getArgument("driver");
        $driver = $this->registry->getDriver($driverCode, DriverRegistryInterface::MODE_RETURN_NULL);

        if (null !== $driver) {
            $event->add($this->render("form-field/render-string-vector-js.html", [
                "driver_code" => $driverCode,
            ]));
        }
    }

    /**
     * @param HookRenderEvent $event
     *
     * Render all the driver fields
     */
    public function onIndexEngineDriverForm(HookRenderEvent $event)
    {
        $driverCode = $event->getArgument("driver");
        $driver = $this->registry->getDriver($driverCode, DriverRegistryInterface::MODE_RETURN_NULL);

        if (null !== $driver) {
            $configuration = $driver->getConfiguration();

            $content = $this->configurationRenderManager->renderFormFromCollection($configuration, "index_engine_driver_configuration.update", $driverCode);

            $event->add($content);
        }
    }
}
