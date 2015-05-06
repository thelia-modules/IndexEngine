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

use IndexEngine\Driver\Configuration\ParserAwareArgumentInterface;
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
            $i = 0;

            $content = "";

            /** @var \IndexEngine\Driver\Configuration\ArgumentInterface $argument */
            foreach ($configuration->getArguments() as $argument) {
                if ($argument instanceof ParserAwareArgumentInterface) {
                    $argument->setParser($this->parser);
                }

                $formattedTitle = $this->formatTitle($argument->getName());

                $content .= $this->render("form-field/render-form-field.html", [
                    "driver_code" => $driverCode,
                    "form_name" => "index_engine_driver_configuration.update",
                    "form_field" => $argument->getName(),
                    "argument" => $argument,
                    "is_vector" => $argument instanceof VectorArgumentInterface,
                    "is_view_builder" => $argument instanceof ViewBuilderInterface,
                    "is_field_count_even" => $i % 2 === 0,
                    "field_count" => $i++,
                    "formatted_title" => $formattedTitle,
                ]);
            }

            $event->add($content);
        }
    }

    protected function formatTitle($name)
    {
        return preg_replace("/[_\.\-]/", " ", ucfirst($name));
    }
}
