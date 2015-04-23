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

namespace IndexEngine\Driver;

use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;
use IndexEngine\Driver\Query\IndexQueryInterface;
use IndexEngine\Entity\IndexDataVector;
use IndexEngine\Entity\IndexMapping;

/**
 * Interface DriverInterface
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface DriverInterface
{
    /**
     * @return \IndexEngine\Driver\Configuration\ArgumentCollectionInterface|null
     *
     * This method is used to build the driver configuration form.
     * Set all your the configuration fields you need here (server address, port, authentication, ...)
     *
     * If you return null, no configuration form will be generated.
     */
    public function getConfiguration();

    /**
     * @param null|ArgumentCollectionInterface $configuration
     * @return void
     *
     * If a configuration is provided in getConfiguration(), this method is called to
     * initialize the driver ( establish connection, load resources, ... )
     *
     * The parameter is null when getConfiguration returns null
     */
    public function loadConfiguration(ArgumentCollectionInterface $configuration = null);

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
    public function createIndex($type, IndexMapping $mapping);

    /**
     * @param $type
     * @return bool
     *
     * This method checks that the index corresponding to the type exists in the server
     */
    public function indexExists($type);

    /**
     * @param $type
     * @return mixed
     *
     * Delete the index the belong to the given type
     *
     * If the server return data, you should return it so it can be logged.
     * You can return anything that is serializable.
     */
    public function deleteIndex($type);

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
    public function persistIndexes($type, IndexDataVector $indexDataVector, IndexMapping $mapping);

    /**
     * @param IndexQueryInterface $query
     * @return \IndexEngine\Entity\IndexDataVector
     *
     * Translate the query for the search engine, execute it and return the values with a IndexData vector.
     *
     * Even if the response is empty, return an empty vector.
     */
    public function executeSearchQuery(IndexQueryInterface $query);

    /**
     * @return string
     *
     * This method returns the driver name.
     * It must be a unique string, less than 64 characters and only composed of
     * lower and upper case letters, numbers, underscores, dashes and points.
     *
     * Example: Elasticsearch, OpenSearchServer, ...
     */
    public static function getCode();

    /**
     * @return void
     *
     * @throws \IndexEngine\Driver\Exception\MissingLibraryException
     *
     * It method has to check missing dependencies for the driver,
     * if one is missing, throw an exception.
     */
    public function checkDependencies();
}
