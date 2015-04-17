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

use Symfony\Component\Form\AbstractType;
use IndexEngine\Driver\DriverRegistryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class IndexEngineDriverType
 * @package IndexEngine\Form\Type
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexEngineDriverType extends AbstractType
{
    private $registry;

    public function __construct(DriverRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->replaceDefaults([
            "choices" => $this->registry->getDriverCodes(),
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
