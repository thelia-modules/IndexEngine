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

use IndexEngine\Discovering\Repository\IndexableEntityRepositoryInterface;
use IndexEngine\Event\IndexEngineIndexEvents;
use IndexEngine\Event\RenderConfigurationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Template\ParserInterface;
use Thelia\Core\Template\TemplateHelper;
use IndexEngine\Discovering\Collector\SqlQuerySubscriber as SqlQueryCollector;

/**
 * Class SqlQuerySubscriber
 * @package IndexEngine\Discovering\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class SqlQuerySubscriber implements EventSubscriberInterface
{
    /** @var ParserInterface */
    private $parser;

    /** @var IndexableEntityRepositoryInterface */
    private $repository;

    public function __construct(ParserInterface $parser, IndexableEntityRepositoryInterface $repository)
    {
        $this->parser = $parser;
        $this->repository = $repository;
    }


    public function renderSqlQueryConfiguration(RenderConfigurationEvent $event)
    {
        if ($event->getType() === $type = SqlQueryCollector::TYPE) {
            // Ensure that the parser is on a back office context
            $this->parser->setTemplateDefinition(TemplateHelper::getInstance()->getActiveAdminTemplate());

            $event->addContent($this->parser->render("index-configuration/sql-query/query.html", [
                "index_type" => $type,
                "entities" => $this->repository->listIndexableEntities($type),
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
            IndexEngineIndexEvents::RENDER_CONFIGURATION_TEMPLATE => ["renderSqlQueryConfiguration"],
        ];
    }
}
