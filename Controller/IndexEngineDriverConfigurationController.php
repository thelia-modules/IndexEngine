<?php
/**
 * This class has been generated by TheliaStudio
 * For more information, see https://github.com/thelia-modules/TheliaStudio
 */

namespace IndexEngine\Controller;

use IndexEngine\Controller\Base\IndexEngineDriverConfigurationController as BaseIndexEngineDriverConfigurationController;
use IndexEngine\Event\IndexEngineDriverConfigurationEvent;

/**
 * Class IndexEngineDriverConfigurationController
 * @package IndexEngine\Controller
 */
class IndexEngineDriverConfigurationController extends BaseIndexEngineDriverConfigurationController
{
    protected function getCreationEvent($formData)
    {
        $event = new IndexEngineDriverConfigurationEvent();

        $event->setDriverCode($formData["driver_code"]);
        $event->setTitle($formData["title"]);

        return $event;
    }

    /**
     * Creates the update event with the provided form data
     *
     * @param  mixed                          $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getUpdateEvent($formData)
    {
        $event = new IndexEngineDriverConfigurationEvent();

        $event->setId($formData["id"]);
        $event->setTitle($formData["title"]);

        return $event;
    }
}
