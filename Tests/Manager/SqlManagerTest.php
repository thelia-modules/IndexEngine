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

namespace IndexEngine\Tests\Manager;

use IndexEngine\Driver\Query\Comparison;
use IndexEngine\Driver\Query\Criterion\Criterion;
use IndexEngine\Driver\Query\Criterion\CriterionGroup;
use IndexEngine\Driver\Query\IndexQuery;
use IndexEngine\Manager\SqlManager;
use Thelia\Core\Translation\Translator;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class SqlManagerTest
 * @package IndexEngine\Tests\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class SqlManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var SqlManager */
    protected $manager;

    public function setUp()
    {
        $this->manager = new SqlManager(new Translator(new Container()), new \PDO("sqlite::memory:"));
    }

    public function testHandleQueryTransformationWithoutWhereNorLimit()
    {
        $query = new IndexQuery("database", "foo");
        $sqlQuery = $this->manager->buildSqlQuery($query, ["bar", "baz"]);

        $this->assertEquals("SELECT `bar`,`baz` FROM `foo`", $sqlQuery);
    }

    public function testHandleQueryTransformationWithoutWhere()
    {
        $query = new IndexQuery("database", "foo");
        $query->setLimit(1);

        $sqlQuery = $this->manager->buildSqlQuery($query, ["bar", "baz"]);

        $this->assertEquals("SELECT `bar`,`baz` FROM `foo` LIMIT 1", $sqlQuery);
    }

    public function testHandleQueryTransformationWithOneConditions()
    {
        $query = new IndexQuery("database", "foo");
        $query->filterBy("bar", 4);

        $sqlQuery = $this->manager->buildSqlQuery($query, ["bar", "baz"]);

        $this->assertEquals("SELECT `bar`,`baz` FROM `foo` WHERE (`bar` = \"4\")", $sqlQuery);
    }

    public function testHandleQueryTransformationWithTwoConditions()
    {
        $query = new IndexQuery("database", "foo");
        $query
            ->filterBy("bar", 4)
            ->filterBy("baz", 2, Comparison::LESS)
        ;

        $sqlQuery = $this->manager->buildSqlQuery($query, ["bar", "baz"]);

        $this->assertEquals("SELECT `bar`,`baz` FROM `foo` WHERE (`bar` = \"4\") AND (`baz` < \"2\")", $sqlQuery);
    }

    public function testHandleQueryTransformationWithTwoConditionsAndOrLink()
    {
        $query = new IndexQuery("database", "foo");
        $query
            ->_or()
            ->filterBy("bar", 4)
            ->filterBy("baz", 2, Comparison::LESS)
        ;

        $sqlQuery = $this->manager->buildSqlQuery($query, ["bar", "baz"]);

        $this->assertEquals("SELECT `bar`,`baz` FROM `foo` WHERE (`bar` = \"4\") OR (`baz` < \"2\")", $sqlQuery);
    }

    public function testHandleQueryTransformationWithThreeConditionsAndOrLink()
    {
        $query = new IndexQuery("database", "foo");
        $query
            ->filterBy("bar", 4)
            ->_or()
            ->filterBy("baz", 2, Comparison::LESS)
            ->filterBy("baz", 0, Comparison::GREATER_EQUAL)
        ;

        $sqlQuery = $this->manager->buildSqlQuery($query, ["bar", "baz"]);

        $this->assertEquals("SELECT `bar`,`baz` FROM `foo` WHERE (`bar` = \"4\") AND (`baz` < \"2\") OR (`baz` >= \"0\")", $sqlQuery);
    }

    public function testHandleQueryTransformationWithOneCustomCriterionGroup()
    {
        $query = new IndexQuery("database", "foo");
        $criterionGroup = new CriterionGroup();

        $criterionGroup
            ->addCriterion(new Criterion("bar", 4))
            ->addCriterion(new Criterion("baz", 2, Comparison::LESS))
        ;

        $query->addCriterionGroup($criterionGroup);

        $sqlQuery = $this->manager->buildSqlQuery($query, ["bar", "baz"]);

        $this->assertEquals("SELECT `bar`,`baz` FROM `foo` WHERE (`bar` = \"4\" AND `baz` < \"2\")", $sqlQuery);
    }

    public function testHandleQueryTransformationWithOneCustomCriterionGroupAndAFilter()
    {
        $query = new IndexQuery("database", "foo");
        $criterionGroup = new CriterionGroup();

        $criterionGroup
            ->addCriterion(new Criterion("bar", 4))
            ->addCriterion(new Criterion("baz", 2, Comparison::LESS))
        ;

        $query
            ->_or()
            ->addCriterionGroup($criterionGroup)
            ->filterBy("baz", 0, Comparison::GREATER_EQUAL)
        ;

        $sqlQuery = $this->manager->buildSqlQuery($query, ["bar", "baz"]);

        $this->assertEquals("SELECT `bar`,`baz` FROM `foo` WHERE (`bar` = \"4\" AND `baz` < \"2\") OR (`baz` >= \"0\")", $sqlQuery);
    }
}
