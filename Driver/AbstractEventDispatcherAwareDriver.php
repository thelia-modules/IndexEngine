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

use IndexEngine\Driver\Event\DriverConfigurationEvent;
use IndexEngine\Driver\Event\DriverEvents;
use IndexEngine\Driver\Event\IndexEvent;
use IndexEngine\Driver\Event\IndexSearchQueryEvent;
use IndexEngine\Driver\Exception\IndexNotFoundException;
use IndexEngine\Driver\Exception\InvalidNameException;
use IndexEngine\Entity\IndexDataVector;
use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;
use IndexEngine\Driver\Query\IndexQueryInterface;
use IndexEngine\Entity\IndexMapping;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AbstractEventDispatcherAwareDriver
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class AbstractEventDispatcherAwareDriver extends AbstractCollection implements DriverInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var array
     */
    private $extraConfiguration = array();

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
        /** @var DriverConfigurationEvent $event */
        $event = $this->dispatch(DriverEvents::DRIVER_GET_CONFIGURATION, new DriverConfigurationEvent());

        $collection = $event->getArgumentCollection();

        return $collection->count() > 0 ? $collection : null;
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
        $this->dispatch(DriverEvents::DRIVER_LOAD_CONFIGURATION, new DriverConfigurationEvent($configuration));
    }

    /**
     * @param string $type
     * @param string $code
     * @param string $name
     * @param IndexMapping $mapping
     * @return mixed
     *
     * This method has to create the index with the given mapping.
     *
     * If the server return data, you should return it so it can be logged.
     * You can return anything that is serializable.
     */
    public function createIndex($type, $code, $name, IndexMapping $mapping)
    {
        /** @var IndexEvent $event */
        $event = $this->dispatch(DriverEvents::INDEX_CREATE, new IndexEvent($type, $code, $name, $mapping));

        return $event->getExtraData();
    }

    /**
     * @param string $type
     * @param string $code
     * @param string $name
     * @return bool
     *
     * This method checks that the index corresponding to the type exists in the server
     */
    public function indexExists($type, $code, $name)
    {
        try {
            /** @var IndexEvent $event */
            $this->dispatch(DriverEvents::INDEX_EXISTS, new IndexEvent($type, $code, $name));
            $exists = true;
        } catch (IndexNotFoundException $e) {
            $exists = false;
        }

        return $exists;
    }

    /**
     * @param string $type
     * @param string $code
     * @param string $name
     * @return mixed
     *
     * Delete the index the belong to the given type
     *
     * If the server return data, you should return it so it can be logged.
     * You can return anything that is serializable.
     */
    public function deleteIndex($type, $code, $name)
    {
        if (true === $this->indexExists($type, $code, $name)) {
            /** @var IndexEvent $event */
            $event = $this->dispatch(DriverEvents::INDEX_DELETE, new IndexEvent($type, $code, $name));

            return $event->getExtraData();
        }

        return [
            "The index can't be deleted as it doesn't exist",
            [
                "index_type" => $type,
                "driver" => static::getCode(),
            ]
        ];
    }

    /**
     * @param string $type
     * @param string $code
     * @param string $name
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
    public function persistIndexes($type, $code, $name, IndexDataVector $indexDataVector, IndexMapping $mapping)
    {
        /** @var IndexEvent $event */
        $event = $this->dispatch(DriverEvents::INDEXES_PERSIST, new IndexEvent($type, $code, $name, $mapping, $indexDataVector));

        return $event->getExtraData();
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
        /** @var IndexSearchQueryEvent $event */
        $event = $this->dispatch(DriverEvents::INDEXES_PERSIST, new IndexSearchQueryEvent($query));

        return $event->getResults();
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
        // You can override this method if you have dependencies
    }

    /**
     * @return null|EventDispatcherInterface
     *
     * This method return the current dispatcher, or null if none has been set yet.
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @return void
     *
     * Set the current dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     *
     * Add an extra configuration in the collection
     */
    public function addExtraConfiguration($name, $value)
    {
        $name = $this->resolveString($name, __METHOD__);
        $this->extraConfiguration[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     *
     * Check if the extra configuration named $name exists
     */
    public function hasExtraConfiguration($name)
    {
        $name = $this->resolveString($name, __METHOD__);

        return isset($this->extraConfiguration[$name]);
    }

    /**
     * @param string $name
     * @return $this
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException is the extra configuration doesn't exist
     *
     * Delete the extra configuration named $name
     */
    public function deleteExtraConfiguration($name)
    {
        $name = $this->resolveString($name, __METHOD__);

        if (!$this->hasExtraConfiguration($name)) {
            throw new InvalidNameException(sprintf("The extra configuration '%s' doesn't exist", $name));
        }

        unset($this->extraConfiguration[$name]);

        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException is the extra configuration doesn't exist
     *
     * Return the extra configuration named $name
     */
    public function getExtraConfiguration($name)
    {
        $name = $this->resolveString($name, __METHOD__);

        if (!$this->hasExtraConfiguration($name)) {
            throw new InvalidNameException(sprintf("The extra configuration '%s' doesn't exist", $name));
        }

        return $this->extraConfiguration[$name];
    }

    /**
     * @return array
     *
     * Dump all the extra configuration
     */
    public function getExtraConfigurations()
    {
        return $this->extraConfiguration;
    }

    /**
     * @param $eventName
     * @param Event $event
     * @return Event the 2nd parameter event
     */
    protected function dispatch($eventName, Event $event)
    {
        return $this->dispatcher->dispatch($this->getEventName($eventName), $event);
    }

    protected function getEventName($driverEvent)
    {
        return $driverEvent . "." . static::getCode();
    }
}
