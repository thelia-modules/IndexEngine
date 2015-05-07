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

namespace IndexEngine\Smarty\Plugin;

use IndexEngine\Manager\IndexConfigurationManagerInterface;
use IndexEngine\Manager\SearchManagerInterface;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

/**
 * Class Index
 * @package IndexEngine\Smarty\Plugin
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class Index extends AbstractSmartyPlugin
{
    /**
     * @var SearchManagerInterface
     */
    private $searchManager;

    /**
     * @var IndexConfigurationManagerInterface
     */
    private $indexConfigurationManager;

    public function __construct(SearchManagerInterface $searchManager, IndexConfigurationManagerInterface $indexConfigurationManager)
    {
        $this->searchManager = $searchManager;
        $this->indexConfigurationManager = $indexConfigurationManager;
    }

    public function renderIndex($params)
    {
        $code = $this->getParam($params, "code");
        unset ($params["code"]);

        $configuration = $this->indexConfigurationManager->getConfigurationEntityFromCode($code);

        $results = $this->searchManager->findResultsFromParams($configuration, $params);

        return $results->toArray();
    }

    /**
     * @return SmartyPluginDescriptor[]
     */
    public function getPluginDescriptors()
    {
        return [
            new SmartyPluginDescriptor("function", "index", $this, "renderIndex"),
        ];
    }
}
