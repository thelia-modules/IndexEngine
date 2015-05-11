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

use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;

/**
 * Interface TaskInterface
 * @package IndexEngine\Driver\Task
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface TaskInterface
{
    /**
     * @return mixed
     *
     * This method is executed when the task is called.
     */
    public function run(ArgumentCollectionInterface $parameters);

    /**
     * @return ArgumentCollectionInterface
     *
     * In this method, you have to return the needed parameters for the run method.
     */
    public function getParameters();

    /**
     * @return mixed
     */
    public function getCode();
}
