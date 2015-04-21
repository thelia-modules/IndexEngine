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


/**
 * Class AbstractIndexQuery
 * @package IndexEngine\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class AbstractIndexQuery implements IndexQueryInterface
{
    protected $currentMode = self::MODE_AND;

    protected $criteria = array();


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
    public function filterBy($column, $value, $comparison = self::COMPARISON_EQUAL, $outerMode = self::MODE_DEFAULT)
    {

        return $this;
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
        $comparison = self::COMPARISON_EQUAL,
        $innerMode = self::MODE_AND,
        $outerMode = self::MODE_DEFAULT
    ) {

        return $this;
    }


}
