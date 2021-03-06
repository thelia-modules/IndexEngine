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

use IndexEngine\Driver\Query\Comparison;
use IndexEngine\Driver\Query\Criterion\Criterion;
use IndexEngine\Driver\Query\Criterion\CriterionGroup;
use IndexEngine\Driver\Query\Criterion\CriterionGroupInterface;
use IndexEngine\Driver\Query\IndexQuery;
use IndexEngine\Driver\Query\IndexQueryInterface;
use IndexEngine\Driver\Query\Link;
use IndexEngine\Driver\Query\Order;
use IndexEngine\Entity\IndexConfiguration;
use IndexEngine\Entity\IndexMapping;
use IndexEngine\Exception\InvalidArgumentException;

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
     * @return \IndexEngine\Entity\IndexDataVector
     *
     * @throws \IndexEngine\Exception\SearchException if something goes wrong
     *
     * With the given configuration and params, it must return an array with the found values
     */
    public function findResultsFromParams(IndexConfiguration $configuration, array $params)
    {
        $limit = intval($this->extractParam($params, "limit"));
        $offset = intval($this->extractParam($params, "offset", 0));
        $order = $this->extractParam($params, "order", []);

        if (! is_array($order)) {
            $order = array_map("trim", explode(",", $order));
        }

        $query = $configuration->createQuery()
            ->setLimit($limit)
            ->setOffset($offset)
        ;

        $this->applyOrder($query, $order);

        return $configuration->getLoadedDriver()->executeSearchQuery(
            $this->parseSearchQuery($params, $configuration->getMapping(), $query),
            $configuration->getMapping()
        );
    }

    /**
     * @param array $parameters
     * @param IndexMapping $mapping
     * @param IndexQueryInterface $query
     * @return IndexQueryInterface The built query
     *
     * @throws \IndexEngine\Exception\InvalidArgumentException if one of the parameters is not valid
     *
     * Parse given parameters and output a valid array.
     *
     * Input example:
     * [
     *   "id" => 5,
     *   "foo" => ["like", "some text"]
     *   0 => ["foo", "=", 5],
     *   "or" => [
     *      "id" => 5,
     *      "foo" => ["like", "some text"]
     *   ]
     * ]
     */
    public function parseSearchQuery(array $parameters, IndexMapping $mapping, IndexQueryInterface $query)
    {
        foreach ($parameters as $name => $entry) {
            if (null !== $criterionGroup = $this->resolveCriterionGroup($name, $entry, $mapping, $mapping->getMapping())) {
                $query->addCriterionGroup($criterionGroup);
            }
        }

        return $query;
    }

    protected function resolveCriterionGroup(
        $name,
        $entry,
        IndexMapping $mapping,
        array $mappingTable,
        CriterionGroupInterface $criterionGroup = null,
        $link = Link::LINK_AND
    ) {
        $isInRecursion = null !== $criterionGroup;

        if (! $isInRecursion) {
            $criterionGroup = new CriterionGroup();
        }

        if (is_array($entry)) {
            if (! $isInRecursion && Link::LINK_OR === $name) {
                foreach ($entry as $name => $subEntry) {
                    $this->resolveCriterionGroup($name, $subEntry, $mapping, $mappingTable, $criterionGroup, Link::LINK_OR);
                }

                return $criterionGroup;
            } else {
                $realValue = $entry;

                if (1 === $count = count($entry)) {
                    $comparison = Comparison::EQUAL;
                }

                switch ($count) {
                    case 3:
                        $name = array_shift($entry);
                    // no break
                    case 2:
                        $comparison = array_shift($entry);
                    // no break
                    case 1:
                        $entry = array_shift($entry);
                        break;

                    default:
                        throw new InvalidArgumentException(sprintf("The given parameter '%s' is not valid: %s", $name, var_export($realValue)));
                }
            }
        } else {
            $comparison = Comparison::EQUAL;
        }

        if ($mapping->hasColumn($name)) {
            $criterionGroup->addCriterion(new Criterion($name,  $mapping->getCastedValue($entry, $mappingTable[$name]), strtoupper($comparison)), null, $link);

            return $criterionGroup;
        }

        return null;
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
