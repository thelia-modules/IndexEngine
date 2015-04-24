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

use IndexEngine\Discovering\Collector\DatabaseSubscriber as DatabaseCollector;
use IndexEngine\Event\IndexEngineIndexEvents;
use IndexEngine\Event\RenderConfigurationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Template\ParserInterface;

/**
 * Class DatabaseSubscriber
 * @package IndexEngine\Discovering\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class DatabaseSubscriber implements EventSubscriberInterface
{
    /** @var ParserInterface */
    private $parser;

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function renderDatabaseConfiguration(RenderConfigurationEvent $event)
    {
        if ($event->getType() === DatabaseCollector::TYPE) {
            $event->addContent($this->parser->render("index-configuration/standard/database.html"));
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
            IndexEngineIndexEvents::RENDER_CONFIGURATION_TEMPLATE => ["renderDatabaseConfiguration"],
        ];
    }
}
