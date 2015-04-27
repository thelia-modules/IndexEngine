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
use Propel\Runtime\Propel;
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

        $query = trim($this->getRequest()->request->get("sql_query"));

        if (strtoupper(substr($query, 0, 6)) !== "SELECT") {
            return JsonResponse::createError(
                $this->getTranslator()->trans("Invalid SQL query. It must start with SELECT", [], IndexEngine::MESSAGE_DOMAIN),
                400
            );
        }

        if (substr($query, -1) !== ";") {
            $query .= ";";
        }

        if (substr_count($query, ";") > 2) {
            return JsonResponse::createError(
                $this->getTranslator()->trans("Invalid SQL query. It must only contain one query", [], IndexEngine::MESSAGE_DOMAIN),
                400
            );
        }

        /** @var \PDO $con */
        $con = Propel::getConnection()->getWrappedConnection();

        try {
            $stmt = $con->query($query);
            $stmt->execute();

            $data = $stmt->fetchAll(\PDO::FETCH_NAMED);

            if (count($data) > 10) {
                $data = array_slice($data, 0, 10);
            }

            return new JsonResponse($data);
        } catch (\PDOException $e) {
            return JsonResponse::createError(
                $this->getTranslator()->trans(
                    "Invalid SQL query: %exception",
                    ["%exception" => $e->getMessage()],
                    IndexEngine::MESSAGE_DOMAIN
                ),
                400
            );
        }
    }
}
