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

namespace IndexEngine\Driver\Query;

use IndexEngine\Driver\DriverInterface;

/**
 * Class IndexActiveQuery
 * @package IndexEngine\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexActiveQuery extends AbstractIndexQuery implements IndexActiveQueryInterface
{
    private $driver;

    public function __construct($type, DriverInterface $driver)
    {
        $this->driver = $driver;

        parent::__construct($type);
    }

    public static function create($type, DriverInterface $driverInterface)
    {
        return new static($type, $driverInterface);
    }

    /**
     * @return \IndexEngine\Entity\IndexDataVector
     *
     * Return the list of results matching the query
     */
    public function find()
    {
        return $this->driver->executeSearchQuery($this);
    }

    /**
     * @return null|\IndexEngine\Entity\IndexData
     *
     * Return the first result found matching the query, null if no result is found
     */
    public function findOne()
    {
        return $this->driver->executeSearchQuery($this->setLimit(1));
    }
}
