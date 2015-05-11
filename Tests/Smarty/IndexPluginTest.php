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

namespace IndexEngine\Tests\Smarty;

use IndexEngine\Driver\Bridge\ElasticSearch\ElasticSearchDriver;
use IndexEngine\Driver\Bridge\ElasticSearch\ElasticSearchListener;
use IndexEngine\Smarty\Plugin\Index;
use IndexEngine\Manager\SearchManager;
use IndexEngine\Manager\IndexConfigurationManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Thelia\Core\HttpFoundation\Request;
use IndexEngine\Discovering\Repository\IndexableEntityRepository;
use IndexEngine\Manager\ConfigurationManager;
use IndexEngine\Driver\DriverRegistry;


/**
 * Class IndexPluginTest
 * @package IndexEngine\Tests\Smarty
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexPluginTest extends \PHPUnit_Framework_TestCase
{
    /** @var Index */
    private $plugin;

    /** @var \IndexEngine\Driver\DriverRegistryInterface  */
    private $registry;

    private $dispatcher;

    /**
     * Dependency inferno
     */
    protected function setUp()
    {
        $this->dispatcher = new EventDispatcher();

        $elastic = new ElasticSearchDriver();
        $elastic->setDispatcher($this->dispatcher);

        $listener = new ElasticSearchListener();
        $listener->setDriver($elastic);
        $this->dispatcher->addSubscriber($listener);

        $this->registry = new DriverRegistry();
        $this->registry->addDriver($elastic);

        $request = new Request();

        $this->plugin = new Index(
            new SearchManager(),
            new IndexConfigurationManager(
                new EventDispatcher(),
                $this->dispatcher,
                $request,
                new IndexableEntityRepository($this->dispatcher),
                new ConfigurationManager($request, $this->registry)
            )
        );
    }

    public function testIndexFunctionReturnAnArray()
    {
        $result = $this->plugin->renderIndex(["code" => "unique_code", "limit" => 10, "order" => "id"]);

        $result = $result["results"];

        $this->assertInternalType("array", $result);
        $this->assertCount(10, $result);

        for ($i = 1; $i <= 10; ++$i) {
            $this->assertEquals($i, $result[$i-1]["id"]);
        }
    }
}
