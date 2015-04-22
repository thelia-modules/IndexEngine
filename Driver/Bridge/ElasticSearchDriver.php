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

    private $shards;
    private $replicas;

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
        ]);

        $collection->setDefaults([
            "servers" => [static::DEFAULT_SERVER]
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
     * @return void
     *
     * This method has to create the index with the given mapping
     */
    public function createIndex($type, IndexMapping $mapping)
    {
        $parameters = array("index" => $type."_index");

        if (null !== $this->shards) {
            $parameters["body"]["settings"]["number_of_shards"] = $this->shards;
        }

        if (null !== $this->replicas) {
            $parameters["body"]["settings"]["number_of_replicas"] = $this->replicas;
        }

        $esMapping = &$parameters["body"]["mappings"][$type];

        foreach ($mapping->getMapping() as $column => $type) {
            switch ($type) {
                case $mapping::TYPE_STRING:
                    break;
            }
        }

        $this->client->indices()->create($parameters);
    }

    /**
     * @param $type
     * @param IndexDataVector $indexDataVector
     * @param IndexMapping $mapping
     * @return void
     *
     * @throws \IndexEngine\Driver\Exception\IndexDataPersistException If something goes wrong during recording
     *
     * This method is called on command and manual index launch.
     * You have to persist each IndexData entity in your search server.
     */
    public function persistIndexes($type, IndexDataVector $indexDataVector, IndexMapping $mapping)
    {

    }

    /**
     * @param IndexQueryInterface $query
     * @return \IndexEngine\Entity\IndexDataVector
     *
     * Translate the query for the search engine, execute it and return the values with a IndexData vector
     */
    public function executeQuery(IndexQueryInterface $query)
    {

    }

    /**
     * @param $type
     * @return void
     *
     * Delete the index the belong to the given type
     */
    public function deleteIndex($type)
    {

    }
}
