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

use IndexEngine\Driver\AbstractCollection;
use IndexEngine\Driver\Exception\InvalidNameException;
use IndexEngine\Driver\Query\Criterion\Criterion;
use IndexEngine\Driver\Query\Criterion\CriterionGroup;
use IndexEngine\Driver\Query\Criterion\CriterionGroupInterface;
use IndexEngine\Driver\Query\Criterion\CriterionInterface;

/**
 * Class AbstractIndexQuery
 * @package IndexEngine\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class AbstractIndexQuery extends AbstractCollection implements IndexQueryInterface
{
    use FluidCallConditionTrait;

    private $type;
    private $name;
    private $limit;
    private $offset;
    private $orderBy;

    public function __construct($type, $name)
    {
        $this->type = $this->resolveString($type, __METHOD__);
        $this->name = $this->resolveString($name, __METHOD__);
    }

    /**
     * @var string
     *
     * The current default link mode
     */
    protected $currentMode = Link::LINK_AND;

    /**
     * @var array
     *
     * The collection of criterion groups
     */
    protected $criterionGroups = array();

    /**
     * @param  string $column
     * @param  mixed  $value
     * @param  string $comparison
     * @param  null   $outerMode
     * @return $this
     *
     * This method creates and adds a criterion.
     * $outerMode defines the link to have with the previous criterion.
     */
    public function filterBy($column, $value, $comparison = Comparison::EQUAL, $outerMode = Link::LINK_DEFAULT)
    {
        return $this->addCriterion(new Criterion($column, $value, $comparison), null, $outerMode);
    }

    /**
     * @param  string      $column
     * @param  array       $values
     * @param  string      $comparison
     * @param  null|string $innerMode
     * @param  null|string $outerMode
     * @return $this
     *
     * This method allow you to filter a column with multiple values.
     * If $innerMode === and, it will produce n comparisons linked with an AND. (n = count($values))
     *
     * $outerMode defines the link to have with the previous criterion.
     */
    public function filterByArray(
        $column,
        array $values,
        $comparison = Comparison::EQUAL,
        $innerMode = Link::LINK_AND,
        $outerMode = Link::LINK_DEFAULT
    ) {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        $criterionGroup = new CriterionGroup();

        if (Link::LINK_DEFAULT === $innerMode) {
            $innerMode = $this->currentMode;
        }

        foreach ($values as $value) {
            $criterionGroup->addCriterion(new Criterion($column, $value, $comparison, $innerMode));
        }

        return $this->addCriterionGroup($criterionGroup, null, $outerMode);
    }

    /**
     * @param  CriterionInterface $criterion
     * @param  null|string        $name
     * @param  null|string        $outerMode
     * @return AbstractIndexQuery
     *
     * Transforms the criterion into a criterion group and adds it to the stack
     */
    public function addCriterion(CriterionInterface $criterion, $name = null, $outerMode = Link::LINK_DEFAULT)
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        $criterionGroup = new CriterionGroup();
        $criterionGroup->addCriterion($criterion, $name);

        return $this->addCriterionGroup($criterionGroup, $name, $outerMode);
    }

    /**
     * @param  CriterionGroupInterface $criterionGroup
     * @param  null|string             $name
     * @param  null|string             $outerMode
     * @return $this
     *
     * Add a criterion group
     */
    public function addCriterionGroup(CriterionGroupInterface $criterionGroup, $name = null, $outerMode = Link::LINK_DEFAULT)
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        if (Link::LINK_DEFAULT === $outerMode) {
            $outerMode = $this->currentMode;
        }

        if (null === $name) {
            $this->criterionGroups[] = [$criterionGroup, $outerMode];
        } else {
            $this->criterionGroups[$this->resolveString($name, __METHOD__)] = [$criterionGroup, $outerMode];
        }

        return $this;
    }

    /**
     * @param $name
     * @return bool
     *
     * Check if the criterion group exists
     */
    public function hasCriterionGroup($name)
    {
        return isset($this->criterionGroups[$this->resolveString($name, __METHOD__)]);
    }

    /**
     * @param $name
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException If the name doesn't exist
     *
     * Delete the criterion named $name
     */
    public function deleteCriterionGroup($name)
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        $name = $this->resolveString($name, __METHOD__);

        if (false === $this->hasCriterionGroup($name)) {
            throw new InvalidNameException(sprintf("The criterion group '%s' doesn't exist", $name));
        }

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException If the name doesn't exist
     *
     * Return the reference of the criterion named $name
     */
    public function &getCriterionGroup($name)
    {
        $name = $this->resolveString($name, __METHOD__);

        if (false === $this->hasCriterionGroup($name)) {
            throw new InvalidNameException(sprintf("The criterion group '%s' doesn't exist", $name));
        }

        return $this->criterionGroups[$name];
    }

    /**
     * @return array
     *
     * This method dumps all the criterion groups and their links.
     */
    public function getCriterionGroups()
    {
        return $this->criterionGroups;
    }

    /**
     * @return $this
     *
     * Set the default mode to Link::LINK_OR
     */
    public function _or()
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        // Replace last occurrence
        $lastOccurrence = array_pop($this->criterionGroups);

        if (null !== $lastOccurrence) {
            $lastOccurrence[1] = Link::LINK_OR;

            array_push($this->criterionGroups, $lastOccurrence);
        }

        $this->currentMode = Link::LINK_OR;

        return $this;
    }

    /**
     * @return $this
     *
     * Set the default mode to Link::LINK_AND
     */
    public function _and()
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        // Replace last occurrence
        $lastOccurrence = array_pop($this->criterionGroups);

        if (null !== $lastOccurrence) {
            $lastOccurrence[1] = Link::LINK_OR;

            array_push($this->criterionGroups, $lastOccurrence);
        }

        $this->currentMode = Link::LINK_AND;

        return $this;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     *
     * @throws \BadMethodCallException If the method can't be resolved
     */
    public function __call($method, $arguments)
    {
        if (preg_match("/^filterBy(([A-Z\d][a-z\d]*)+)$/", $method, $match)) {
            $camelizedName = $match[1];

            $name = preg_replace_callback(
                "/[A-Z]/",
                function ($match) {
                    return "_".strtolower($match[0]);
                },
                lcfirst($camelizedName)
            );

            array_unshift($arguments, $name);

            return call_user_func_array([$this, "filterBy"], $arguments);
        }

        throw new \BadMethodCallException(sprintf("The method %s::%s doesn't exist", __CLASS__, $method));
    }

    /**
     * @return string
     *
     * The index type to apply the query on
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  int   $limit
     * @return $this
     *
     * Set the max number of results to return
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int|null
     *
     * Get the max number of results to return
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return string
     *
     * The index name to apply the query on
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $offset
     * @return $this
     *
     * Set the current offset
     */
    public function setOffset($offset)
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int|null
     *
     * Get the current offset
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param  string $column
     * @param  string $type
     * @return $this
     *
     * Add a field to the order stack
     */
    public function orderBy($column, $type = Order::ASC)
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        $this->orderBy[] = [$column, $type];

        return $this;
    }

    /**
     * @return array
     *
     * Return the reference to the orderBy stack
     */
    public function &getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return $this
     *
     * Reset the order by stack
     */
    public function clearOrderBy()
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        $this->orderBy = array();
    }
}
