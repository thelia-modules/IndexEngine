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

namespace IndexEngine\Driver\Task;


/**
 * Class AbstractTask
 * @package IndexEngine\Driver\Task
 * @author Benjamin Perche <benjamin@thelia.net>
 */
abstract class AbstractTask implements TaskInterface
{
    public function runFromArray(array $parameters)
    {
        $parameters = $this->getParameters()->loadValues($parameters);

        return $this->run($parameters);
    }
}
