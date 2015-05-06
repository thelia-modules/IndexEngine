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

use IndexEngine\Discovering\Repository\IndexableEntityRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class IndexTypeType
 * @package IndexEngine\Form\Type
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexTypeType extends AbstractType
{
    /** @var  IndexableEntityRepositoryInterface */
    private $repository;

    public function __construct(IndexableEntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $foundTypes = $this->repository->listIndexableEntityTypes();

        $choices = [];

        foreach ($foundTypes as $type) {
            $choices[$type] = $type;
        }

        $resolver->replaceDefaults([
            "choices" => $choices,
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
        return "index_type";
    }
}
