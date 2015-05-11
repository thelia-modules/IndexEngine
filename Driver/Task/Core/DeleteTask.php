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

namespace IndexEngine\Driver\Task\Core;

use IndexEngine\Driver\Configuration\ArgumentCollection;
use IndexEngine\Driver\Configuration\StringArgument;
use IndexEngine\Driver\Task\TaskInterface;
use IndexEngine\Driver\Configuration\ArgumentCollectionInterface;
use IndexEngine\Manager\IndexConfigurationManagerInterface;

/**
 * Class DeleteTask
 * @package IndexEngine\Driver\Task\Core
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class DeleteTask implements TaskInterface
{
    /** @var IndexConfigurationManagerInterface  */
    private $indexConfigurationManager;

    public function __construct(IndexConfigurationManagerInterface $indexConfigurationManager) {
        $this->indexConfigurationManager = $indexConfigurationManager;
    }

    /**
     * @return mixed
     *
     * This method is executed when the task is called.
     */
    public function run(ArgumentCollectionInterface $parameters)
    {
        $code = $parameters->getArgument("index_configuration_code")->getValue();

        $configuration = $this->indexConfigurationManager->getConfigurationEntityFromCode($code);

        return $configuration->getLoadedDriver()->deleteIndex(
            $configuration->getType(),
            $configuration->getCode(),
            $configuration->getEntity()
        );
    }

    /**
     * @return ArgumentCollectionInterface
     *
     * In this method, you have to return the needed parameters for the run method.
     */
    public function getParameters()
    {
        $collection = new ArgumentCollection();
        $collection->addArgument(new StringArgument("index_configuration_code"));

        return $collection;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return "delete";
    }
}
