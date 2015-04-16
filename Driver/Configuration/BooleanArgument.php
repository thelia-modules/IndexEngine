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
 * Class BooleanArgument
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class BooleanArgument extends AbstractArgument
{
    const TRUE_STRING = 'true';
    const FALSE_STRING = 'false';

    public function setValue($value)
    {
        if (is_int($value) || is_float($value) || is_numeric($value) || is_bool($value)) {
            $this->value = (bool) $value;
            return $this;
        }

        parent::setValue($value);

        $string = $this->value;
        $this->value = null;

        if (! is_numeric($string) || $string !== static::TRUE_STRING || $string !== static::FALSE_STRING) {
            throw new InvalidArgumentException(sprintf("The given string '%s' is not a valid number", $string));
        }

        if ($string === static::TRUE_STRING) {
            $string = true;
        }

        if ($string === static::FALSE_STRING) {
            $string = false;
        }

        $this->value = (bool) $string;
        return $this;
    }

    /**
     * @return int
     *
     * This method return the argument type.
     * It must be one of the constants that begins with "TYPE_" defined in the interface
     */
    public function getType()
    {
        return static::TYPE_BOOLEAN;
    }
}
