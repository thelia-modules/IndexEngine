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

namespace IndexEngine\Driver\Bridge\ElasticSearch;

use Elasticsearch\Client;
use IndexEngine\Driver\Configuration\BooleanArgument;
use IndexEngine\Driver\Configuration\IntegerArgument;
use IndexEngine\Driver\Configuration\StringVectorArgument;
use IndexEngine\Driver\DriverEventSubscriber;
use IndexEngine\Driver\Event\DriverConfigurationEvent;
use IndexEngine\Driver\Event\DriverEvents;
use IndexEngine\Driver\Event\IndexEvent;
use IndexEngine\Driver\Exception\IndexNotFoundException;
use IndexEngine\Entity\IndexMapping;

/**
 * Class ElasticSearchListener
 * @package IndexEngine\Driver\Bridge\ElasticSearch
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ElasticSearchListener extends DriverEventSubscriber
{
    public function getConfiguration(DriverConfigurationEvent $event)
    {
        $collection = $event->getArgumentCollection();

        $collection->addArgument(new StringVectorArgument("servers"));
        $collection->addArgument(new IntegerArgument("number_of_shards"));
        $collection->addArgument(new IntegerArgument("number_of_replicas"));
        $collection->addArgument(new BooleanArgument("save_source"));

        $collection->setDefaults([
            "servers" => [ElasticSearchDriver::DEFAULT_SERVER],
            "number_of_shards" => ElasticSearchDriver::DEFAULT_SHARDS,
            "number_of_replicas" => ElasticSearchDriver::DEFAULT_REPLICAS,
            "save_source" => ElasticSearchDriver::DEFAULT_SAVE_SOURCE,
        ]);
    }

    public function loadConfiguration(DriverConfigurationEvent $event)
    {
        $configuration = $event->getArgumentCollection();
        $hosts = iterator_to_array($configuration->getArgument("servers"));

        $this->getDriver()
            ->addExtraConfiguration(
                "client",
                new Client(["hosts" => $hosts])
            )
            ->addExtraConfiguration("number_of_shards", $configuration->getArgument("number_of_shards"))
            ->addExtraConfiguration("number_of_replicas", $configuration->getArgument("number_of_replicas"))
            ->addExtraConfiguration("save_source", $configuration->getArgument("save_source"))
        ;
    }

    public function prepareIndexMapping(IndexEvent $event)
    {
        $type = $event->getType();
        $name = $event->getIndexName();
        $mapping = $event->getMapping();

        $parameters = array("index" => $name);
        $driver = $this->getDriver();

        $shards = $driver->getExtraConfiguration("number_of_shards");
        if (null !== $shards) {
            $parameters["body"]["settings"]["number_of_shards"] = $shards;
        }

        $replicas = $driver->getExtraConfiguration("number_of_replicas");
        if (null !== $replicas) {
            $parameters["body"]["settings"]["number_of_replicas"] = $replicas;
        }

        $esMapping = &$parameters["body"]["mappings"][$type];

        $esMapping["_source"] = ["enabled" => $driver->getExtraConfiguration("save_source")];

        foreach ($mapping->getMapping() as $column => $type) {
            $resolvedType = $this->resolveType($type);

            $esMapping["proprieties"][$column] = $resolvedType;
        }

        $event->setExtraData($parameters);
    }

    public function createIndex(IndexEvent $event)
    {
        $data = $this->getClient()->indices()->create($event->getExtraData());

        $event->setExtraData($data);
    }

    public function indexExists(IndexEvent $event)
    {
        $exists = $this->getClient()->indices()->exists(array(
            "index" => $event->getIndexName(),
        ));

        if (false === $exists) {
            throw new IndexNotFoundException(sprintf("The index type '%s' doesn't exist", $event->getIndexName()));
        }
    }

    public function deleteIndex(IndexEvent $event)
    {
        $name = $event->getIndexName();
        $data = $this->getClient()->indices()->delete(array("index" => $name));
        $event->setExtraData($data);
    }

    public function persistIndexes(IndexEvent $event)
    {

    }

    protected function resolveType($type)
    {
        switch ($type) {
            case IndexMapping::TYPE_BOOLEAN:
                return ["type" => "boolean"];

            case IndexMapping::TYPE_FLOAT:
                return ["type" => "float"];

            case IndexMapping::TYPE_INTEGER:
                return ["type" => "integer"];

            case IndexMapping::TYPE_DATE:
                return ["type" => "date"];

            case IndexMapping::TYPE_DATETIME:
                return ["type" => "date_time"];

            case IndexMapping::TYPE_TIME:
                return ["type" => "time"];

            default:
            case IndexMapping::TYPE_STRING:
            case IndexMapping::TYPE_BIG_TEXT:
                return ["type" => "string", "analyzer" => "standard"];
        }
    }

    /**
     * @return \ElasticSearch\Client
     */
    protected function getClient()
    {
        return $this->getDriver()->getExtraConfiguration("client");
    }

    /**
     * @return string
     *
     * The driver code to catch the good events
     */
    public static function getDriverCode()
    {
        return ElasticSearchDriver::getCode();
    }

    /**
     * @return array
     */
    public static function getDriverEvents()
    {
        $events = parent::getDriverEvents();

        $events[DriverEvents::INDEX_CREATE][] = ["prepareIndexMapping", 128];

        return $events;
    }
}
