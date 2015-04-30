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

use IndexEngine\Driver\AbstractCollection;
use IndexEngine\Driver\Exception\InvalidNameException;
use IndexEngine\Driver\Query\Link;

/**
 * Class CriterionGroup
 * @package IndexEngine\Driver\Query\Criterion
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class CriterionGroup extends AbstractCollection implements CriterionGroupInterface
{
    /** @var array  */
    private $collection = array();
    private $collectionCount = 0;

    /**
     * @param CriterionInterface $criterion
     * @param null|string $name
     * @param string $link
     * @return $this
     *
     * Add a criterion to the group
     */
    public function addCriterion(CriterionInterface $criterion, $name = null, $link = Link::LINK_AND)
    {
        if (null === $name) {
            $this->collection[] = [$criterion, $link];
        } else {
            $this->collection[$this->resolveString($name, __METHOD__)] = [$criterion, $link];
        }

        $this->collectionCount++;

        return $this;
    }

    /**
     * @param $name
     * @return bool
     *
     * Check if the criterion named $name exists
     */
    public function hasCriterion($name)
    {
        return isset($this->collection[$this->resolveString($name, __METHOD__)]);
    }

    /**
     * @param $name
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException if the name doesn't exist
     *
     * Delete the criterion named $name.
     */
    public function deleteCriterion($name)
    {
        $name = $this->resolveString($name, __METHOD__);

        if (false === $this->hasCriterion($name)) {
            throw new InvalidNameException(sprintf("The criterion '%s' doesn't exist in this group", $name));
        }

        unset($this->collection[$this->resolveString($name, __METHOD__)]);
        $this->collectionCount--;

        return $this;
    }

    /**
     * @param $name
     * @return array Composed of a CriterionInterface and the link (string)
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException if the name doesn't exist
     */
    public function getCriterion($name)
    {
        $name = $this->resolveString($name, __METHOD__);

        if (false === $this->hasCriterion($name)) {
            throw new InvalidNameException(sprintf("The criterion '%s' doesn't exist in this group", $name));
        }

        return $this->collection[$this->resolveString($name, __METHOD__)];
    }

    /**
     * @return array[]
     *
     * Dump all the criteria
     */
    public function getCriteria()
    {
        return $this->collection;
    }

    /**
     * @return int
     *
     * Get the current entry number of the collection
     */
    public function count()
    {
        return $this->collectionCount;
    }
}
