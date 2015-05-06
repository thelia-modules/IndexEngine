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

use IndexEngine\Entity\IndexConfiguration;

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
     * @return array
     *
     * @throws \IndexEngine\Exception\SearchException if something goes wrong
     *
     * With the given configuration and params, it must return an array with the found values
     */
    public function findResultsFromParams(IndexConfiguration $configuration, array $params);
}
