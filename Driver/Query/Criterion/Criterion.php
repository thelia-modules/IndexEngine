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

use IndexEngine\Driver\Query\Comparison;

/**
 * Class Criterion
 * @package IndexEngine\Driver\Query\Criterion
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class Criterion implements CriterionInterface
{
    /** @var  string */
    protected $column;

    /** @var  mixed */
    protected $value;

    /** @var  string */
    protected $comparison;

    public function __construct($column, $value, $comparison = Comparison::EQUAL)
    {
        $this->column = $column;
        $this->value = $value;
        $this->comparison = $comparison;
    }

    /**
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function setColumn($column)
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getComparison()
    {
        return $this->comparison;
    }

    /**
     * @param string $comparison
     * @return $this
     */
    public function setComparison($comparison)
    {
        $this->comparison = $comparison;
        return $this;
    }
}
