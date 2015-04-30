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

namespace IndexEngine\Driver\Bridge\OpenSearchServer;

use IndexEngine\Driver\Configuration\StringArgument;
use IndexEngine\Driver\DriverEventSubscriber;
use IndexEngine\Driver\Event\DriverConfigurationEvent;
use IndexEngine\Driver\Event\IndexEvent;
use IndexEngine\Driver\Event\IndexSearchQueryEvent;

/**
 * Class OpenSearchServerListener
 * @package IndexEngine\Driver\Bridge\OpenSearchServer
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class OpenSearchServerListener extends DriverEventSubscriber
{
    /**
     * @return string
     *
     * The driver code to catch the good events
     */
    public static function getDriverCode()
    {
        return OpenSearchServerDriver::getCode();
    }

    /**
     * @param DriverConfigurationEvent $event
     *
     * This method is used to build the driver configuration form.
     * Set all your the configuration fields you need here (server address, port, authentication, ...)
     */
    public function getConfiguration(DriverConfigurationEvent $event)
    {
        $collection = $event->getArgumentCollection();

        $collection
            ->addArgument(new StringArgument("hostname"))
            ->addArgument(new StringArgument("login"))
            ->addArgument(new StringArgument("api-key"))
        ;

        $collection->setDefaults([
            "hostname" => "http://localhost:9090"
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

    }

    /**
     * @param IndexSearchQueryEvent $event
     *
     * Translate the query for the search engine, execute it and return the values with a IndexData vector.
     */
    public function executeSearchQuery(IndexSearchQueryEvent $event)
    {

    }
}
