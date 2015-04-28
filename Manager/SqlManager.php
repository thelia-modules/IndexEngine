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

namespace IndexEngine\Manager;

use IndexEngine\Exception\SqlException;
use IndexEngine\IndexEngine;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SqlManager
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class SqlManager implements SqlManagerInterface
{
    /** @var TranslatorInterface  */
    private $translator;

    /** @var \PDO  */
    private $con;

    public function __construct(TranslatorInterface $translator, \PDO $con)
    {
        $this->translator = $translator;
        $this->con = $con;
    }

    /**
     * @param $query
     * @param int $limit
     * @return array The fetched data
     *
     * @throws \IndexEngine\Exception\SqlException If the query has a problem
     *
     * Execute a query to get the results
     */
    public function executeQuery($query, $limit = 10)
    {
        $query = trim($query);

        if (strtoupper(substr($query, 0, 6)) !== "SELECT") {
            throw new SqlException(
                $this->translator->trans("Invalid SQL query. It must start with SELECT", [], IndexEngine::MESSAGE_DOMAIN)
            );
        }

        if (substr($query, -1) !== ";") {
            $query .= ";";
        }

        if (substr_count($query, ";") > 2) {
            throw new SqlException(
                $this->translator->trans("Invalid SQL query. It must only contain one query", [], IndexEngine::MESSAGE_DOMAIN)
            );
        }

        try {
            $stmt = $this->con->query($query);
            $stmt->execute();

            $data = $stmt->fetchAll(\PDO::FETCH_NAMED);

            if (count($data) > $limit) {
                $data = array_slice($data, 0, $limit);
            }

            return $data;
        } catch (\PDOException $e) {
            throw new SqlException(
                $this->translator->trans(
                    "Invalid SQL query: %exception",
                    ["%exception" => $e->getMessage()],
                    IndexEngine::MESSAGE_DOMAIN
                )
            );
        }
    }
}
