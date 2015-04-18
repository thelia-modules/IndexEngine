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
use IndexEngine\Driver\Configuration\StringVectorArgument;
use IndexEngine\Driver\DriverInterface;
use IndexEngine\Driver\Exception\MissingLibraryException;
use Thelia\Core\Template\ParserInterface;

/**
 * Class ElasticSearchDriver
 * @package IndexEngine\Driver\Bridge
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ElasticSearchDriver implements DriverInterface
{
    /**
     * @var \ElasticSearch\Client
     */
    protected $client;

    /** @var ParserInterface */
    private $parser;

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

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
            new StringVectorArgument($this->parser, "servers"),
        ]);

        $collection->setDefaults(["servers" => ["localhost:9200"]]);

        return $collection;
    }

    /**
     * @param ArgumentCollection $configuration
     * @return void
     *
     * If a configuration is provided in getConfiguration(), this method is called to
     * initialize the driver ( establish connection, load resources, ... )
     */
    public function loadConfiguration(ArgumentCollection $configuration)
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
}
