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

/**
 * Class IndexData
 * @package IndexEngine\Entity
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexData implements \JsonSerializable
{
    private $data = array();

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param  array $data
     * @return $this
     */
    public function setData(array $data, IndexMapping $mapping)
    {
        foreach ($mapping->getMapping() as $column => $type) {
            if (isset($data[$column])) {
                $this->data[$column] = $mapping->getCastedValue($data[$column], $type);
            }
        }

        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return json_encode($this->data);
    }
}
