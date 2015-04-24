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

namespace IndexEngine\IO\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thelia\Command\ContainerAwareCommand;
use Thelia\Command\Output\TheliaConsoleOutput;
use Thelia\Core\HttpFoundation\Request;


/**
 * Class IndexExistsCommand
 * @package IndexEngine\IO\Command
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexExistsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("index:exists")
            ->addArgument("driver-configuration", InputArgument::REQUIRED, "The driver configuration code to use")
            ->addArgument("index-name", InputArgument::REQUIRED, "The index name to check")
        ;
    }

    /**
     * @param InputInterface $input
     * @param TheliaConsoleOutput $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configurationCode = $input->getArgument("driver-configuration");
        $indexName = $input->getArgument("index-name");

        $configuration = $this->getManager()->getConfigurationFromCode($configurationCode, true);
        $driver = $configuration->getDriver();

        if ($driver->indexExists(null, $indexName)) {
            $output->renderBlock([
                "",
                sprintf("The index type '%s' exists with the configuration '%s'", $indexName, $configurationCode),
                ""
            ], "bg=green;fg=black");

            return 0;
        }

        $output->renderBlock([
            "",
            sprintf("The index type '%s' doesn't exist with the configuration '%s'", $indexName, $configurationCode),
            ""
        ], "bg=red;fg=white");

        return 1;
    }

    /**
     * @return \IndexEngine\Manager\ConfigurationManagerInterface
     */
    protected function getManager()
    {
        $container = $this->getContainer();

        if (!$container->isScopeActive("request")) {
            $container->enterScope("request");
            $container->set("request", new Request());
        }

        return $container->get("index_engine.configuration.manager");
    }
}
