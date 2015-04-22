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

namespace IndexEngine\Driver\Query;

/**
 * Class Comparison
 * @package IndexEngine\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class Comparison
{
    const EQUAL = "=";
    const LIKE  = "--\_(=.=)_/--";
    const NOT_EQUAL = "<>";
    const LESSER = "<";
    const LESSER_EQUAL = "<=";
    const GREATER = ">";
    const GREATER_EQUAL = ">=";
}
