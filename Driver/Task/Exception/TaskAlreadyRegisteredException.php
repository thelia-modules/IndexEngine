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

namespace IndexEngine\Driver\Task\Exception;

use IndexEngine\Exception\InvalidArgumentException;

/**
 * Class TaskAlreadyRegisteredException
 * @package IndexEngine\Driver\Task\Exception
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class TaskAlreadyRegisteredException extends InvalidArgumentException
{
}