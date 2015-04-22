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

namespace IndexEngine\Exception;

/**
 * Class BadTypeException
 * @package IndexEngine\Exception
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class BadTypeException extends \UnexpectedValueException
{
    public static function create($expectedType, $var)
    {
        return new static(sprintf(
            "The given value is not valid. Expected %s, %s given",
            is_object($expectedType) ? get_class($expectedType) : $expectedType,
            is_object($var) ? get_class($var) : gettype($var)
        ));
    }
}
