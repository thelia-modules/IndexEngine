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

    public function _if($condition)
    {
        array_push($this->conditionStack, (bool) $condition);
        $this->currentElement++;

        return $this;
    }

    public function _elseif($condition)
    {
        if (false === $this->conditionStack[$this->currentElement]) {
            $this->conditionStack[$this->currentElement] = (bool) $condition;
        } elseif (2 > (int) $this->conditionStack[$this->currentElement]) {
            $this->conditionStack[$this->currentElement] = 1 + (int) $this->conditionStack[$this->currentElement];
        }

        return $this;
    }

    public function _else()
    {
        if (2 > (int) $this->conditionStack[$this->currentElement]) {
            $this->conditionStack[$this->currentElement] = 1 + (int) $this->conditionStack[$this->currentElement];
        }

        return $this;
    }

    public function _endif()
    {
        array_pop($this->conditionStack);
        $this->currentElement--;
        $this->preventExecution = false;

        return $this;
    }

    public function validateMethodCall()
    {
        if (! empty($this->conditionStack) && (in_array(false, $this->conditionStack, true) || in_array(2, $this->conditionStack, true))) {
            return $this;
        }

        return null;
    }
}
