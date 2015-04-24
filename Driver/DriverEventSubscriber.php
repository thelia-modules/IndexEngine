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
use IndexEngine\Driver\Event\DriverEvents;

/**
 * Class DriverEventSubscriber
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class DriverEventSubscriber implements DriverEventSubscriberInterface
{
    private $driver;

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    final public static function getSubscribedEvents()
    {
        $events = [];
        $driverCode = static::getDriverCode();

        foreach (static::getDriverEvents() as $key => $value) {
            $events[$key.".".$driverCode] = $value;
        }

        return $events;
    }

    /**
     * @return DriverInterface
     *
     * Get the current driver instance
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param DriverInterface $driver
     * @return void
     *
     * Set the current driver instance
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return array
     *
     * Similar to \Symfony\Component\EventDispatcher\EventSubscriberInterface::getSubscribedEvents
     * but the output will be filtered to add the driver code to the event names
     */
    public static function getDriverEvents()
    {
        return [
            DriverEvents::DRIVER_GET_CONFIGURATION => [
                ["getConfiguration", 0],
            ],
            DriverEvents::DRIVER_LOAD_CONFIGURATION => [
                ["loadConfiguration", 0],
            ],
            DriverEvents::INDEX_CREATE => [
                ["createIndex", 0],
            ],
            DriverEvents::INDEX_EXISTS => [
                ["indexExists", 0],
            ],
            DriverEvents::INDEX_DELETE => [
                ["deleteIndex", 0],
            ],
            DriverEvents::INDEXES_PERSIST => [
                ["persistIndexes", 0]
            ],
            DriverEvents::INDEX_SEARCH_QUERY => [
                ["executeSearchQuery", 0]
            ]
        ];
    }
}
