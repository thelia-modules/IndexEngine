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

namespace IndexEngine\Controller\Api;

use IndexEngine\Exception\InvalidArgumentException;
use IndexEngine\Exception\SearchException;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\JsonResponse;

/**
 * Class IndexEngineIndexController
 * @package IndexEngine\Controller\Api
 * @author Benjamin Perche <benjamin@thelia.net>
 *
 * Public API for index engine
 */
class IndexEngineIndexController extends BaseFrontController
{
    public function searchAction($configurationCode)
    {
        try {
            $configuration = $this->getIndexConfigurationManager()->getConfigurationEntityFromCode($configurationCode);
            $results = $this->getSearchManager()->findResultsFromParams($configuration, $this->getRequest()->query->all());
        } catch (InvalidArgumentException $e) {
            return JsonResponse::createError(sprintf("The configuration code '%s' doesn't exist", $configurationCode), 404);
        } catch (SearchException $e) {
            return JsonResponse::createError(sprintf("An error occurred during the search process: %s", $e->getMessage()), 400);
        } catch (\Exception $e) {
            return JsonResponse::createError(sprintf("An error occurred during the search process: %s", $e->getMessage()), 500);
        }

        return new JsonResponse($results->toArray());
    }

    /**
     * @return \IndexEngine\Manager\SearchManagerInterface
     */
    protected function getSearchManager()
    {
        return $this->container->get("index_engine.search_manager");
    }

    /**
     * @return \IndexEngine\Manager\IndexConfigurationManagerInterface
     */
    protected function getIndexConfigurationManager()
    {
        return $this->container->get("index_engine.index_configuration_manager");
    }
}
