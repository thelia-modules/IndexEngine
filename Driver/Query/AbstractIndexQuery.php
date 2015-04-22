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

    private static $linkModes = [
        Link::LINK_OR,
        Link::LINK_AND,
        Link::LINK_DEFAULT,
    ];

    protected $currentMode = Link::LINK_AND;

    protected $criterionGroups = array();


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
    public function filterBy($column, $value, $comparison = Comparison::EQUAL, $outerMode = Link::LINK_DEFAULT)
    {
        return $this->addCriterion(new Criterion($column, $value, $comparison), $outerMode);
    }

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

        foreach ($values as $value) {
            $criterionGroup->addCriterion(new Criterion($column, $value, $comparison, $innerMode));
        }

        return $this->addCriterionGroup($criterionGroup, null, $outerMode);
    }

    /**
     * @param CriterionInterface $criterion
     * @param null|string $name
     * @param null|string $outerMode
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
     * @param CriterionGroupInterface $criterionGroup
     * @param null|string $name
     * @param null|string $outerMode
     * @return $this
     *
     * Add a criterion group
     */
    public function addCriterionGroup(CriterionGroupInterface $criterionGroup, $name = null, $outerMode = Link::LINK_DEFAULT)
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
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
}
