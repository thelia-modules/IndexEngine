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
use IndexEngine\Driver\Exception\InvalidNameException;


/**
 * Class IndexMapping
 * @package IndexEngine\Entity
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexMapping
{
    /**
     * Standard Types
     */
    const TYPE_BOOLEAN = "boolean";
    const TYPE_BIG_TEXT = "big_text";
    const TYPE_STRING = "string";
    const TYPE_INTEGER = "integer";
    const TYPE_FLOAT = "float";

    private $types = [
        self::TYPE_BOOLEAN,
        self::TYPE_STRING,
        self::TYPE_INTEGER,
        self::TYPE_FLOAT,
        self::TYPE_BIG_TEXT,
    ];

    /** @var array */
    private $mapping;

    /**
     * @param array $mapping
     * @param string $fallbackType
     * @return $this
     */
    public function setMapping(array $mapping, $fallbackType = self::TYPE_STRING)
    {
        foreach ($mapping as $name => $type) {
            if (!in_array($type, $this->types)) {
                $type = $fallbackType;
            }

            $this->mapping[$name] = $type;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param $column
     * @return bool
     */
    public function hasColumn($column)
    {
        return isset($this->mapping[$column]);
    }

    /**
     * @param $column
     * @return mixed
     *
     * @throws \IndexEngine\Driver\Exception\InvalidNameException
     */
    public function resolveType($column)
    {
        if (! $this->hasColumn($column)) {
            throw new InvalidNameException(sprintf("The mapping doesn't have any '%s' column", $column));
        }

        return $this->mapping[$column];
    }
}
