<?php
/**
 * This class has been generated by TheliaStudio
 * For more information, see https://github.com/thelia-modules/TheliaStudio
 */

namespace IndexEngine\Action;

use IndexEngine\Action\Base\IndexEngineIndexAction as  BaseIndexEngineIndexAction;
use IndexEngine\Event\IndexEngineIndexEvent;
use IndexEngine\Model\IndexEngineIndex;

/**
 * Class IndexEngineIndexAction
 * @package IndexEngine\Action
 */
class IndexEngineIndexAction extends BaseIndexEngineIndexAction
{
    protected function createOrUpdate(IndexEngineIndexEvent $event, IndexEngineIndex $model)
    {
        $event->addCondition("mapping", $event->getMapping());

        $model
            ->setColumns($event->getColumns())
            ->setConditions($event->getConditions())
        ;

        parent::createOrUpdate($event, $model);
    }
}
