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
use IndexEngine\Driver\Query\IndexQuery;
use IndexEngine\Entity\IndexData;
use IndexEngine\Entity\IndexDataVector;
use IndexEngine\Entity\IndexMapping;
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
    public function testRetrieveData()
    {
        $query = new IndexQuery("bar", "baz");

        $results = $this->driver->executeSearchQuery($query, $this->getMapping());

        $data = iterator_to_array($results);
        $expectedData = iterator_to_array($this->getDataVector());

        $sortClosure = function (IndexData $a, IndexData $b) {
            $aId = $a->getData()["id"];
            $bId = $b->getData()["id"];

            if ($aId === $bId) {
                return 0;
            }

            return $aId < $bId ? -1 : 1;
        };

        usort($data, $sortClosure);
        usort($expectedData, $sortClosure);

        $this->assertEquals($expectedData, $data);
    }

    /**
     * @param $type
     * @param $code
     * @param $name
     *
     * @dataProvider generateIndex
     * @depends testRetrieveData
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
            "description" => "Great orange with a great user experience. Taste very good",
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
