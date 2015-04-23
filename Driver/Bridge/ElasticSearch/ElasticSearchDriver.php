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

use IndexEngine\Driver\AbstractEventDispatcherAwareDriver;
use IndexEngine\Driver\Exception\MissingLibraryException;

/**
 * Class ElasticSearchEventDispatcherAwareDriver
 * @package IndexEngine\Driver\Bridge
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ElasticSearchEventDispatcherAwareDriver extends AbstractEventDispatcherAwareDriver
{
    const DEFAULT_SERVER = "localhost:9200";
    const DEFAULT_SHARDS = 1;
    const DEFAULT_REPLICAS = 0;
    const DEFAULT_SAVE_SOURCE = true;

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
