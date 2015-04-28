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

use IndexEngine\Exception\SqlException;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;

/**
 * Class IndexEngineSqlQueryController
 * @package IndexEngine\Controller
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexEngineSqlQueryController extends BaseAdminController
{
    public function testQueryAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, "IndexEngine", AccessManager::UPDATE)) {
            return $response;
        }

        try {
            return new JsonResponse(
                $this->getManager()->executeQuery($this->getRequest()->request->get("sql_query"))
            );
        } catch (SqlException $e) {
            return JsonResponse::createError($e->getMessage(), 400);
        }
    }

    /**
     * @return \IndexEngine\Manager\SqlManagerInterface
     */
    public function getManager()
    {
        return $this->container->get("index_engine.sql_manager");
    }
}
