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

namespace IndexEngine\Driver\Query\Criterion;

use IndexEngine\Driver\Query\Link;

/**
 * Interface CriterionGroupInterface
 * @package IndexEngine\Driver\Query\Criterion
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface CriterionGroupInterface
{
    /**
     * @param CriterionInterface $criterion
     * @param null|string $name
     * @param string $link
     * @return $this
     *
     * Add a criterion to the group
     */
    public function addCriterion(CriterionInterface $criterion, $name = null, $link = Link::LINK_AND);

    /**
     * @param $name
     * @return bool
     *
     * Check if the criterion named $name exists
     */
    public function hasCriterion($name);

    /**
     * @param $name
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException if the name doesn't exist
     *
     * Delete the criterion named $name.
     */
    public function deleteCriterion($name);

    /**
     * @param $name
     * @return array Composed of a CriterionInterface and the link (string)
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException if the name doesn't exist
     */
    public function getCriterion($name);

    /**
     * @return array[]
     *
     * Dump all the criteria
     */
    public function getCriteria();

    /**
     * @return int
     *
     * Get the current entry number of the collection
     */
    public function count();
}
