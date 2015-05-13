<?php
/**
 * This class has been generated by TheliaStudio
 * For more information, see https://github.com/thelia-modules/TheliaStudio
 */

namespace IndexEngine\Controller;

use IndexEngine\Controller\Base\IndexEngineIndexController as BaseIndexEngineIndexController;
use IndexEngine\Event\IndexEngineIndexEvent;
use IndexEngine\Form\Transformer\IndexMappingTransformer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Tools\URL;

/**
 * Class IndexEngineIndexController
 * @package IndexEngine\Controller
 */
class IndexEngineIndexController extends BaseIndexEngineIndexController
{
    /**
     * Creates the creation event with the provided form data
     *
     * @param  mixed                          $formData
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
     * @param  mixed                          $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getUpdateEvent($formData)
    {
        $event = new IndexEngineIndexEvent();

        $transformer = new IndexMappingTransformer();

        $event->setId($formData["id"]);
        $event->setCode($formData["code"]);
        $event->setTitle($formData["title"]);
        $event->setType($formData["type"]);
        $event->setEntity($formData["entity"]);
        $event->setIndexEngineDriverConfigurationId($formData["index_engine_driver_configuration_id"]);
        $event->setConditions($formData["conditions"]);
        $event->setColumns($formData["columns"]);
        $event->setMapping($transformer->reverseTransform($formData["mapping"]));

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

        $mappingTransformer = new IndexMappingTransformer();

        return parent::getUpdateForm(array_merge($data, [
            "type" => $manager->getCurrentType(),
            "entity" => $manager->getCurrentEntity(),
            "columns" => $manager->getCurrentColumns(),
            "conditions" => $manager->getCurrentConditionsCriteria(),
            "mapping" => $mappingTransformer->transform($manager->getCurrentMapping()), // I don't use the transformer in the form because ... well ... I can't make it work.
        ]));
    }

    protected function renderEditionTemplate()
    {
        $this->getParserContext()
            ->set(
                "index_engine_index_id",
                $this->getRequest()->query->get("index_engine_index_id")
            )
        ;

        return $this->render("index-engine-index-edit", [
            "mappingChoices" => $this->getDefaultMapping()->getTypes()
        ]);
    }

    protected function redirectToListTemplate()
    {
        return new RedirectResponse(
            URL::getInstance()->absoluteUrl("/admin/module/IndexEngine")
        );
    }


    /**
     * @return \IndexEngine\Entity\IndexMapping
     */
    public function getDefaultMapping()
    {
        return $this->container->get("index_engine.default_index_mapping");
    }
}
