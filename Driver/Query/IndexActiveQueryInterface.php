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


/**
 * Interface IndexActiveQueryInterface
 * @package IndexEngine\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface IndexActiveQueryInterface extends IndexQueryInterface
{
    /**
     * @return \IndexEngine\Entity\IndexResult[]
     *
     * Return the list of results matching the query
     */
    public function find();

    /**
     * @return null|\IndexEngine\Entity\IndexResult
     *
     * Return the first result found matching the query, null if no result is found
     */
    public function findOne();
}
