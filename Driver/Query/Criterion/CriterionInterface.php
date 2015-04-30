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


/**
 * Interface CriterionInterface
 * @package IndexEngine\Driver\Query\Criterion
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface CriterionInterface
{
    /**
     * @return string
     */
    public function getColumn();

    /**
     * @param string $column
     * @return $this
     */
    public function setColumn($column);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value);

    /**
     * @return string
     */
    public function getComparison();
    /**
     * @param string $comparison
     * @return $this
     */
    public function setComparison($comparison);

    /**
     * @return string
     *
     * The criterion can be represented as a string
     */
    public function __toString();
}
