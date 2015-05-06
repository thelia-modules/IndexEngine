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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Interface EventDispatcherAwareInterface
 * @package IndexEngine\Driver
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface EventDispatcherAwareInterface
{
    /**
     * @return null|EventDispatcherInterface
     *
     * This method return the current dispatcher, or null if none has been set yet.
     */
    public function getDispatcher();

    /**
     * @param  EventDispatcherInterface $dispatcher
     * @return void
     *
     * Set the current dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher);
}
