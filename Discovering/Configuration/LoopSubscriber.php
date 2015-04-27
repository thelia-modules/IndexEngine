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

namespace IndexEngine\Discovering\Configuration;

use IndexEngine\Discovering\Collector\LoopSubscriber as LoopCollector;

/**
 * Class LoopSubscriber
 * @package IndexEngine\Discovering\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class LoopSubscriber extends AbstractGenericRenderListener
{
    /**
     * @return string The collector Type
     */
    protected function getType()
    {
        return LoopCollector::TYPE;
    }
}
