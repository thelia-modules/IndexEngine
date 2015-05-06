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

namespace IndexEngine\Entity;

use IndexEngine\Driver\Query\IndexQuery;

/**
 * Class IndexConfiguration
 * @package IndexEngine\Entity
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexConfiguration
{
    /** @var string */
    private $code;

    /** @var string */
    private $title;

    /** @var string */
    private $type;

    /** @var DriverConfiguration */
    private $driverConfiguration;

    /** @var string */
    private $entity;

    /** @var array */
    private $columns;

    /** @var array */
    private $criteria;

    /** @var IndexMapping */
    private $mapping;

    /** @var array */
    private $extraData;

    /**
     * @return DriverConfiguration
     */
    public function getDriverConfiguration()
    {
        return $this->driverConfiguration;
    }

    /**
     * @param  DriverConfiguration $driverConfiguration
     * @return $this
     */
    public function setDriverConfiguration(DriverConfiguration $driverConfiguration)
    {
        $this->driverConfiguration = $driverConfiguration;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtraData()
    {
        return $this->extraData;
    }

    /**
     * @param  array $extraData
     * @return $this
     */
    public function setExtraData(array $extraData)
    {
        $this->extraData = $extraData;

        return $this;
    }

    /**
     * @return IndexMapping
     */
    public function getMapping()
    {
        return $this->mapping ?: new IndexMapping();
    }

    /**
     * @param  IndexMapping $mapping
     * @return $this
     */
    public function setMapping(IndexMapping $mapping)
    {
        $this->mapping = $mapping;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param  string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param  string $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param  array $columns
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param  array $criteria
     * @return $this
     */
    public function setCriteria(array $criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @return \IndexEngine\Driver\DriverInterface
     */
    public function getLoadedDriver()
    {
        $driver = $this->driverConfiguration->getDriver();
        $driver->loadConfiguration($this->driverConfiguration->getConfiguration());

        return $driver;
    }

    /**
     * @param  string $name
     * @param  mixed  $default
     * @return mixed
     */
    public function getExtraDataEntry($name, $default = null)
    {
        if (true === $this->hasExtraDataEntry($name)) {
            return $this->extraData[$name];
        }

        return $default;
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function hasExtraDataEntry($name)
    {
        return isset($this->extraData[$name]);
    }

    /**
     * @param  string $name
     * @param  mixed  $value
     * @return $this
     */
    public function setExtraDataEntry($name, $value)
    {
        $this->extraData[$name] = $value;

        return $this;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function deleteExtraDataEntry($name)
    {
        if ($this->hasExtraDataEntry($name)) {
            unset($this->extraData[$name]);
        }

        return $this;
    }

    public function createQuery()
    {
        return new IndexQuery($this->getCode(), $this->getEntity());
    }
}
