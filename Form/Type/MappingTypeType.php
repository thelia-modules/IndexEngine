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

use IndexEngine\Entity\IndexMapping;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MappingTypeType
 * @package IndexEngine\Form\Type
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class MappingTypeType extends AbstractType
{
    /** @var  IndexMapping */
    private $indexMapping;

    public function __construct(IndexMapping $indexMapping)
    {
        $this->indexMapping = $indexMapping;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $mapping = [];

        foreach ($this->indexMapping->getTypes() as $type) {
            $mapping[$type] = $type;
        }

        $resolver->replaceDefaults([
            "choices" => $mapping,
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
        return "index_mapping_type";
    }
}
