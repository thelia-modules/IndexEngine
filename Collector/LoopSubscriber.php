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

namespace IndexEngine\Collector;

use IndexEngine\Event\Module\CollectEvent;
use IndexEngine\Event\Module\EntityCollectEvent;
use IndexEngine\Event\Module\EntityColumnsCollectEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use IndexEngine\Event\Module\IndexEngineEvents;
use Symfony\Component\HttpKernel\Log\NullLogger;

/**
 * Class LoopSubscriber
 * @package IndexEngine\Listener
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class LoopSubscriber implements EventSubscriberInterface
{
    const TYPE = "loop";

    private $loopDefinition;
    private $container;
    private $logger;

    public function __construct(array $loopDefinition, ContainerInterface $container, LoggerInterface $logger = null)
    {
        $this->loopDefinition = $loopDefinition;
        $this->container = $container;
        $this->logger = $logger ?: new NullLogger();
    }

    public function addLoopType(CollectEvent $event)
    {
        $event->add(static::TYPE);
    }

    public function collectLoops(EntityCollectEvent $event)
    {
        if ($event->getType() === static::TYPE) {
            foreach (array_keys($this->loopDefinition) as $loop) {
                $event->add($loop);
            }
        }
    }

    public function collectLoopOutputs(EntityColumnsCollectEvent $event)
    {
        if ($event->getType() === static::TYPE) {
            $loop = $event->getEntity();

            if (isset($this->loopDefinition[$loop])) {
                try {
                    $reflection = new \ReflectionClass($this->loopDefinition[$loop]);

                    /** @var \Thelia\Core\Template\Element\BaseLoop $loopInstance */
                    $loopInstance = $reflection->newInstance($this->container);
                    $loopInstance->initializeArgs(["limit" => 1]);

                    $results = $loopInstance->exec($pagination);

                    if ($results->getCount() > 0) {
                        $results->rewind();
                        $result = $results->current();

                        foreach ($result->getVars() as $variable) {
                            $event->add($variable);
                        }
                    }
                } catch (\Exception $e) {
                    $this->logger->error("An error occurred while analysing loop.", [
                        "loop" => $loop,
                        "error" => $e->getMessage()
                    ]);
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
            IndexEngineEvents::COLLECT_ENTITY_TYPES     => ["addLoopType"],
            IndexEngineEvents::COLLECT_ENTITIES         => ["collectLoops"],
            IndexEngineEvents::COLLECT_ENTITY_COLUMNS   => ["collectLoopOutputs"],
        ];
    }
}
