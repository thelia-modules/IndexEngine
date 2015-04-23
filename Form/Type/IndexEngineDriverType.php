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

namespace IndexEngine\Form\Type;

use IndexEngine\Driver\Exception\MissingLibraryException;
use IndexEngine\IndexEngine;
use Symfony\Component\Form\AbstractType;
use IndexEngine\Driver\DriverRegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class IndexEngineDriverType
 * @package IndexEngine\Form\Type
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexEngineDriverType extends AbstractType
{
    /** @var DriverRegistryInterface  */
    private $registry;

    /** @var TranslatorInterface  */
    private $translator;

    public function __construct(DriverRegistryInterface $registry, TranslatorInterface $translator)
    {
        $this->registry = $registry;
        $this->translator = $translator;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $codes = [];

        $missingDependencyText = $this->translator->trans("Missing dependency", [], IndexEngine::MESSAGE_DOMAIN);

        foreach ($this->registry->getDrivers() as $code => $driver) {
            try {
                $driver->checkDependencies();
                $label = $code;
            } catch (MissingLibraryException $e) {
                $label = sprintf("%s (%s)", $code, $missingDependencyText);
            }

            $codes[$code] = $label;
        }

        $resolver->replaceDefaults([
            "choices" => $codes,
        ]);
    }

    public function getParent()
    {
        return "choice";
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "index_engine_driver";
    }
}
