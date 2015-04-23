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

use IndexEngine\Entity\IndexDataVector;
use IndexEngine\Entity\IndexMapping;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class IndexEvent
 * @package IndexEngine\Driver\Event
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexEvent extends Event
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var IndexMapping
     */
    private $mapping;

    /**
     * @var IndexDataVector
     */
    private $indexDataVector;

    /**
     * @var mixed
     */
    private $extraData;

    public function __construct($type, IndexMapping $mapping = null, IndexDataVector $indexDataVector = null)
    {
        $this->type = $type;
        $this->mapping = $mapping ?: new IndexMapping();
        $this->indexDataVector = $indexDataVector ?: new IndexDataVector();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return IndexMapping
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param IndexMapping $mapping
     * @return $this
     */
    public function setMapping(IndexMapping $mapping)
    {
        $this->mapping = $mapping;
        return $this;
    }

    /**
     * @return IndexDataVector
     */
    public function getIndexDataVector()
    {
        return $this->indexDataVector;
    }

    /**
     * @param IndexDataVector $indexDataVector
     * @return $this
     */
    public function setIndexDataVector(IndexDataVector $indexDataVector)
    {
        $this->indexDataVector = $indexDataVector;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtraData()
    {
        return $this->extraData;
    }

    /**
     * @param mixed $extraData
     * @return $this
     */
    public function setExtraData($extraData)
    {
        $this->extraData = $extraData;
        return $this;
    }
}
