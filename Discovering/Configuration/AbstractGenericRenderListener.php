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

namespace IndexEngine\Discovering\Configuration;

use IndexEngine\Form\Type\IndexComparisonType;
use IndexEngine\Manager\IndexConfigurationManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use IndexEngine\Discovering\Repository\IndexableEntityRepositoryInterface;
use IndexEngine\Event\IndexEngineIndexEvents;
use IndexEngine\Event\RenderConfigurationEvent;
use Thelia\Core\Template\ParserInterface;
use Thelia\Core\Template\TemplateHelper;

/**
 * Class AbstractGenericRenderListener
 * @package IndexEngine\Discovering\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class AbstractGenericRenderListener implements EventSubscriberInterface
{
    /** @var ParserInterface */
    private $parser;

    /** @var IndexableEntityRepositoryInterface */
    private $repository;

    /** @var IndexComparisonType */
    private $indexComparisonType;

    /** @var IndexConfigurationManagerInterface */
    private $manager;

    public function __construct(
        ParserInterface $parser,
        IndexableEntityRepositoryInterface $repository,
        IndexComparisonType $indexComparisonType,
        IndexConfigurationManagerInterface $manager
    ) {
        $this->parser = $parser;
        $this->repository = $repository;
        $this->indexComparisonType = $indexComparisonType;
        $this->manager = $manager;
    }

    public function renderConfiguration(RenderConfigurationEvent $event)
    {
        if ($event->getType() === $type = $this->getType()) {
            // Ensure that the parser is on a back office context
            $this->parser->setTemplateDefinition(TemplateHelper::getInstance()->getActiveAdminTemplate());

            $event->addContent($this->parser->render("index-configuration/standard/entity.html", [
                "index_type" => $type,
                "entities" => $this->repository->listIndexableEntities($type),
                "comparison_choices" => $this->indexComparisonType->getChoices(),
                "index_engine_index_id" => $this->manager->getCurrentIndexId(),
            ]));
        }
    }

    public function renderConfigurationColumns(RenderConfigurationEvent $event)
    {
        if ($event->getType() === $type = $this->getType()) {
            // Ensure that the parser is on a back office context
            $this->parser->setTemplateDefinition(TemplateHelper::getInstance()->getActiveAdminTemplate());

            $event->addContent($this->parser->render("index-configuration/standard/columns.html", [
                "columns" => $this->repository->listIndexableEntityColumns($type, $event->getEntity()),
                "index_engine_index_id" => $this->manager->getCurrentIndexId(),
            ]));
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
            IndexEngineIndexEvents::RENDER_CONFIGURATION_TEMPLATE => ["renderConfiguration"],
            IndexEngineIndexEvents::RENDER_CONFIGURATION_COLUMNS_TEMPLATE => ["renderConfigurationColumns"],
        ];
    }

    /**
     * @return string The collector Type
     */
    abstract protected function getType();
}
