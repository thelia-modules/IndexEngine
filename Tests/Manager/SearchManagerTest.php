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

namespace IndexEngine\Tests\Manager;

use IndexEngine\Driver\Query\IndexQuery;
use IndexEngine\Driver\Query\Order;
use IndexEngine\Manager\SearchManager;

/**
 * Class SearchManagerTest
 * @package IndexEngine\Tests\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class SearchManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SearchManager
     */
    protected $manager;

    protected function setUp()
    {
        $this->manager = new SearchManager();
    }

    public function testExtractParameter()
    {
        $closure = $this->getMethodClosure($this->manager, "extractParam");

        $params = [
            "foo" => "bar",
        ];

        $this->assertArrayHasKey("foo", $params);
        $this->assertEquals("bar", $closure($params, "foo"));
        $this->assertArrayNotHasKey("foo", $params);
        $this->assertNull($closure($params, "foo"));
    }

    public function testApplyOrder()
    {
        $closure = $this->getMethodClosure($this->manager, "applyOrder");
        $query = new IndexQuery("foo", "bar");

        $supportedOrderFormats = [
            "orange",
            "banana-reverse",
            ["apple"],
            ["pear", "asc"],
            ["pineapple", "desc"],
        ];

        $expectedOrders = [
            ["orange"       , Order::ASC],
            ["banana"       , Order::DESC],
            ["apple"        , Order::ASC],
            ["pear"         , Order::ASC],
            ["pineapple"    , Order::DESC],
        ];

        $closure($query, $supportedOrderFormats);

        $this->assertEquals($expectedOrders, $query->getOrderBy());
    }

    protected function getMethodClosure($object, $method)
    {
        $reflection = new \ReflectionMethod(get_class($object), $method);
        $reflection->setAccessible(true);

        return $reflection->getClosure($object);
    }
}
