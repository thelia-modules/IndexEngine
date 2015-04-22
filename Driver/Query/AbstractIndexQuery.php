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

use IndexEngine\Driver\Query\Criterion\Criterion;
use IndexEngine\Driver\Query\Criterion\CriterionGroup;
use IndexEngine\Driver\Query\Criterion\CriterionGroupInterface;
use IndexEngine\Driver\Query\Criterion\CriterionInterface;

/**
 * Class AbstractIndexQuery
 * @package IndexEngine\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class AbstractIndexQuery implements IndexQueryInterface
{
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
     * This method adds a criterion to the criteria stack.
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


        return $this;
    }

    public function addCriterion(CriterionInterface $criterion, $name = null, $outerMode = Link::LINK_DEFAULT)
    {
        $criterionGroup = new CriterionGroup();
        $criterionGroup->addCriterion($criterion, $name);

        $this->criterionGroups[] = [$criterionGroup, $outerMode];

        return $this;
    }

    public function addCriterionGroup(CriterionGroupInterface $criterionGroup, $outerMode = Link::LINK_DEFAULT)
    {
        $this->criterionGroups[] = [$criterionGroup, $outerMode];

        return $this;
    }
}
