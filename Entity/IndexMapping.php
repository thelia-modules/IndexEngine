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
    const TYPE_DATE = "date";
    const TYPE_TIME = "time";
    const TYPE_DATETIME = "datetime";

    private $types = [
        self::TYPE_BOOLEAN,
        self::TYPE_STRING,
        self::TYPE_INTEGER,
        self::TYPE_FLOAT,
        self::TYPE_BIG_TEXT,
        self::TYPE_DATE,
        self::TYPE_TIME,
        self::TYPE_DATETIME,
    ];

    /** @var array */
    private $mapping = array();

    /**
     * @param  array  $mapping
     * @param  string $fallbackType
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

    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param $entry
     * @param $type
     * @return mixed
     *
     * Converts the entry to the given type
     */
    public function getCastedValue($entry, $type)
    {
        switch ($type) {
            case IndexMapping::TYPE_BOOLEAN:
                $castedValue = (bool) $entry;
                break;

            case IndexMapping::TYPE_INTEGER:
                $castedValue = (int) $entry;
                break;

            case IndexMapping::TYPE_FLOAT:
                $castedValue = (float) $entry;
                break;

            case IndexMapping::TYPE_DATE:
                if ($entry instanceof \DateTime) {
                    $entry =  $entry->format("Y-m-d");
                }

                $castedValue = (string) $entry;
                break;

            case IndexMapping::TYPE_TIME:
                if ($entry instanceof \DateTime) {
                    $entry =  $entry->format("H:i:s");
                }

                $castedValue = (string) $entry;
                break;

            case IndexMapping::TYPE_DATETIME:
                if ($entry instanceof \DateTime) {
                    $entry =  $entry->format(\DateTime::ATOM);
                }

                $castedValue = (string) $entry;
                break;

            default:
            case IndexMapping::TYPE_STRING:
            case IndexMapping::TYPE_BIG_TEXT:
                $castedValue = (string) $entry;
        }

        return $castedValue;
    }
}
