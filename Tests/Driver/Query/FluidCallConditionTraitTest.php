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

namespace IndexEngine\Tests\Driver\Query;

use IndexEngine\Tests\Driver\Query\Mock\FluidCallConditionTraitMock;

/**
 * Class FluidCallConditionTraitTest
 * @package IndexEngine\Tests\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class FluidCallConditionTraitTest extends \PHPUnit_Framework_TestCase
{
    /** @var FluidCallConditionTraitMock */
    protected $mock;

    protected function setUp()
    {
        $this->mock = new FluidCallConditionTraitMock();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage foo
     */
    public function testIfIfConditionIsValidExecutionContinue()
    {
        $this->mock
            ->_if(true)
                ->foo()
            ->_endif()
        ;
    }

    public function testIfIfConditionIsNotValidFunctionIsNotCalledButReturnTheObjectItself()
    {
        $mock = $this->mock->_if(false)->foo();

        $this->assertEquals($this->mock, $mock);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage bar
     */
    public function testIfIfConditionIsNotValidElseIsExecuted()
    {
        $this->mock
            ->_if(false)
               ->foo()
            ->_else()
                ->bar()
            ->_endif()
        ;
    }

    public function testElseIfConditionPreventElseExecutionIfValid()
    {
        $this->mock
            ->_if(false)
                ->foo()
            ->_elseif(true)
                ->baz()
            ->_else()
                ->foo()
            ->_endif()
        ;

        $this->assertEquals("yes", $this->mock->baz);
    }

    public function testMultipleIfCanBeUsed()
    {
        $this->mock
            ->_if(false)
                ->foo()
            ->_elseif(true)
                ->baz()
            ->_else()
                ->foo()
            ->_endif()
            ->_if(false)
                ->foo()
            ->_elseif(true)
                ->theFourthMethod()
            ->_else()
                ->foo()
            ->_endif()
        ;

        $this->assertEquals("yes", $this->mock->baz);
        $this->assertEquals("yes", $this->mock->anArgument);
    }

    public function testSupportNestedConditions()
    {
        $this->mock
            ->_if(false)
                ->foo()
            ->_elseif(true)
                ->_if(false)
                    ->foo()
                ->_elseif(true)
                    ->baz()
                ->_endif()
            ->_else()
                ->foo()
            ->_endif()
            ->_if(false)
                ->foo()
            ->_else()
                ->theFourthMethod()
            ->_endIf()
        ;

        $this->assertEquals("yes", $this->mock->baz);
        $this->assertEquals("yes", $this->mock->anArgument);
    }
}
