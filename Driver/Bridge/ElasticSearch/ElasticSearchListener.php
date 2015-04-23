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
use IndexEngine\Entity\IndexMapping;

/**
 * Class ElasticSearchListener
 * @package IndexEngine\Driver\Bridge\ElasticSearch
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ElasticSearchListener extends DriverEventSubscriber
{
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

    public function getDefaultConfiguration(DriverConfigurationEvent $event)
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

    public function createIndex(IndexEvent $event)
    {
        $type = $event->getType();
        $mapping = $event->getMapping();

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

        $data = $this->client->indices()->create($parameters);

        $event->setExtraData($data);
    }

    public function indexExists(IndexEvent $event)
    {
        return $this->client->indices()->exists(array(
            "index" => $this->generateIndexNameFromType($event->getType())
        ));
    }

    public function deleteIndex(IndexEvent $event)
    {
        $type = $event->getType();

        if ($this->indexExists($type)) {
            return $this->client->indices()->delete(array("index" => $this->generateIndexNameFromType($type)));
        }
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

    /**
     * @return string
     *
     * The driver code to catch the good events
     */
    public function getDriverCode()
    {
        return ElasticSearchDriver::getCode();
    }

    /**
     * @return array
     *
     * Similar to \Symfony\Component\EventDispatcher\EventSubscriberInterface::getSubscribedEvents
     * but the output will be filtered to add the driver code to the event names
     */
    public function getDriverEvents()
    {
        return [
            DriverEvents::DRIVER_GET_CONFIGURATION => [
                ["getDefaultConfiguration", 0],
            ],
            DriverEvents::DRIVER_LOAD_CONFIGURATION => [
                ["loadConfiguration", 0],
            ],
        ];
    }
}
