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

namespace IndexEngine\Tests\Driver\Query\Mock;

use IndexEngine\Driver\Query\FluidCallConditionTrait;

/**
 * Class FluidCallConditionTraitMock
 * @package IndexEngine\Tests\Driver\Query\Mock
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class FluidCallConditionTraitMock
{
    use FluidCallConditionTrait;

    public $baz;
    public $anArgument;

    public function foo()
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        throw new \Exception("foo");
    }

    public function bar()
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        throw new \Exception("bar");
    }

    public function baz()
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        $this->baz = "yes";

        return $this;
    }

    public function theFourthMethod()
    {
        if (null !== $return = $this->validateMethodCall()) {
            return $return;
        }

        $this->anArgument = "yes";

        return $this;
    }
}
