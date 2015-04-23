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

namespace IndexEngine\Driver;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Interface DriverEventSubscriberInterface
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface DriverEventSubscriberInterface extends EventSubscriberInterface
{
    /**
     * @return string
     *
     * The driver code to catch the good events
     */
    public function getDriverCode();

    /**
     * @return array
     *
     * Similar to \Symfony\Component\EventDispatcher\EventSubscriberInterface::getSubscribedEvents
     * but the output will be filtered to add the driver code to the event names
     */
    public function getDriverEvents();
}
