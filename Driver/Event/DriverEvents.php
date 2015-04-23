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

/**
 * Class DriverEvents
 * @package IndexEngine\Driver\Event
 * @author Benjamin Perche <benjamin@thelia.net>
 *
 * All the following event names aren't directly used,
 * they are suffixed by a point and the driver code.
 *
 * Example:
 * driver.get_configuration.Elasticsearch
 */
abstract class DriverEvents
{
    /**
     * This event is dispatched when we need to collect the driver's configuration
     */
    const DRIVER_GET_CONFIGURATION = "driver.get_configuration";

    /**
     * This event is dispatched when we load the configuration into the driver
     */
    const DRIVER_LOAD_CONFIGURATION = "driver.load_configuration";

    /**
     * This event is dispatched when an index has to be created
     */
    const INDEX_CREATE = "index_create";

    /**
     * This event is dispatched when we need to check that an index exists
     */
    const INDEX_EXISTS = "index_exists";

    /**
     * This event is dispatched when we have to delete an index
     */
    const INDEX_DELETE = "index_delete";

    /**
     * This event is dispatched when we want to register indexes on the server
     */
    const INDEXES_PERSIST = "indexes_persist";

    /**
     * This event is dispatched when a query has to be done
     */
    const INDEX_SEARCH_QUERY = "index_search_query";
}
