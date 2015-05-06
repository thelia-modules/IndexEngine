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

namespace IndexEngine\Tests\Driver\Bridge;

use IndexEngine\Driver\AbstractEventDispatcherAwareDriver;
use IndexEngine\Driver\Configuration\ArgumentCollection;
use IndexEngine\Driver\DriverEventSubscriberInterface;
use IndexEngine\Driver\DriverInterface;
use IndexEngine\Driver\Query\Comparison;
use IndexEngine\Driver\Query\Criterion\Criterion;
use IndexEngine\Driver\Query\Criterion\CriterionGroup;
use IndexEngine\Driver\Query\IndexQuery;
use IndexEngine\Driver\Query\Link;
use IndexEngine\Entity\IndexData;
use IndexEngine\Entity\IndexDataVector;
use IndexEngine\Entity\IndexMapping;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class BridgeTestCase
 * @package IndexEngine\Tests\Driver\Bridge
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class BridgeTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var DriverInterface */
    protected $driver;

    /** @var EventDispatcher */
    protected $dispatcher;

    /** @var null|DriverEventSubscriberInterface  */
    protected $listener;

    protected $configuration;

    public function setUp()
    {
        $this->dispatcher = new EventDispatcher();
        $this->driver = $this->getDriver();

        if ($this->driver instanceof AbstractEventDispatcherAwareDriver) {
            $this->listener = $this->getListener();

            if (! $this->listener instanceof DriverEventSubscriberInterface) {
                throw new \LogicException("Your listener must implement IndexEngine\\Driver\\DriverEventSubscriberInterface");
            }

            $this->listener->setDriver($this->driver);
            $this->dispatcher->addSubscriber($this->listener);
            $this->driver->setDispatcher($this->dispatcher);

            $this->configuration = $this->driver->getConfiguration();

            $this->setConfiguration($this->configuration);
            $this->driver->loadConfiguration($this->configuration);
        }
    }

    /**
     * @param $type
     * @param $code
     * @param $name
     * @param IndexMapping $mapping
     *
     * @dataProvider generateIndex
     */
    public function testCreateIndex($type, $code, $name, IndexMapping $mapping)
    {
        // Delete the index if it already exists
        if (true === $this->driver->indexExists($type, $code, $name)) {
            $this->driver->deleteIndex($type, $code, $name);
        }

        $this->driver->createIndex($type, $code, $name, $mapping);

        $this->assertTrue($this->driver->indexExists($type, $code, $name));
    }

    /**
     * @param $type
     * @param $code
     * @param $name
     * @param IndexDataVector $indexDataVector
     * @param IndexMapping $mapping
     *
     * @dataProvider generateIndexData
     * @depends testCreateIndex
     */
    public function testInsertData($type, $code, $name, IndexDataVector $indexDataVector, IndexMapping $mapping)
    {
        $this->driver->persistIndexes($type, $code, $name, $indexDataVector, $mapping);

        // Let the time to the driver to build the index
        sleep(5);
    }

    /**
     * @depends testInsertData
     */
    public function testRetrieveDataWithoutFilter()
    {
        // Test that it supports an empty query
        $query = $this->getBaseQuery();

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());

        $data = iterator_to_array($results);
        $expectedData = iterator_to_array($this->getDataVector());

        // Then it support a simple >= operator
        $this->assertEquals($expectedData, $data);
    }

    /**
     * @depends testRetrieveDataWithoutFilter
     */
    public function testRetrieveDataWithOneFilterOnOneField()
    {
        $query = $this->getBaseQuery()->filterBy("id", 2, Criteria::GREATER_EQUAL);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(3, $data);

        // The IDS are 2,3 and 4. we ignored 1 \o/
        for ($i = 2; $i <= 4; ++$i) {
            /** @var IndexData $row */
            $row = $data[$i-2];
            $this->assertEquals($i, $row->getData()["id"]);
        }
    }

    /**
     * @depends testRetrieveDataWithOneFilterOnOneField
     */
    public function testRetrieveDataWithTwoFiltersOnOneField()
    {
        $query = $this->getBaseQuery();
        $group = new CriterionGroup();
        $group
            ->addCriterion(new Criterion("id", 2, Comparison::GREATER_EQUAL))
            ->addCriterion(new Criterion("id", 3, Comparison::LESS_EQUAL))
        ;
        $query->addCriterionGroup($group);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(2, $data);

        // The IDS are 2 and 3. we ignored 1 and 4 \o/
        for ($i = 2; $i <= 3; ++$i) {
            /** @var IndexData $row */
            $row = $data[$i-2];
            $this->assertEquals($i, $row->getData()["id"]);
        }
    }

    /**
     * @depends testRetrieveDataWithTwoFiltersOnOneField
     */
    public function testRetrieveDataWithThreeFiltersOnOneField()
    {
        $query = $this->getBaseQuery();
        $group = new CriterionGroup();
        $group
            ->addCriterion(new Criterion("id", 2, Comparison::NOT_EQUAL))
            ->addCriterion(new Criterion("id", 3, Comparison::NOT_EQUAL))
            ->addCriterion(new Criterion("id", 4, Comparison::NOT_EQUAL))
        ;
        $query->addCriterionGroup($group);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(1, $data);

        // The IDS are 2 and 3. we ignored 1 and 4 \o/
        $this->assertEquals(1, $data[0]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithThreeFiltersOnOneField
     */
    public function testRetrieveDataWithMultipleDifferentFiltersOnOneField()
    {
        $query = $this->getBaseQuery();
        $group = new CriterionGroup();
        $group
            ->addCriterion(new Criterion("id", 2, Comparison::NOT_EQUAL))
            ->addCriterion(new Criterion("id", 3, Comparison::LESS_EQUAL))
            ->addCriterion(new Criterion("id", 1, Comparison::GREATER_EQUAL))
            ->addCriterion(new Criterion("id", 3, Comparison::NOT_EQUAL))
        ;
        $query->addCriterionGroup($group);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(1, $data);

        // The IDS are 2 and 3. we ignored 1 and 4 \o/
        $this->assertEquals(1, $data[0]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithMultipleDifferentFiltersOnOneField
     */
    public function testRetrieveDataWithOneFilterOnTwoField()
    {
        $query = $this->getBaseQuery();
        $group = new CriterionGroup();
        $group
            ->addCriterion(new Criterion("id", 2, Comparison::GREATER_EQUAL))
            ->addCriterion(new Criterion("price", 40, Comparison::GREATER))
        ;
        $query->addCriterionGroup($group);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(2, $data);

        $this->assertEquals(2, $data[0]->getData()["id"]);
        $this->assertEquals(4, $data[1]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithOneFilterOnTwoField
     */
    public function testRetrieveDataWithMultipleFilterOnTwoField()
    {
        $query = $this->getBaseQuery();
        $group = new CriterionGroup();
        $group
            ->addCriterion(new Criterion("id", 2, Comparison::NOT_EQUAL))
            ->addCriterion(new Criterion("id", 2, Comparison::GREATER_EQUAL))
            ->addCriterion(new Criterion("price", 40, Comparison::GREATER))
            ->addCriterion(new Criterion("price", 50, Comparison::LESS))
            ->addCriterion(new Criterion("code", "T-shir", Comparison::LIKE))
            ->addCriterion(new Criterion("description", "best", Comparison::LIKE))
        ;

        $query->addCriterionGroup($group);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(1, $data);

        $this->assertEquals(4, $data[0]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithMultipleFilterOnTwoField
     */
    public function testRetrieveDataWithASimpleOrFilterOnOneField()
    {
        $query = $this->getBaseQuery();
        $group = new CriterionGroup();
        $group
            ->addCriterion(new Criterion("id", 1, Comparison::NOT_EQUAL), null, Link::LINK_OR)
            ->addCriterion(new Criterion("id", 2, Comparison::GREATER_EQUAL))
        ;

        $query->addCriterionGroup($group);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(3, $data);

        // The IDS are 2,3 and 4. we ignored 1 \o/
        for ($i = 2; $i <= 4; ++$i) {
            /** @var IndexData $row */
            $row = $data[$i-2];
            $this->assertEquals($i, $row->getData()["id"]);
        }
    }

    /**
     * @depends testRetrieveDataWithASimpleOrFilterOnOneField
     */
    public function testRetrieveDataWithTwoOrFilterOnOneField()
    {
        $query = $this->getBaseQuery();
        $group = new CriterionGroup();
        $group
            ->addCriterion(new Criterion("id", 1, Comparison::NOT_EQUAL), null, Link::LINK_OR)
            ->addCriterion(new Criterion("id", 2, Comparison::GREATER_EQUAL), null, Link::LINK_OR)
            ->addCriterion(new Criterion("id", 2, Comparison::LESS_EQUAL))
        ;

        $query->addCriterionGroup($group);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(4, $data);

        // All the IDs are there
        for ($i = 1; $i <= 4; ++$i) {
            /** @var IndexData $row */
            $row = $data[$i-1];
            $this->assertEquals($i, $row->getData()["id"]);
        }
    }

    /**
     * @depends testRetrieveDataWithTwoOrFilterOnOneField
     */
    public function testRetrieveDataWithOrFilterOnTwoField()
    {
        $query = $this->getBaseQuery();
        $group = new CriterionGroup();
        $group
            ->addCriterion(new Criterion("id", 2, Comparison::GREATER), null, Link::LINK_OR)
            ->addCriterion(new Criterion("price", 45, Comparison::LESS))
        ;

        $query->addCriterionGroup($group);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(3, $data);

        $this->assertEquals(1, $data[0]->getData()["id"]);
        $this->assertEquals(3, $data[1]->getData()["id"]);
        $this->assertEquals(4, $data[2]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithTwoOrFilterOnOneField
     *
     * In that test, we check that for the driver, the following boolean operation is correctly interpreted:
     *
     * a.b + c.d
     *
     * a,b,c,d being boolean values.
     *
     * "." (alias "AND") operator must be prior to "+" (alias "OR")
     *
     * We do a XOR operation to check that priority are respected:
     * _           _
     * a . b + a . b  = a xor b
     */
    public function testRetrieveDataWithBothOrAndAndFiltersChecksThatDriverSupportsOperatorPriority()
    {
        $query = $this->getBaseQuery();
        $group = new CriterionGroup();
        $group
            ->addCriterion(new Criterion("id", 2, Comparison::NOT_EQUAL))
            ->addCriterion(new Criterion("price", 1, Comparison::EQUAL), null, Link::LINK_OR)
            ->addCriterion(new Criterion("id", 2, Comparison::EQUAL))
            ->addCriterion(new Criterion("price", 1, Comparison::NOT_EQUAL))
        ;

        $query->addCriterionGroup($group);

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(2, $data);

        $this->assertEquals(2, $data[0]->getData()["id"]);
        $this->assertEquals(3, $data[1]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithBothOrAndAndFiltersChecksThatDriverSupportsOperatorPriority
     */
    public function testRetrieveDataWithTwoCriterionGroupsLinkedByAAnd()
    {
        $query = $this->getBaseQuery()
            ->filterBy("id", 1, Comparison::GREATER_EQUAL)
            ->filterBy("id", 4)
        ;

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(1, $data);

        $this->assertEquals(4, $data[0]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithTwoCriterionGroupsLinkedByAAnd
     */
    public function testRetrieveDataWithThreeCriterionGroupsLinkedByAAnd()
    {
        $query = $this->getBaseQuery()
            ->filterBy("id", 1, Comparison::GREATER)
            ->filterBy("id", 4, Comparison::LESS)
            ->filterBy("id", 3, Comparison::LESS_EQUAL)
        ;

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(2, $data);

        $this->assertEquals(2, $data[0]->getData()["id"]);
        $this->assertEquals(3, $data[1]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithThreeCriterionGroupsLinkedByAAnd
     */
    public function testRetrieveDataWithTwoCriterionGroupsLinkedByAndOnTwoFields()
    {
        $query = $this->getBaseQuery()
            ->filterBy("id", 1, Comparison::GREATER)
            ->filterBy("price", 40, Comparison::LESS)
        ;

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(1, $data);

        $this->assertEquals(3, $data[0]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithTwoCriterionGroupsLinkedByAndOnTwoFields
     */
    public function testRetrieveDataWithMultipleCriterionGroupsLinkedByAndOnMultipleFields()
    {
        $query = $this->getBaseQuery()
            ->filterBy("price", 50, Comparison::LESS)
            ->filterBy("code", "T-shi", Comparison::LIKE)
            ->filterBy("out_date", "2015-01-01", Comparison::GREATER_EQUAL)
        ;

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(1, $data);

        $this->assertEquals(4, $data[0]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithMultipleCriterionGroupsLinkedByAndOnMultipleFields
     */
    public function testRetrieveDataWithMultipleCriterionFilteredByAOrOnOneField()
    {
        $query = $this->getBaseQuery()
            ->filterBy("id", 1, Comparison::EQUAL, Link::LINK_OR)
            ->filterBy("id", 3, Comparison::LESS)
        ;

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(2, $data);

        $this->assertEquals(1, $data[0]->getData()["id"]);
        $this->assertEquals(2, $data[1]->getData()["id"]);
    }

    /**
     * @depends testRetrieveDataWithMultipleCriterionFilteredByAOrOnOneField
     */
    public function testRetrieveDataWithWeirdGuyComplexQuery()
    {
        $criterionGroupOne = new CriterionGroup();
        $criterionGroupOne
            ->addCriterion(new Criterion("id", 1, Comparison::NOT_EQUAL))
            ->addCriterion(new Criterion("description", "This text isn't anywhere", Comparison::LIKE))
        ;

        $criterionGroupTwo = new CriterionGroup();
        $criterionGroupTwo
            ->addCriterion(new Criterion("description", "user experience", Comparison::LIKE))
            ->addCriterion(new Criterion("description", "Great", Comparison::LIKE))
        ;



        $query = $this->getBaseQuery()
            ->addCriterionGroup($criterionGroupOne, null, Link::LINK_OR)
            ->addCriterionGroup($criterionGroupTwo)
            ->filterBy("id", 3, Comparison::LESS_EQUAL)
            ->filterBy("price", 50, Comparison::GREATER_EQUAL)
        ;

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());
        $data = iterator_to_array($results);

        $this->assertCount(1, $data);

        $this->assertEquals(2, $data[0]->getData()["id"]);
    }

    /**
     * @param $type
     * @param $code
     * @param $name
     *
     * @dataProvider generateIndex
     * @depends testRetrieveDataWithWeirdGuyComplexQuery
     */
    public function testDeleteIndex($type, $code, $name)
    {
        $this->driver->deleteIndex($type, $code, $name);

        $this->assertFalse($this->driver->indexExists($type, $code, $name));
    }

    public function generateIndex()
    {
        return [
            ["foo", "bar", "baz", $this->getMapping()]
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
            "created_at" => IndexMapping::TYPE_DATETIME
        ]);
    }

    public function generateIndexData()
    {
        return [
            ["foo", "bar", "baz", $this->getDataVector(), $this->getMapping()]
        ];
    }

    protected function getBaseQuery()
    {
        $query = new IndexQuery("bar", "baz");
        $query->orderBy("id");

        return $query;
    }

    public function getDataVector()
    {
        $dataVector = new IndexDataVector();

        $dataVector[] = (new IndexData())->setData([
            "id" => 1,
            "code" => "T-shirt",
            "description" => "Great T-shirt with a great user experience. The best T-shirt for everybody",
            "is_default" => true,
            "price" => 42.123,
            "out_date" => "2012-12-21",
            "out_hour" => "12:23:34",
            "created_at" => "2015-05-04T12:52:00"
        ], $this->getMapping());

        $dataVector[] = (new IndexData())->setData([
            "id" => 2,
            "code" => "Jeans",
            "description" => "Great jeans with a great user experience. The best jeans for everybody",
            "is_default" => true,
            "price" => 55.32,
            "out_date" => "2012-12-21",
            "out_hour" => "23:34:45",
            "created_at" => "2015-05-04T12:55:00"
        ], $this->getMapping());

        $dataVector[] = (new IndexData())->setData([
            "id" => 3,
            "code" => "Orange",
            "description" => "Great orange. Taste very good",
            "is_default" => false,
            "price" => 1,
            "out_date" => "1970-01-01",
            "out_hour" => "00:00:00",
            "created_at" => "2015-05-04T12:56:00"
        ], $this->getMapping());

        $dataVector[] = (new IndexData())->setData([
            "id" => 4,
            "code" => "T-shirt-2",
            "description" => "Great T-shirt with a great user experience. The best T-shirt for everybody. More T-shirt than #1",
            "is_default" => false,
            "price" => 43,
            "out_date" => "2015-05-04",
            "out_hour" => "12:23:34",
            "created_at" => "2015-05-04T12:58:00"
        ], $this->getMapping());

        return $dataVector;
    }

    public function setConfiguration(ArgumentCollection $collection = null)
    {
        // Override this method to set the configuration needed for the test
        if (null !== $collection) {
            $collection->loadValues($collection->getDefaults());
        }
    }

    public function getListener()
    {
        // Must return the listener if the driver needs it
        return null;
    }

    /**
     * @return DriverInterface
     */
    abstract protected function getDriver();
}
