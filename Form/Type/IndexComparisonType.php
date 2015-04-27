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

use IndexEngine\Driver\Query\Comparison;
use IndexEngine\IndexEngine;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * Class IndexComparisonType
 * @package IndexEngine\Form\Type
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexComparisonType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->replaceDefaults([
            "choices" => $this->getChoices()
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
        return "index_comparison";
    }

    public function getChoices()
    {
        return [
            Comparison::EQUAL => $this->translator->trans("Equals", [], IndexEngine::MESSAGE_DOMAIN),
            Comparison::NOT_EQUAL => $this->translator->trans("Is different from", [], IndexEngine::MESSAGE_DOMAIN),
            Comparison::LIKE => $this->translator->trans("Contains", [], IndexEngine::MESSAGE_DOMAIN),
            Comparison::LESS => $this->translator->trans("Is less than", [], IndexEngine::MESSAGE_DOMAIN),
            Comparison::LESS_EQUAL => $this->translator->trans("Is less or equal than", [], IndexEngine::MESSAGE_DOMAIN),
            Comparison::GREATER => $this->translator->trans("Is greater than", [], IndexEngine::MESSAGE_DOMAIN),
            Comparison::GREATER_EQUAL => $this->translator->trans("Is greater or equal than", [], IndexEngine::MESSAGE_DOMAIN),
        ];
    }
}
