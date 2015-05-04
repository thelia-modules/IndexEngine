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

namespace IndexEngine\Tests\Driver\Bridge;

use IndexEngine\Driver\DriverInterface;
use IndexEngine\Driver\Bridge\ElasticSearch\ElasticSearchDriver;
use IndexEngine\Driver\Bridge\ElasticSearch\ElasticSearchListener;


/**
 * Class ElasticSearchTest
 * @package IndexEngine\Tests\Driver\Bridge
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ElasticSearchTest extends BridgeTestCase
{
    public function getListener()
    {
        return new ElasticSearchListener();
    }

    /**
     * @return DriverInterface
     */
    protected function getDriver()
    {
        return new ElasticSearchDriver();
    }
}
