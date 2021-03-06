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

use IndexEngine\Driver\Query\IndexQueryInterface;
use IndexEngine\Entity\IndexConfiguration;
use IndexEngine\Entity\IndexMapping;

/**
 * Class SearchManager
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface SearchManagerInterface
{
    /**
     * @param  IndexConfiguration $configuration
     * @param  array              $params
     * @return \IndexEngine\Entity\IndexDataVector
     *
     * @throws \IndexEngine\Exception\SearchException if something goes wrong
     *
     * With the given configuration and params, it must return an array with the found values
     */
    public function findResultsFromParams(IndexConfiguration $configuration, array $params);

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
    public function parseSearchQuery(array $parameters, IndexMapping $mapping, IndexQueryInterface $query);
}
