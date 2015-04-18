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

use IndexEngine\Driver\Configuration\VectorArgumentInterface;
use IndexEngine\Driver\Configuration\ViewBuilderInterface;
use IndexEngine\Driver\DriverRegistryInterface;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Event\Hook\HookRenderEvent;

/**
 * Class DriverConfigurationHook
 * @package IndexEngine\Hook
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class DriverConfigurationHook extends BaseHook
{
    private $registry;

    public function __construct(DriverRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function onIndexEngineDriverForm(HookRenderEvent $event)
    {
        $driverCode = $event->getArgument("driver");
        $driver = $this->registry->getDriver($driverCode, DriverRegistryInterface::MODE_RETURN_NULL);

        if (null !== $driver) {
            $configuration = $driver->getConfiguration();
            $i = 0;

            /** @var \IndexEngine\Driver\Configuration\ArgumentInterface $argument */
            foreach ($configuration->getArguments() as $argument) {
                $this->render("form-field/render-form-field.html", [
                    "form_name" => "index_engine_driver_configuration.update",
                    "form_field" => $argument->getName(),
                    "argument" => $argument,
                    "is_vector" => $argument instanceof VectorArgumentInterface,
                    "is_view_builder" => $argument instanceof ViewBuilderInterface,
                    "field_count" => $i++,
                ]);
            }
        }
    }
}
