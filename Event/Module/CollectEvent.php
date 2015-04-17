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

namespace IndexEngine\Event\Module;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class CollectEvent
 * @package IndexEngine\Event\Module
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class CollectEvent extends Event
{
    protected $data = array();

    public function add($value)
    {
        $this->data[$value] = $value;

        return $this;
    }

    public function has($value)
    {
        return isset($this->data[$value]);
    }

    public function delete($value)
    {
        if ($this->has($value)) {
            unset($this->data[$value]);

            return true;
        }

        return false;
    }

    public function getData()
    {
        return $this->data;
    }
}
