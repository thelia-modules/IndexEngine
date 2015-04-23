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

namespace IndexEngine\Driver\Event;

use IndexEngine\Driver\Configuration\ArgumentCollection;
use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class DriverConfigurationEvent
 * @package IndexEngine\Driver\Event
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class DriverConfigurationEvent extends Event
{
    /** @var ArgumentCollectionInterface */
    private $argumentCollection;

    public function __construct(ArgumentCollectionInterface $argumentCollection = null)
    {
        $this->argumentCollection = $argumentCollection ?: new ArgumentCollection();
    }

    /**
     * @return ArgumentCollectionInterface
     */
    public function getArgumentCollection()
    {
        return $this->argumentCollection;
    }

    /**
     * @param ArgumentCollectionInterface $argumentCollection
     * @return $this
     */
    public function setArgumentCollection(ArgumentCollectionInterface $argumentCollection)
    {
        $this->argumentCollection = $argumentCollection;
        return $this;
    }
}
