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
namespace IndexEngine\Driver\Query;

use IndexEngine\Driver\Query\Criterion\CriterionGroupInterface;
use IndexEngine\Driver\Query\Criterion\CriterionInterface;

/**
 * Class AbstractIndexQuery
 * @package IndexEngine\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface IndexQueryInterface
{
    /**
     * @param string $column
     * @param mixed $value
     * @param string $comparison
     * @param null $outerMode
     * @return $this
     *
     * This method creates and adds a criterion.
     * $outerMode defines the link to have with the previous criterion.
     */
    public function filterBy($column, $value, $comparison = Comparison::EQUAL, $outerMode = Link::LINK_DEFAULT);

    /**
     * @param string $column
     * @param array $values
     * @param string $comparison
     * @param null|string $innerMode
     * @param null|string $outerMode
     * @return $this
     *
     * This method allow you to filter a column with multiple values.
     * If $innerMode === and, it will produce n comparisons linked with an AND. (n = count($values))
     *
     * $outerMode defines the link to have with the previous criterion.
     */
    public function filterByArray($column, array $values, $comparison = Comparison::EQUAL, $innerMode = Link::LINK_AND, $outerMode = Link::LINK_DEFAULT);

    /**
     * @param CriterionInterface $criterion
     * @param null|string $name
     * @param null|string $outerMode
     * @return AbstractIndexQuery
     *
     * Transforms the criterion into a criterion group and adds it to the stack
     */
    public function addCriterion(CriterionInterface $criterion, $name = null, $outerMode = Link::LINK_DEFAULT);

    /**
     * @param CriterionGroupInterface $criterionGroup
     * @param null|string $name
     * @param null|string $outerMode
     * @return $this
     *
     * Add a criterion group
     */
    public function addCriterionGroup(CriterionGroupInterface $criterionGroup, $name = null, $outerMode = Link::LINK_DEFAULT);

    /**
     * @param $name
     * @return bool
     *
     * Check if the criterion group exists
     */
    public function hasCriterionGroup($name);

    /**
     * @param $name
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException If the name doesn't exist
     *
     * Delete the criterion named $name
     */
    public function deleteCriterionGroup($name);

    /**
     * @param $name
     * @return mixed
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException If the name doesn't exist
     *
     * Return the reference of the criterion named $name
     */
    public function getCriterionGroup($name);

    /**
     * @return $this
     *
     * Set the default mode to Link::LINK_OR
     */
    public function _or();

    /**
     * @return $this
     *
     * Set the default mode to Link::LINK_AND
     */
    public function _and();

    /**
     * @return string
     *
     * The index type to apply the query on
     */
    public function getType();

    /**
     * @param int $limit
     * @return $this
     *
     * Set the max number of results to return
     */
    public function setLimit($limit);

    /**
     * @return int|null
     *
     * Get the max number of results to return
     */
    public function getLimit();
}