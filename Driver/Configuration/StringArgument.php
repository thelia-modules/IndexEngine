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


/**
 * Class StringArgument
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class StringArgument extends AbstractArgument
{
    /**
     * @return string
     *
     * This method return the argument type.
     * It must be one of the constants that begins with "TYPE_" defined in the interface
     */
    public function getType()
    {
        return static::TYPE_STRING;
    }
}
