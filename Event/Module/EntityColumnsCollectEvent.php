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


/**
 * Class EntityColumnsCollectEvent
 * @package IndexEngine\Event\Module
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class EntityColumnsCollectEvent extends EntityCollectEvent
{
    private $entity;

    public function __construct($type, $entity)
    {
        $this->entity = $entity;

        parent::__construct($type);
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
