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

namespace IndexEngine\Driver\Bridge;

use Elasticsearch\Client;
use IndexEngine\Driver\Configuration\ArgumentCollection;
use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;
use IndexEngine\Driver\Configuration\BooleanArgument;
use IndexEngine\Driver\Configuration\IntegerArgument;
use IndexEngine\Driver\Configuration\StringVectorArgument;
use IndexEngine\Driver\DriverInterface;
use IndexEngine\Driver\Exception\MissingLibraryException;
use IndexEngine\Driver\Query\IndexQueryInterface;
use IndexEngine\Entity\IndexDataVector;
use IndexEngine\Entity\IndexMapping;

/**
 * Class ElasticSearchDriver
 * @package IndexEngine\Driver\Bridge
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ElasticSearchDriver implements DriverInterface
{
    const DEFAULT_SERVER = "localhost:9200";

    /**
     * @var \ElasticSearch\Client
     */
    protected $client;

    /** @var int */
    private $shards;

    /** @var int */
    private $replicas;

    /** @var bool */
    private $source;

    /**
     * @return \IndexEngine\Driver\Configuration\ArgumentCollectionInterface|null
     *
     * This method is used to build the driver configuration form.
     * Set all your the configuration fields you need here (server address, port, authentication, ...)
     *
     * If you return null, no configuration form will be generated.
     */
    public function getConfiguration()
    {
        $collection = new ArgumentCollection([
            new StringVectorArgument("servers"),
            new IntegerArgument("number_of_shards"),
            new IntegerArgument("number_of_replicas"),
            new BooleanArgument("save_source"),
        ]);

        $collection->setDefaults([
            "servers" => [static::DEFAULT_SERVER],
            "number_of_shards" => 1,
            "number_of_replicas" => 0,
            "save_source" => true,
        ]);

        return $collection;
    }

    /**
     * @param null|ArgumentCollectionInterface $configuration
     * @return void
     *
     * If a configuration is provided in getConfiguration(), this method is called to
     * initialize the driver ( establish connection, load resources, ... )
     *
     * The parameter is null when getConfiguration returns null
     */
    public function loadConfiguration(ArgumentCollectionInterface $configuration = null)
    {
        $hosts = $configuration->getArgument("servers");
        $resolvedHosts = [];

        foreach ($hosts as $host) {
            if (false !== strpos(":", $host)) {
                $currentHost = explode(":", $host, 2);
                $resolvedHosts[] = [
                    "host" => $currentHost[0],
                    "port" => $currentHost[1],
                ];
            } else {
                $resolvedHosts[] = [
                    "host" => $host,
                ];
            }
        }

        $this->client = new Client([
            "hosts" => $resolvedHosts
        ]);

        $this->shards = $configuration->getArgument("number_of_shards");
        $this->replicas = $configuration->getArgument("number_of_replicas");
        $this->source = $configuration->getArgument("save_source");
    }

    /**
     * @return string
     *
     * This method returns the driver name.
     * It must be a unique string, less than 64 characters and only composed of
     * lower and upper case letters, numbers, underscores, dashes and points.
     *
     * Example: Elasticsearch, OpenSearchServer, ...
     */
    public static function getCode()
    {
        return "Elasticsearch";
    }

    /**
     * @return void
     *
     * @throws \IndexEngine\Driver\Exception\MissingLibraryException
     *
     * It method has to check missing dependencies for the driver,
     * if one is missing, throw an exception.
     */
    public function checkDependencies()
    {
        if (! class_exists("Elasticsearch\\Client")) {
            throw MissingLibraryException::createComposer("elasticsearch/elasticsearch:~1.0", "ElasticSearch");
        }
    }

    /**
     * @param string $type
     * @param IndexMapping $mapping
     * @return mixed
     *
     * This method has to create the index with the given mapping.
     *
     * If the server return data, you should return it so it can be logged.
     * You can return anything that is serializable.
     */
    public function createIndex($type, IndexMapping $mapping)
    {
        $parameters = array("index" => $this->generateIndexNameFromType($type));

        if (null !== $this->shards) {
            $parameters["body"]["settings"]["number_of_shards"] = $this->shards;
        }

        if (null !== $this->replicas) {
            $parameters["body"]["settings"]["number_of_replicas"] = $this->replicas;
        }

        $esMapping = &$parameters["body"]["mappings"][$type];

        $esMapping["_source"] = ["enabled" => $this->source];

        foreach ($mapping->getMapping() as $column => $type) {
            $resolvedType = $this->resolveType($type);
        }

        $this->client->indices()->create($parameters);
    }

    /**
     * @param $type
     * @param IndexDataVector $indexDataVector
     * @param IndexMapping $mapping
     * @return mixed
     *
     * @throws \IndexEngine\Driver\Exception\IndexDataPersistException If something goes wrong during recording
     *
     * This method is called on command and manual index launch.
     * You have to persist each IndexData entity in your search server.
     *
     * If the server return data, you should return it so it can be logged.
     * You can return anything that is serializable.
     */
    public function persistIndexes($type, IndexDataVector $indexDataVector, IndexMapping $mapping)
    {

    }

    /**
     * @param IndexQueryInterface $query
     * @return \IndexEngine\Entity\IndexDataVector
     *
     * Translate the query for the search engine, execute it and return the values with a IndexData vector.
     *
     * Even if the response is empty, return an empty vector.
     */
    public function executeSearchQuery(IndexQueryInterface $query)
    {

    }

    /**
     * @param $type
     * @return bool
     *
     * This method checks that the index corresponding to the type exists in the server
     */
    public function indexExists($type)
    {
        return $this->client->indices()->exists(array("index" => $this->generateIndexNameFromType($type)));
    }

    /**
     * @param $type
     * @return mixed
     *
     * Delete the index the belong to the given type
     *
     * If the server return data, you should return it so it can be logged.
     * You can return anything that is serializable.
     */
    public function deleteIndex($type)
    {
        if ($this->indexExists($type)) {
            return $this->client->indices()->delete(array("index" => $this->generateIndexNameFromType($type)));
        }

        return [
            "The index can't be deleted as it doesn't exist",
            [
                "index_type" => $type,
                "index_name" => $this->generateIndexNameFromType($type)
            ]
        ];
    }

    protected function generateIndexNameFromType($type)
    {
        return $type."_index";
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
                return ["type" => "string", "analyzer" => "standard"];
        }
    }
}
