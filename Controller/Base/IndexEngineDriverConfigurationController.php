<?php
/**
 * This class has been generated by TheliaStudio
 * For more information, see https://github.com/thelia-modules/TheliaStudio
 */

namespace IndexEngine\Controller\Base;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\AbstractCrudController;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Tools\URL;
use IndexEngine\Event\IndexEngineDriverConfigurationEvent;
use IndexEngine\Event\IndexEngineDriverConfigurationEvents;
use IndexEngine\Model\IndexEngineDriverConfigurationQuery;

/**
 * Class IndexEngineDriverConfigurationController
 * @package IndexEngine\Controller\Base
 * @author TheliaStudio
 */
class IndexEngineDriverConfigurationController extends AbstractCrudController
{
    public function __construct()
    {
        parent::__construct(
            "index_engine_driver_configuration",
            "id",
            "order",
            AdminResources::MODULE,
            IndexEngineDriverConfigurationEvents::CREATE,
            IndexEngineDriverConfigurationEvents::UPDATE,
            IndexEngineDriverConfigurationEvents::DELETE,
            null,
            null,
            "IndexEngine"
        );
    }

    /**
     * Return the creation form for this object
     */
    protected function getCreationForm()
    {
        return $this->createForm("index_engine_driver_configuration.create");
    }

    /**
     * Return the update form for this object
     */
    protected function getUpdateForm($data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }

        return $this->createForm("index_engine_driver_configuration.update", "form", $data);
    }

    /**
     * Hydrate the update form for this object, before passing it to the update template
     *
     * @param mixed $object
     */
    protected function hydrateObjectForm($object)
    {
        $data = array(
            "id" => $object->getId(),
            "code" => $object->getCode(),
            "driver_code" => $object->getDriverCode(),
            "title" => $object->getTitle(),
            "serialized_configuration" => $object->getSerializedConfiguration(),
        );

        return $this->getUpdateForm($data);
    }

    /**
     * Creates the creation event with the provided form data
     *
     * @param  mixed                          $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getCreationEvent($formData)
    {
        $event = new IndexEngineDriverConfigurationEvent();

        $event->setCode($formData["code"]);
        $event->setDriverCode($formData["driver_code"]);
        $event->setTitle($formData["title"]);
        $event->setSerializedConfiguration($formData["serialized_configuration"]);

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
        $event->setCode($formData["code"]);
        $event->setDriverCode($formData["driver_code"]);
        $event->setTitle($formData["title"]);
        $event->setSerializedConfiguration($formData["serialized_configuration"]);

        return $event;
    }

    /**
     * Creates the delete event with the provided form data
     */
    protected function getDeleteEvent()
    {
        $event = new IndexEngineDriverConfigurationEvent();

        $event->setId($this->getRequest()->request->get("index_engine_driver_configuration_id"));

        return $event;
    }

    /**
     * Return true if the event contains the object, e.g. the action has updated the object in the event.
     *
     * @param mixed $event
     */
    protected function eventContainsObject($event)
    {
        return null !== $this->getObjectFromEvent($event);
    }

    /**
     * Get the created object from an event.
     *
     * @param mixed $event
     */
    protected function getObjectFromEvent($event)
    {
        return $event->getIndexEngineDriverConfiguration();
    }

    /**
     * Load an existing object from the database
     */
    protected function getExistingObject()
    {
        return IndexEngineDriverConfigurationQuery::create()
            ->findPk($this->getRequest()->query->get("index_engine_driver_configuration_id"))
        ;
    }

    /**
     * Returns the object label form the object event (name, title, etc.)
     *
     * @param mixed $object
     */
    protected function getObjectLabel($object)
    {
        return $object->getTitle();
    }

    /**
     * Returns the object ID from the object
     *
     * @param mixed $object
     */
    protected function getObjectId($object)
    {
        return $object->getId();
    }

    /**
     * Render the main list template
     *
     * @param mixed $currentOrder , if any, null otherwise.
     */
    protected function renderListTemplate($currentOrder)
    {
        $this->getParser()
            ->assign("order", $currentOrder)
        ;

        return $this->render("index-engine-driver-configurations");
    }

    /**
     * Render the edition template
     */
    protected function renderEditionTemplate()
    {
        $this->getParserContext()
            ->set(
                "index_engine_driver_configuration_id",
                $this->getRequest()->query->get("index_engine_driver_configuration_id")
            )
        ;

        return $this->render("index-engine-driver-configuration-edit");
    }

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToEditionTemplate()
    {
        $id = $this->getRequest()->query->get("index_engine_driver_configuration_id");

        return new RedirectResponse(
            URL::getInstance()->absoluteUrl(
                "/admin/module/IndexEngine/index_engine_driver_configuration/edit",
                [
                    "index_engine_driver_configuration_id" => $id,
                ]
            )
        );
    }

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToListTemplate()
    {
        return new RedirectResponse(
            URL::getInstance()->absoluteUrl("/admin/module/IndexEngine/index_engine_driver_configuration")
        );
    }
}
