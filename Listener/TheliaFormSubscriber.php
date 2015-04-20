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

use IndexEngine\Driver\Configuration\ArgumentInterface;
use IndexEngine\Driver\Configuration\EnumArgument;
use IndexEngine\Driver\Configuration\Exception\InvalidTypeException;
use IndexEngine\Driver\Configuration\FormBuilderInterface;
use IndexEngine\Driver\Configuration\VectorArgumentInterface;
use IndexEngine\Manager\ConfigurationManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\DataTransformerInterface;
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

    /** @var DataTransformerInterface */
    private $typeResolver;

    public function __construct(ConfigurationManagerInterface $configurationManager, DataTransformerInterface $typeResolver)
    {
        $this->configurationManager = $configurationManager;
        $this->typeResolver = $typeResolver;
    }

    public function addDatabaseFields(TheliaFormEvent $event)
    {
        $formBuilder = $event->getForm()->getFormBuilder();
        $configuration = $this->configurationManager->getCurrentConfiguration();

        foreach ($configuration->getConfiguration()->getArguments() as $argument) {
            if ($argument instanceof FormBuilderInterface) {
                $argument->buildForm($formBuilder);
            } else {
                if ($argument instanceof VectorArgumentInterface) {
                    if (0 === preg_match("/^Vector\<([a-z_\-\.]+)\>$/", $argument->getType(), $match)) {
                        throw new InvalidTypeException(sprintf("Invalid vector type '%s'", $argument->getType()));
                    }

                    $subType = $this->typeResolver->transform($match[1]);

                    if (null === $subType) {
                        throw new InvalidTypeException(sprintf("Invalid vector subtype '%s'", $match[1]));
                    }

                    $type = "collection";
                    $options = [
                        "type" => $subType,
                        "allow_add" => true,
                        "allow_delete" => true,
                        "cascade_validation" => true,
                    ];
                } else {
                    $type = $this->typeResolver->transform($argument->getType());

                    if (null === $type) {
                        throw new InvalidTypeException(sprintf("Invalid argument type '%s'", $type));
                    }

                    $options = [];

                    if ($argument instanceof EnumArgument) {
                        $options["choices"] = $argument->getChoices();
                    }
                }

                $formBuilder->add($argument->getName(), $type, $options);
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
        ];
    }
}
