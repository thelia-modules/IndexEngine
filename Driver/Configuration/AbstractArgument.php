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

namespace IndexEngine\Driver\Configuration;

use IndexEngine\Exception\InvalidArgumentException;

/**
 * Class AbstractArgument
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class AbstractArgument implements ArgumentInterface
{
    protected $value;

    private $name;

    public function __construct($name, $value = null)
    {
        if (false === $this->validateName($name)) {
            throw new InvalidArgumentException(sprintf("The given argument name for %s is not valid", __CLASS__));
        }

        $this->name = $name;

        if (null !== $value) {
            $this->setValue($value);
        }
    }

    /**
     * @return mixed
     *
     * This method return the casted value of the argument
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     *
     * This method returns the argument name.
     * It must be a unique string, less than 64 characters and only composed of
     * lower and upper case letters, numbers, underscores, dashes and points.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $value
     * @return $this
     *
     * @throws \IndexEngine\Exception\InvalidArgumentException
     *
     * This method returns the object itself.
     */
    public function setValue($value)
    {
        if (!is_string($value) || (is_object($value) && !method_exists($value, "__toString"))) {
            throw new InvalidArgumentException(sprintf(
                "Invalid argument given to %s, expected a string, %s given",
                __METHOD__,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        $value = (string) $value;

        if (null !== $regex = $this->getValidationRegex()) {
            if (! preg_match($regex, $value)) {
                throw new InvalidArgumentException(sprintf(
                    "The given value '%s' doesn't match the validation regular expression: %s",
                    $value,
                    $regex
                ));
            }
        }

        $this->value = $value;

        return $this;
    }

    protected function validateName($name)
    {
        return (bool) preg_match("/^[a-z\d\-_\.]{1,64}$/i", $name);
    }

    protected function getValidationRegex()
    {
        return;
    }
}
