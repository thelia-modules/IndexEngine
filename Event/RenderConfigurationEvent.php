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

namespace IndexEngine\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class RenderConfigurationEvent
 * @package IndexEngine\Event
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class RenderConfigurationEvent extends Event
{
    /** @var string */
    private $content = "";

    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function addContent($content)
    {
        $this->content .= $content;
        return $this;
    }
}
