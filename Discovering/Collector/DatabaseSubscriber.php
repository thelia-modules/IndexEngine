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

namespace IndexEngine\Discovering\Collector;

use IndexEngine\Event\Module\CollectEvent;
use IndexEngine\Event\Module\EntityCollectEvent;
use IndexEngine\Event\Module\EntityColumnsCollectEvent;
use IndexEngine\Event\Module\IndexEngineEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DatabaseSubscriber
 * @package IndexEngine\Discovering\Collector
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class DatabaseSubscriber implements EventSubscriberInterface
{
    const TYPE = "database";

    /** @var \PDO */
    private $con;

    public function __construct(\PDO $con)
    {
        $this->con = $con;
    }

    public function addDatabaseType(CollectEvent $event)
    {
        $event->add(static::TYPE);
    }

    public function collectTables(EntityCollectEvent $event)
    {
        if ($event->getType() === static::TYPE) {
            $stmt = $this->con->prepare("SHOW TABLES");
            $stmt->execute();

            foreach ($stmt->fetchAll() as $entity) {
                $event->add($entity[0]);
            }
        }
    }

    public function collectTableColumns(EntityColumnsCollectEvent $event)
    {
        if ($event->getType() === static::TYPE) {
            $entity = $event->getEntity();
            $stmt = $this->con->prepare(sprintf("SHOW COLUMNS FROM `%s`", $entity));
            $stmt->execute();

            foreach ($stmt->fetchAll() as $column) {
                $event->add($column[0]);
            }
        }
    }

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
    public static function getSubscribedEvents()
    {
        return [
            IndexEngineEvents::COLLECT_ENTITY_TYPES     => ["addDatabaseType"],
            IndexEngineEvents::COLLECT_ENTITIES         => ["collectTables"],
            IndexEngineEvents::COLLECT_ENTITY_COLUMNS   => ["collectTableColumns"],
        ];
    }
}
