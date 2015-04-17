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
use IndexEngine\Event\IndexEngineIndexEvent;
use IndexEngine\Event\IndexEngineIndexEvents;
use IndexEngine\Model\IndexEngineIndexQuery;
use Thelia\Core\Event\ToggleVisibilityEvent;

/**
 * Class IndexEngineIndexController
 * @package IndexEngine\Controller\Base
 * @author TheliaStudio
 */
class IndexEngineIndexController extends AbstractCrudController
{
    public function __construct()
    {
        parent::__construct(
            "index_engine_index",
            "id",
            "order",
            AdminResources::MODULE,
            IndexEngineIndexEvents::CREATE,
            IndexEngineIndexEvents::UPDATE,
            IndexEngineIndexEvents::DELETE,
            IndexEngineIndexEvents::TOGGLE_VISIBILITY,
            null,
            "IndexEngine"
        );
    }

    /**
     * Return the creation form for this object
     */
    protected function getCreationForm()
    {
        return $this->createForm("index_engine_index.create");
    }

    /**
     * Return the update form for this object
     */
    protected function getUpdateForm($data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }

        return $this->createForm("index_engine_index.update", "form", $data);
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
            "visible" => (bool) $object->getVisible(),
            "code" => $object->getCode(),
            "title" => $object->getTitle(),
            "entity" => $object->getEntity(),
            "serialized_columns" => $object->getSerializedColumns(),
            "serialized_condition" => $object->getSerializedCondition(),
            "index_engine_driver_configuration_id" => $object->getIndexEngineDriverConfigurationId(),
        );

        return $this->getUpdateForm($data);
    }

    /**
     * Creates the creation event with the provided form data
     *
     * @param mixed $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getCreationEvent($formData)
    {
        $event = new IndexEngineIndexEvent();

        $event->setVisible($formData["visible"]);
        $event->setCode($formData["code"]);
        $event->setTitle($formData["title"]);
        $event->setEntity($formData["entity"]);
        $event->setSerializedColumns($formData["serialized_columns"]);
        $event->setSerializedCondition($formData["serialized_condition"]);
        $event->setIndexEngineDriverConfigurationId($formData["index_engine_driver_configuration_id"]);

        return $event;
    }

    /**
     * Creates the update event with the provided form data
     *
     * @param mixed $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getUpdateEvent($formData)
    {
        $event = new IndexEngineIndexEvent();

        $event->setId($formData["id"]);
        $event->setVisible($formData["visible"]);
        $event->setCode($formData["code"]);
        $event->setTitle($formData["title"]);
        $event->setEntity($formData["entity"]);
        $event->setSerializedColumns($formData["serialized_columns"]);
        $event->setSerializedCondition($formData["serialized_condition"]);
        $event->setIndexEngineDriverConfigurationId($formData["index_engine_driver_configuration_id"]);

        return $event;
    }

    /**
     * Creates the delete event with the provided form data
     */
    protected function getDeleteEvent()
    {
        $event = new IndexEngineIndexEvent();

        $event->setId($this->getRequest()->request->get("index_engine_index_id"));

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
        return $event->getIndexEngineIndex();
    }

    /**
     * Load an existing object from the database
     */
    protected function getExistingObject()
    {
        return IndexEngineIndexQuery::create()
            ->findPk($this->getRequest()->query->get("index_engine_index_id"))
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

        return $this->render("index-engine-indexs");
    }

    /**
     * Render the edition template
     */
    protected function renderEditionTemplate()
    {
        $this->getParserContext()
            ->set(
                "index_engine_index_id",
                $this->getRequest()->query->get("index_engine_index_id")
            )
        ;

        return $this->render("index-engine-index-edit");
    }

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToEditionTemplate()
    {
        $id = $this->getRequest()->query->get("index_engine_index_id");

        return new RedirectResponse(
            URL::getInstance()->absoluteUrl(
                "/admin/module/IndexEngine/index_engine_index/edit",
                [
                    "index_engine_index_id" => $id,
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
            URL::getInstance()->absoluteUrl("/admin/module/IndexEngine/index_engine_index")
        );
    }

    protected function createToggleVisibilityEvent()
    {
        return new ToggleVisibilityEvent($this->getRequest()->query->get("index_engine_index_id"));
    }
}
