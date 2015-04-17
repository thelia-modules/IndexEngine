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
 * Class EnumArgument
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class EnumArgument extends AbstractArgument
{
    private $choices;

    public function __construct($name, array $choices = array(), $value = null)
    {
        $this->choices = $choices;

        parent::__construct($name, $value);
    }

    public function setValue($value)
    {
        if (false === $key = array_search($value, $this->choices)) {
            throw new InvalidArgumentException(sprintf(
                "The given value '%s' is not valid. It must be one of: %s",
                $value,
                sprintf("'%s'", implode(", '", $this->choices))
            ));
        }

        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     *
     * This method return the argument type.
     * It must be one of the constants that begins with "TYPE_" defined in the interface
     */
    public function getType()
    {
        return static::TYPE_ENUM;
    }
}
