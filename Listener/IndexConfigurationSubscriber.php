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

namespace IndexEngine\Listener;

use IndexEngine\Entity\IndexMapping;
use IndexEngine\Event\IndexEngineIndexEvent;
use IndexEngine\Event\IndexEngineIndexEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class IndexConfigurationSubscriber
 * @package IndexEngine\Listener
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexConfigurationSubscriber implements EventSubscriberInterface
{
    public function filterSqlQuery(IndexEngineIndexEvent $event)
    {
        $sqlQuery = $event->sql_query;

        if (! empty($sqlQuery)) {
            $event->addCondition("query", $sqlQuery);
        }
    }

    /**
     * @param IndexEngineIndexEvent $event
     *
     * Initial filter of the conditions
     */
    public function filterConditions(IndexEngineIndexEvent $event)
    {
        $conditions = $event->getConditions();
        $event->setConditions([]);

        $event->addCondition("criteria", $conditions);
    }

    public function completeMissingMapping(IndexEngineIndexEvent $event)
    {
        $mapping = $event->getMapping();
        $mappedColumns = array_keys($mapping);

        $columns = $event->getColumns();

        $notMappedColumns = array_diff($columns, $mappedColumns);

        foreach ($notMappedColumns as $notMappedColumn) {
            $mapping[$notMappedColumn] = IndexMapping::TYPE_STRING;
        }

        $event->setMapping($mapping);
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
            IndexEngineIndexEvents::UPDATE => [
                ["filterSqlQuery", 192],
                ["completeMissingMapping", 192],
                ["filterConditions", 999],
            ],
        ];
    }
}
