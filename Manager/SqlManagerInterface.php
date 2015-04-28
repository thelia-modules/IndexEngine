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

/**
 * Class SqlManager
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface SqlManagerInterface
{
    /**
     * @param $query
     * @param int $limit
     * @return array The fetched data
     *
     * @throws \IndexEngine\Exception\SqlException If the query has a problem
     *
     * Execute a query to get the results
     */
    public function executeQuery($query, $limit = 10);
}