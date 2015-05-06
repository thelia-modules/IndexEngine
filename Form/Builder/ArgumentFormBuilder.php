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

namespace IndexEngine\Form\Builder;

use IndexEngine\Driver\Configuration\ArgumentInterface;
use IndexEngine\Driver\Configuration\EnumArgument;
use IndexEngine\Driver\Configuration\Exception\InvalidTypeException;
use IndexEngine\Driver\Configuration\FormBuilderInterface;
use IndexEngine\Driver\Configuration\VectorArgumentInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface as SfFormBuilderInterface;

/**
 * Class ArgumentFormBuilder
 * @package IndexEngine\Form\Builder
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ArgumentFormBuilder implements ArgumentFormBuilderInterface
{
    private $typeResolver;

    public function __construct(DataTransformerInterface $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    /**
     * @param  ArgumentInterface      $argument
     * @param  SfFormBuilderInterface $builder
     * @param  mixed                  $defaultValue
     * @return void
     *
     * Add the needed field(s) into the symfony form builder for the given argument
     */
    public function addField(ArgumentInterface $argument, SfFormBuilderInterface $builder, $defaultValue = null)
    {
        if ($argument instanceof FormBuilderInterface) {
            $argument->buildForm($builder);
        } else {
            $argumentLabel = $this->formatTitle($argument->getName());

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
                    "options" => [
                        "label" => $argumentLabel,
                        "required" => false,
                    ],
                ];
            } else {
                $type = $this->typeResolver->transform($argument->getType());

                if (null === $type) {
                    throw new InvalidTypeException(sprintf("Invalid argument type '%s'", $type));
                }

                $options = [
                    "label" => $argumentLabel,
                    "required" => false,
                ];

                if ($argument instanceof EnumArgument) {
                    $options["choices"] = $argument->getChoices();
                }
            }

            $currentValue = $argument->getValue();

            if (null !== $currentValue && "" !== $currentValue && [] !== $currentValue) {
                $options["data"] = $currentValue;
            } elseif (null !== $defaultValue) {
                $options["data"] = $defaultValue;
            }

            $builder->add($argument->getName(), $type, $options);
        }
    }

    protected function formatTitle($name)
    {
        return preg_replace("/[_\.\-]/", " ", ucfirst($name));
    }
}
