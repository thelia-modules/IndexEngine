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
use IndexEngine\Driver\Event\IndexSearchQueryEvent;
use IndexEngine\Driver\Exception\IndexNotFoundException;
use IndexEngine\Driver\Exception\TimeoutException;
use IndexEngine\Entity\IndexData;
use IndexEngine\Entity\IndexDataVector;
use IndexEngine\Entity\IndexMapping;

/**
 * Class ElasticSearchListener
 * @package IndexEngine\Driver\Bridge\ElasticSearch
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ElasticSearchListener extends DriverEventSubscriber
{
    /**
     * @param DriverConfigurationEvent $event
     *
     * This method is used to build the driver configuration form.
     * Set all your the configuration fields you need here (server address, port, authentication, ...)
     */
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

    /**
     * @param DriverConfigurationEvent $event
     *
     * If a configuration is provided in getConfiguration(), this method is called to
     * initialize the driver ( establish connection, load resources, ... )
     */
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

    /**
     * @param IndexEvent $event
     *
     * This method prepares the basic query for creating the index in Elastic.
     *
     * It is placed before the real execution so that anyone can modify it before.
     */
    public function prepareIndexMapping(IndexEvent $event)
    {
        $code = $event->getIndexCode();
        $mapping = $event->getMapping();

        $parameters = array("index" => $code);
        $driver = $this->getDriver();

        $shards = $driver->getExtraConfiguration("number_of_shards")->getValue();
        if (null !== $shards) {
            $parameters["body"]["settings"]["number_of_shards"] = $shards;
        }

        $replicas = $driver->getExtraConfiguration("number_of_replicas")->getValue();
        if (null !== $replicas) {
            $parameters["body"]["settings"]["number_of_replicas"] = $replicas;
        }

        $esMapping = &$parameters["body"]["mappings"][$code];

        $esMapping["_source"] = ["enabled" => $driver->getExtraConfiguration("save_source")];

        foreach ($mapping->getMapping() as $column => $type) {
            $resolvedType = $this->resolveType($type);

            $esMapping["properties"][$column] = $resolvedType;
        }

        $event->setExtraData($parameters);
    }

    /**
     * @param IndexEvent $event
     *
     * This method has to create the index with the given mapping.
     *
     * If the server return data, you should set it in the extra data so it can be logged.
     * You can set anything that is serializable.
     */
    public function createIndex(IndexEvent $event)
    {
        $data = $this->getClient()->indices()->create($event->getExtraData());

        $event->setExtraData($data);
    }

    /**
     * @param IndexEvent $event
     *
     * @throws \IndexEngine\Driver\Exception\IndexNotFoundException if the index doesn't exist
     *
     * This method checks that the index corresponding to the type exists in the server
     */
    public function indexExists(IndexEvent $event)
    {
        $exists = $this->getClient()->indices()->exists(array(
            "index" => $event->getIndexCode(),
        ));

        if (false === $exists) {
            throw new IndexNotFoundException(sprintf("The index type '%s' doesn't exist", $event->getIndexName()));
        }
    }

    /**
     * @param IndexEvent $event
     *
     * Delete the index the belong to the given type
     *
     * If the server return data, you should set it in extra data so it can be logged.
     * You can set anything that is serializable.
     */
    public function deleteIndex(IndexEvent $event)
    {
        $code = $event->getIndexCode();
        $data = $this->getClient()->indices()->delete(array("index" => $code));
        $event->setExtraData($data);
    }

    /**
     * @param IndexEvent $event
     *
     * @throws \IndexEngine\Driver\Exception\IndexDataPersistException If something goes wrong during recording
     *
     * This method is called on command and manual index launch.
     * You have to persist each IndexData entity in your search server.
     *
     * If the server return data, you should set it in extra data so it can be logged.
     * You can set anything that is serializable.
     */
    public function persistIndexes(IndexEvent $event)
    {
        $params = [
            "index" => $event->getIndexName(),
            "type" => $event->getIndexCode(),
        ];

        $client = $this->getClient();

        /** @var \IndexEngine\Entity\IndexData $data */
        foreach ($event->getIndexDataVector() as $data) {
            $params["body"] = $data->getData();

            $client->index($params);
        }
    }

    /**
     * @param IndexSearchQueryEvent $event
     *
     * This method is used to translate the IndexEngine query into a ElasticSearch one.
     * The result is set into the extra content
     */
    public function prepareSearchQuery(IndexSearchQueryEvent $event)
    {

    }

    /**
     * @param IndexSearchQueryEvent $event
     *
     * Translate the query for the search engine, execute it and return the values with a IndexData vector.
     */
    public function executeSearchQuery(IndexSearchQueryEvent $event)
    {
        $query = $event->getQuery();

        $params = [
            "type" => $query->getType(),
            "index" => $query->getName(),
            "body" => $event->getExtraData()
        ];

        $results = $this->getClient()->search($params);

        if ($results["timed_out"]) {
            throw new TimeoutException("The query timed out");
        }

        $event->setResults(
            $this->filterResults($results["hits"]["hits"], $event->getMapping())
        );
    }

    public function filterResults(array $results, IndexMapping $mapping)
    {
        $resultVector = new IndexDataVector();

        foreach ($results as $result) {
            $resultVector[] = (new IndexData())->setData($result, $mapping);
        }

        return $resultVector;
    }

    /**
     * @param $type
     * @return array
     *
     * Inner method that translates IndexEngine column type to a valid ElasticSearch index type
     */
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
                return ["type" => "date", "format" => "YYYY-MM-dd"];

            case IndexMapping::TYPE_DATETIME:
                return ["type" => "date"];

            case IndexMapping::TYPE_TIME:
                return ["type" => "date", "format" => "HH:mm:ss"];

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
        $events[DriverEvents::INDEX_SEARCH_QUERY][] = ["prepareSearchQuery", 128];

        return $events;
    }
}
