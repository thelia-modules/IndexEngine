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

use IndexEngine\Driver\Query\IndexQuery;
use IndexEngine\Driver\Query\IndexQueryInterface;
use IndexEngine\Entity\IndexData;
use IndexEngine\Entity\IndexDataVector;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class IndexSearchQueryEvent
 * @package IndexEngine\Driver\Event
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexSearchQueryEvent extends Event
{
    /** @var IndexDataVector */
    private $results;

    /** @var IndexQueryInterface */
    private $query;

    public function __construct(IndexQueryInterface $query, IndexDataVector $indexDataVector = null)
    {
        $this->query = $query;
        $this->results = $indexDataVector ?: new IndexDataVector();
    }

    /**
     * @param IndexData $data
     * @return $this
     */
    public function addResult(IndexData $data)
    {
        $this->results[] = $data;
        return $this;
    }

    /**
     * @return IndexDataVector
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param IndexDataVector $results
     * @return $this
     */
    public function setResults(IndexDataVector $results)
    {
        $this->results = $results;
        return $this;
    }

    /**
     * @return IndexQueryInterface
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param IndexQueryInterface $query
     * @return $this
     */
    public function setQuery(IndexQueryInterface $query)
    {
        $this->query = $query;
        return $this;
    }
}
