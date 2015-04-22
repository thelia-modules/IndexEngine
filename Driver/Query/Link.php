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
 * Class Link
 * @package IndexEngine\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class Link
{
    const LINK_OR = "or";
    const LINK_AND = "and";
    const LINK_DEFAULT = null;
}
