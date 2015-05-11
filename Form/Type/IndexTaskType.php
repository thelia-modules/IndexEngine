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

use IndexEngine\Driver\Task\TaskRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class IndexTaskType
 * @package IndexEngine\Form\Type
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexTaskType extends AbstractType
{
    /** @var TaskRegistryInterface */
    private $taskRegistry;

    public function __construct(TaskRegistryInterface $taskRegistry)
    {
        $this->taskRegistry = $taskRegistry;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $choices = [];

        foreach ($this->taskRegistry->getTaskCodes() as $code) {
            $choices[$code] = $code;
        }

        $resolver->replaceDefaults([
            "choices" => $choices
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
        return "index_task";
    }
}
