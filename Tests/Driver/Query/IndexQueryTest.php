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

namespace IndexEngine\Tests\Driver\Query;

use IndexEngine\Driver\Query\IndexQuery;
use IndexEngine\Driver\Query\Link;

/**
 * Class IndexQueryTest
 * @package IndexEngine\Tests\Driver\Query
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterByMethodAddsACriterionGroup()
    {
        $query = new IndexQuery("foo", "bar");

        $this->assertCount(0, $query->getCriterionGroups());
        $query->filterBy("foo", "bar");

        $this->assertCount(1, $query->getCriterionGroups());
        $query->filterBy("for", "baz");

        $this->assertCount(2, $query->getCriterionGroups());
    }

    public function testOrAndAndMethodsReplacesLastOccurrence()
    {
        $query = new IndexQuery("foo", "bar");

        $query->_or()->_and();

        $query->filterBy("foo", "bar");
        $this->assertEquals(Link::LINK_AND, $query->getCriterionGroups()[0][1]);

        $query->_or();

        $this->assertEquals(Link::LINK_OR, $query->getCriterionGroups()[0][1]);
    }
}
