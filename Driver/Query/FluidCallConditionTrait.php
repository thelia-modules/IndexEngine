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
 * Class FluidCallConditionTrait
 * @package IndexEngine\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
trait FluidCallConditionTrait
{
    protected $conditionStack = array();
    protected $currentElement = -1;
    protected $executionState = 0;

    /**
     * @param bool $condition
     * @return $this
     */
    public function _if($condition)
    {
        array_push($this->conditionStack, (bool) $condition);
        $this->currentElement++;

        return $this;
    }

    /**
     * @param bool $condition
     * @return $this
     */
    public function _elseif($condition)
    {
        if (-1 !== $this->currentElement) {
            if (false === $this->conditionStack[$this->currentElement]) {
                $this->conditionStack[$this->currentElement] = (bool)$condition;
            } elseif (2 > (int)$this->conditionStack[$this->currentElement]) {
                $this->conditionStack[$this->currentElement] = 1 + (int)$this->conditionStack[$this->currentElement];
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function _else()
    {
        if (-1 !== $this->currentElement) {
            if (2 > (int)$this->conditionStack[$this->currentElement]) {
                $this->conditionStack[$this->currentElement] = 1 + (int)$this->conditionStack[$this->currentElement];
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function _endif()
    {
        if (-1 !== $this->currentElement) {
            array_pop($this->conditionStack);
            $this->currentElement--;
            $this->preventExecution = false;
        }

        return $this;
    }

    /**
     * @return $this|null
     *
     * This method must be called in conditionally chainable methods.
     *
     * If you want your method not to be executed if the current condition is false,
     * add this snippet a the begining:
     *
     * if (null !== $return = $this->validateMethodCall()) {
     *      return $return;
     * }
     */
    public function validateMethodCall()
    {
        if (-1 !== $this->currentElement && (in_array(false, $this->conditionStack, true) || in_array(2, $this->conditionStack, true))) {
            return $this;
        }

        return null;
    }
}
