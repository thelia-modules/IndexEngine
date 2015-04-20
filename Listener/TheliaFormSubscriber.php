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

use IndexEngine\Form\Builder\ArgumentFormBuilderInterface;
use IndexEngine\Manager\ConfigurationManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\TheliaEvents;
use IndexEngine\Form\IndexEngineDriverConfigurationUpdateForm;
use Thelia\Core\Event\TheliaFormEvent;


/**
 * Class TheliaFormSubscriber
 * @package IndexEngine\Listener
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class TheliaFormSubscriber implements EventSubscriberInterface
{
    /** @var ConfigurationManagerInterface  */
    private $configurationManager;

    /** @var   */
    private $argumentFormBuilder;

    public function __construct(
        ConfigurationManagerInterface $configurationManager,
        ArgumentFormBuilderInterface $argumentFormBuilder
    ) {
        $this->configurationManager = $configurationManager;
        $this->argumentFormBuilder = $argumentFormBuilder;
    }

    public function addDatabaseFields(TheliaFormEvent $event)
    {
        $formBuilder = $event->getForm()->getFormBuilder();
        $configuration = $this->configurationManager->getCurrentConfiguration();

        foreach ($configuration->getConfiguration()->getArguments() as $argument) {
            $this->argumentFormBuilder->addField($argument, $formBuilder);
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
            TheliaEvents::FORM_AFTER_BUILD.".".IndexEngineDriverConfigurationUpdateForm::FORM_NAME => [
                ["addDatabaseFields", 128],
            ],
        ];
    }
}
