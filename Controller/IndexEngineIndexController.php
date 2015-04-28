<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine\Controller;

use IndexEngine\Controller\Base\IndexEngineIndexController as BaseIndexEngineIndexController;
use IndexEngine\Event\IndexEngineIndexEvent;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;

/**
 * Class IndexEngineIndexController
 * @package IndexEngine\Controller
 */
class IndexEngineIndexController extends BaseIndexEngineIndexController
{
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
        $event->setCode($formData["code"]);
        $event->setTitle($formData["title"]);
        $event->setType($formData["type"]);
        $event->setEntity($formData["entity"]);
        $event->setIndexEngineDriverConfigurationId($formData["index_engine_driver_configuration_id"]);
        $event->setConditions($formData["conditions"]);
        $event->setColumns($formData["columns"]);

        return $event;
    }

    public function renderIndexConfigurationAction($type)
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, "IndexEngine", AccessManager::VIEW)) {
            if ($this->getRequest()->isXmlHttpRequest()) {
                return new JsonResponse(["error" => "You're not authorized to view this resource"], 401);
            }

            return $response;
        }

        $this->getParserContext()
            ->addForm($this->getUpdateForm())
        ;

        $content = $this->getManager()->renderConfigurationTemplate($type);
        $response = new Response($content);

        if ("" === trim($content)) {
            $response->setStatusCode(400);
        }

        return $response;
    }

    public function renderColumnsConfigurationAction($type, $entity)
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, "IndexEngine", AccessManager::VIEW)) {
            if ($this->getRequest()->isXmlHttpRequest()) {
                return new JsonResponse(["error" => "You're not authorized to view this resource"], 401);
            }

            return $response;
        }

        $this->getParserContext()
            ->addForm($this->getUpdateForm())
        ;

        $content = $this->getManager()->renderConfigurationColumnsTemplate($type, $entity);
        $response = new Response($content);

        if ("" === trim($content)) {
            $response->setStatusCode(400);
        }

        return $response;
    }

    /**
     * @return \IndexEngine\Manager\IndexConfigurationManagerInterface
     */
    protected function getManager()
    {
        return $this->container->get("index_engine.index_configuration_manager");
    }

    protected function getUpdateForm($data = array())
    {
        $manager = $this->getManager();

        if (!is_array($data)) {
            $data = array();
        }

        return parent::getUpdateForm(array_merge($data, [
            "type" => $manager->getCurrentType(),
            "entity" => $manager->getCurrentEntity(),
            "columns" => $manager->getCurrentColumns(),
            "conditions" => $manager->getCurrentConditionsCriteria(),
            "mapping" => $manager->getCurrentMapping(),
        ]));
    }

    protected function renderEditionTemplate()
    {
        return parent::renderEditionTemplate();
    }

    /**
     * @return \IndexEngine\Entity\IndexMapping
     */
    public function getDefaultMapping()
    {
        return $this->container->get("index_engine.default_index_mapping");
    }
}
