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

namespace IndexEngine\Controller;

use IndexEngine\IndexEngine;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;

/**
 * Class IndexTaskController
 * @package IndexEngine\Controller
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexTaskController extends BaseAdminController
{
    public function listTaskAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, "IndexEngine", AccessManager::VIEW)) {
            return $response;
        }

        return $this->render("index-tasks");
    }

    public function generateConfigurationFormAction($taskCode)
    {
        $isXHR = $this->getRequest()->isXmlHttpRequest();

        if (null !== $response = $this->checkAuth(AdminResources::MODULE, "IndexEngine", AccessManager::VIEW)) {
            if ($isXHR) {
                return new JsonResponse([
                    "error_message" => $this->getTranslator()->trans(
                        "You don't have the right to execute a task",
                        [],
                        IndexEngine::MESSAGE_DOMAIN
                    )
                ], 401);
            } else {
                return $response;
            }
        }

        $taskRegistry = $this->getTaskRegistry();

        if (false === $taskRegistry->hasTask($taskCode)) {
            if ($isXHR) {
                return new JsonResponse([
                    "error_message" => $this->getTranslator()->trans(
                        "The task %task doesn't exist",
                        ["%task" => $taskCode],
                        IndexEngine::MESSAGE_DOMAIN
                    )
                ], 404);
            } else {
                return $this->pageNotFound();
            }
        }

        $manager = $this->getConfigurationRenderManager();
        $task = $taskRegistry->getTask($taskCode);

        $baseForm = $this->createForm("index_engine_task.configuration", "form", [], [
            "task" => $task
        ]);

        $this->getParserContext()->addForm($baseForm);

        $content = $manager->renderFormFromCollection($task->getParameters(), "index_engine_task.configuration");

        return new Response($content);
    }

    public function runTaskAction($taskCode)
    {

    }

    /**
     * @return \IndexEngine\Manager\ConfigurationRenderManagerInterface
     */
    protected function getConfigurationRenderManager()
    {
        return $this->container->get("index_engine.configuration_render_manager");
    }

    /**
     * @return \IndexEngine\Driver\Task\TaskRegistryInterface
     */
    protected function getTaskRegistry()
    {
        return $this->container->get("index_engine.task.registry");
    }
}
