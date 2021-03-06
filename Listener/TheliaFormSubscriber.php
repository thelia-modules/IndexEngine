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

use IndexEngine\Driver\Task\TaskInterface;
use IndexEngine\Form\Builder\ArgumentFormBuilderInterface;
use IndexEngine\Form\IndexEngineIndexUpdateForm;
use IndexEngine\Form\IndexTaskConfigurationForm;
use IndexEngine\Manager\ConfigurationManagerInterface;
use IndexEngine\Manager\IndexConfigurationManagerInterface;
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

    /** @var IndexConfigurationManagerInterface  */
    private $indexConfigurationManager;

    public function __construct(
        ConfigurationManagerInterface $configurationManager,
        ArgumentFormBuilderInterface $argumentFormBuilder,
        IndexConfigurationManagerInterface $indexConfigurationManager
    ) {
        $this->configurationManager = $configurationManager;
        $this->argumentFormBuilder = $argumentFormBuilder;
        $this->indexConfigurationManager = $indexConfigurationManager;
    }

    public function addDatabaseFields(TheliaFormEvent $event)
    {
        $formBuilder = $event->getForm()->getFormBuilder();
        $configuration = $this->configurationManager->getCurrentConfiguration()->getConfiguration();

        $defaults = $configuration->getDefaults();

        foreach ($configuration->getArguments() as $argument) {
            $default = isset($defaults[$argument->getName()]) ? $defaults[$argument->getName()] : null;

            $this->argumentFormBuilder->addField($argument, $formBuilder, $default);
        }
    }

    public function addLoopFields(TheliaFormEvent $event)
    {
        $formBuilder = $event->getForm()->getFormBuilder();
        $configuration = $this->indexConfigurationManager->getCurrentConditions();

        if (isset($configuration["loopCriteria"]) && is_array($configuration["loopCriteria"])){
            $data = $configuration["loopCriteria"];
        } else {
            $data = [];
        }

        $formBuilder->add("loop_criteria", "collection", [
            "type" => "loop_criterion",
            "allow_add" => true,
            "allow_delete" => true,
            "data" => $data
        ]);
    }

    public function addSqlQuery(TheliaFormEvent $event)
    {
        $conditions = $this->indexConfigurationManager->getCurrentConditions();

        $event->getForm()->getFormBuilder()
            ->add("sql_query", "text", [
                "data" => isset($conditions["query"]) ? $conditions["query"] : null,
            ])
        ;
    }

    public function buildFormFromConfiguration(TheliaFormEvent $event)
    {
        $baseForm = $event->getForm();

        if (method_exists($baseForm, "getTask")) {
            $task = $baseForm->getTask();

            if ($task instanceof TaskInterface) {
                $builder = $baseForm->getFormBuilder();
                $argumentCollection = $task->getParameters();

                $defaults = $argumentCollection->getDefaults();

                foreach ($argumentCollection->getArguments() as $argument) {
                    $this->argumentFormBuilder->addField(
                        $argument,
                        $builder,
                        isset($defaults[$argument->getName()]) ? $defaults[$argument->getName()] : null
                    );
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
            TheliaEvents::FORM_AFTER_BUILD.".".IndexEngineDriverConfigurationUpdateForm::FORM_NAME => [
                ["addDatabaseFields", 128],
            ],
            TheliaEvents::FORM_AFTER_BUILD.".".IndexEngineIndexUpdateForm::FORM_NAME => [
                ["addSqlQuery", 128],
                ["addLoopFields", 128],
            ],
            TheliaEvents::FORM_AFTER_BUILD.".".IndexTaskConfigurationForm::FORM_NAME => [
                ["buildFormFromConfiguration", 128],
            ],
        ];
    }
}
