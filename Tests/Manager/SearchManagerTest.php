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

use IndexEngine\Driver\Query\Comparison;
use IndexEngine\Driver\Query\Criterion\Criterion;
use IndexEngine\Driver\Query\Criterion\CriterionGroup;
use IndexEngine\Driver\Query\IndexQuery;
use IndexEngine\Driver\Query\IndexQueryInterface;
use IndexEngine\Driver\Query\Link;
use IndexEngine\Driver\Query\Order;
use IndexEngine\Entity\IndexMapping;
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

    /**
     * @param array $given
     * @param array $expected
     *
     * @dataProvider rawQueryTableProvider
     */
    public function testParsesQueryVeryWellSoMuchWow(array $given, IndexMapping $mapping, IndexQueryInterface $expected)
    {
        $computed = $this->manager->parseSearchQuery($given, $mapping, new IndexQuery("foo", "bar"));

        $this->assertEquals($expected, $computed);
    }

    public function rawQueryTableProvider()
    {
        $mapping = $this->getMapping();

        return [
            [
                // Given
                [
                    "id" => "1"
                ],
                $mapping,
                // Expected
                IndexQuery::create("foo", "bar")->filterBy("id", 1, Comparison::EQUAL, Link::LINK_AND)
            ],
            [
                // Given
                [
                    "id" => ["1"]
                ],
                $mapping,
                // Expected
                IndexQuery::create("foo", "bar")->filterBy("id", 1, Comparison::EQUAL, Link::LINK_AND)
            ],
            [
                // Given
                [
                    "id" => [">", 1],
                    "code" => ["like", "foo"]
                ],
                $mapping,
                // Expected
                IndexQuery::create("foo", "bar")
                    ->filterBy("id", 1, Comparison::GREATER, Link::LINK_AND)
                    ->filterBy("code", "foo", Comparison::LIKE, Link::LINK_AND)
            ],
            [
                // Given
                [
                    ["id", ">", "2"],
                    ["id", "<", "5"],
                ],
                $mapping,
                // Expected
                IndexQuery::create("foo", "bar")
                    ->filterBy("id", 2, Comparison::GREATER, Link::LINK_AND)
                    ->filterBy("id", 5, Comparison::LESS, Link::LINK_AND)
            ],
            [
                // Given
                [
                    ["id", ">", "2"],
                    "or" => [
                        "id" => 1,
                        "code" => "foo"
                    ]
                ],
                $mapping,
                // Expected
                IndexQuery::create("foo", "bar")
                    ->filterBy("id", 2, Comparison::GREATER, Link::LINK_AND)
                    ->addCriterionGroup(
                        (new CriterionGroup())
                            ->addCriterion(new Criterion("id", 1, Comparison::EQUAL), null, Link::LINK_OR)
                            ->addCriterion(new Criterion("code", "foo", Comparison::EQUAL), null, Link::LINK_OR)
                    )
            ],
            [
                // Given
                [
                    "or" => [
                        ["id", "=", 1],
                        ["id", "=", 2],
                        ["code", "like", "foo"]
                    ]
                ],
                $mapping,
                // Expected
                IndexQuery::create("foo", "bar")
                    ->addCriterionGroup(
                        (new CriterionGroup())
                            ->addCriterion(new Criterion("id", 1, Comparison::EQUAL), null, Link::LINK_OR)
                            ->addCriterion(new Criterion("id", 2, Comparison::EQUAL), null, Link::LINK_OR)
                            ->addCriterion(new Criterion("code", "foo", Comparison::LIKE), null, Link::LINK_OR)
                    )
            ],
        ];
    }

    public function getMapping()
    {
        return (new IndexMapping())->setMapping([
            "id" => IndexMapping::TYPE_INTEGER,
            "code" => IndexMapping::TYPE_STRING,
            "description" => IndexMapping::TYPE_BIG_TEXT,
            "is_default" => IndexMapping::TYPE_BOOLEAN,
            "price" => IndexMapping::TYPE_FLOAT,
            "out_date" => IndexMapping::TYPE_DATE,
            "out_hour" => IndexMapping::TYPE_TIME,
            "created_at" => IndexMapping::TYPE_DATETIME,
        ]);
    }
}
