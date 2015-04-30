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

use IndexEngine\Driver\Event\IndexEvent;
use IndexEngine\Entity\IndexData;
use IndexEngine\Event\Module\CollectEvent;
use IndexEngine\Event\Module\IndexEngineEvents;
use IndexEngine\Manager\IndexConfigurationManagerInterface;
use IndexEngine\Manager\SqlManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


/**
 * Class SqlQuerySubscriber
 * @package IndexEngine\Discovering\Collector
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class SqlQuerySubscriber implements EventSubscriberInterface
{
    const TYPE = "sql query";

    /** @var IndexConfigurationManagerInterface  */
    private $indexManager;

    /** @var SqlManagerInterface  */
    private $sqlManager;

    public function __construct(IndexConfigurationManagerInterface $indexManager, SqlManagerInterface $sqlManager)
    {
        $this->indexManager = $indexManager;
        $this->sqlManager = $sqlManager;
    }

    public function addSqlQueryType(CollectEvent $event)
    {
        $event->add(static::TYPE);
    }

    public function collectData(IndexEvent $event)
    {
        if ($event->getType() === static::TYPE) {
            $configuration = $this->indexManager->getConfigurationEntityFromCode($event->getIndexCode());

            if (null !== $query = $configuration->getExtraDataEntry("query")) {
                $dataVector = $event->getIndexDataVector();
                $mapping = $event->getMapping();

                foreach ($this->sqlManager->executeQuery($query, PHP_INT_MAX) as $row) {
                    $dataVector[] = (new IndexData())->setData($row, $mapping);
                }
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
            IndexEngineEvents::COLLECT_ENTITY_TYPES     => ["addSqlQueryType"],
            IndexEngineEvents::COLLECT_DATA_TO_INDEX    => ["collectData"],
        ];
    }
}
