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

use IndexEngine\Driver\Query\IndexQuery;
use IndexEngine\Driver\Query\Order;
use IndexEngine\Entity\IndexConfiguration;

/**
 * Class SearchManager
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class SearchManager implements SearchManagerInterface
{
    const ORDER_REVERSE_SUFFIX = "-reverse";
    const ORDER_REVERSE_SUFFIX_LEN = 8;

    /**
     * @param  IndexConfiguration $configuration
     * @param  array              $params
     * @return array
     *
     * @throws \IndexEngine\Exception\SearchException if something goes wrong
     *
     * With the given configuration and params, it must return an array with the found values
     */
    public function findResultsFromParams(IndexConfiguration $configuration, array $params)
    {
        $limit = $this->extractParam($params, "limit", PHP_INT_MAX);
        $offset = $this->extractParam($params, "offset", 0);
        $order = $this->extractParam($params, "order", []);

        if (! is_array($order)) {
            $order = array_map("trim", explode(",", $order));
        }

        $query = $configuration->createQuery()
            ->setLimit($limit)
            ->setOffset($offset)
        ;

        $this->applyOrder($query, $order);
    }

    protected function applyOrder(IndexQuery $query, array $order)
    {
        foreach ($order as $orderChain) {
            if (is_array($orderChain)) {
                $orderName = array_shift($orderChain);
                $orderType = array_shift($orderChain);

                if (null === $orderName) {
                    continue;
                }

                if ($orderType !== Order::ASC && $orderType !== Order::DESC) {
                    $orderType = Order::ASC;
                }
            } elseif (static::ORDER_REVERSE_SUFFIX_LEN < strlen($orderChain) &&
                0 === substr_compare($orderChain, static::ORDER_REVERSE_SUFFIX, -static::ORDER_REVERSE_SUFFIX_LEN, null, true)
            ) {
                $orderName = substr($orderChain, 0, -static::ORDER_REVERSE_SUFFIX_LEN);
                $orderType = Order::DESC;
            } else {
                $orderName = $orderChain;
                $orderType = Order::ASC;
            }

            $query->orderBy($orderName, $orderType);
        }
    }

    protected function extractParam(array &$params, $paramName, $default = null)
    {
        if (isset($params[$paramName])) {
            $value = $params[$paramName];
            unset($params[$paramName]);

            return $value;
        }

        return $default;
    }
}
