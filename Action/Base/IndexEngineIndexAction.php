<?php
/**
 * This class has been generated by TheliaStudio
 * For more information, see https://github.com/thelia-modules/TheliaStudio
 */

namespace IndexEngine\Action\Base;

use IndexEngine\Model\Map\IndexEngineIndexTableMap;
use IndexEngine\Event\IndexEngineIndexEvent;
use IndexEngine\Event\IndexEngineIndexEvents;
use IndexEngine\Model\IndexEngineIndexQuery;
use IndexEngine\Model\IndexEngineIndex;
use Thelia\Action\BaseAction;
use Thelia\Core\Event\ToggleVisibilityEvent;
use Propel\Runtime\Propel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;

/**
 * Class IndexEngineIndexAction
 * @package IndexEngine\Action
 * @author TheliaStudio
 */
class IndexEngineIndexAction extends BaseAction implements EventSubscriberInterface
{
    public function create(IndexEngineIndexEvent $event)
    {
        $this->createOrUpdate($event, new IndexEngineIndex());
    }

    public function update(IndexEngineIndexEvent $event)
    {
        $model = $this->getIndexEngineIndex($event);

        $this->createOrUpdate($event, $model);
    }

    public function delete(IndexEngineIndexEvent $event)
    {
        $this->getIndexEngineIndex($event)->delete();
    }

    protected function createOrUpdate(IndexEngineIndexEvent $event, IndexEngineIndex $model)
    {
        $con = Propel::getConnection(IndexEngineIndexTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            if (null !== $id = $event->getId()) {
                $model->setId($id);
            }

            if (null !== $visible = $event->getVisible()) {
                $model->setVisible($visible);
            }

            if (null !== $code = $event->getCode()) {
                $model->setCode($code);
            }

            if (null !== $title = $event->getTitle()) {
                $model->setTitle($title);
            }

            if (null !== $type = $event->getType()) {
                $model->setType($type);
            }

            if (null !== $entity = $event->getEntity()) {
                $model->setEntity($entity);
            }

            if (null !== $serializedColumns = $event->getSerializedColumns()) {
                $model->setSerializedColumns($serializedColumns);
            }

            if (null !== $serializedCondition = $event->getSerializedCondition()) {
                $model->setSerializedCondition($serializedCondition);
            }

            if (null !== $indexEngineDriverConfigurationId = $event->getIndexEngineDriverConfigurationId()) {
                $model->setIndexEngineDriverConfigurationId($indexEngineDriverConfigurationId);
            }

            $model->save($con);

            $con->commit();
        } catch (\Exception $e) {
            $con->rollback();

            throw $e;
        }

        $event->setIndexEngineIndex($model);
    }

    protected function getIndexEngineIndex(IndexEngineIndexEvent $event)
    {
        $model = IndexEngineIndexQuery::create()->findPk($event->getId());

        if (null === $model) {
            throw new \RuntimeException(sprintf(
                "The 'index_engine_index' id '%d' doesn't exist",
                $event->getId()
            ));
        }

        return $model;
    }

    public function toggleVisibility(ToggleVisibilityEvent $event)
    {
        $this->genericToggleVisibility(new IndexEngineIndexQuery(), $event);
    }

    public function beforeCreateFormBuild(TheliaFormEvent $event)
    {
    }

    public function beforeUpdateFormBuild(TheliaFormEvent $event)
    {
    }

    public function afterCreateFormBuild(TheliaFormEvent $event)
    {
    }

    public function afterUpdateFormBuild(TheliaFormEvent $event)
    {
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
        return array(
            IndexEngineIndexEvents::CREATE => array("create", 128),
            IndexEngineIndexEvents::UPDATE => array("update", 128),
            IndexEngineIndexEvents::DELETE => array("delete", 128),
            IndexEngineIndexEvents::TOGGLE_VISIBILITY => array("toggleVisibility", 128),
            TheliaEvents::FORM_BEFORE_BUILD.".index_engine_index_create" => array("beforeCreateFormBuild", 128),
            TheliaEvents::FORM_BEFORE_BUILD.".index_engine_index_update" => array("beforeUpdateFormBuild", 128),
            TheliaEvents::FORM_AFTER_BUILD.".index_engine_index_create" => array("afterCreateFormBuild", 128),
            TheliaEvents::FORM_AFTER_BUILD.".index_engine_index_update" => array("afterUpdateFormBuild", 128),
        );
    }
}
