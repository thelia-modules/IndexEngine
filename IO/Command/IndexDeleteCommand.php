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
use Thelia\Core\HttpFoundation\Request;

/**
 * Class IndexDeleteCommand
 * @package IndexEngine\IO\Command
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexDeleteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("index:delete")
            ->setDescription("Deletes an index")
            ->addArgument("driver-configuration", InputArgument::REQUIRED, "The driver configuration code to use")
            ->addArgument("index-code", InputArgument::REQUIRED, "The index configuration code to use")
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $driverCode = $input->getArgument("driver-configuration");
        $indexCode = $input->getArgument("index-code");

        $configuration = $this->getManager()->getConfigurationFromCode($driverCode, true);

        $driver = $configuration->getDriver();

        if (!$driver->indexExists(null, $indexCode, $indexCode)) {
            $output->renderBlock([
                "",
                sprintf("The index code '%s' doesn't exist with the configuration '%s'", $indexCode, $driverCode),
                ""
            ], "bg=red;fg=white");

            return 1;
        }

        $configuration->getDriver()->deleteIndex(null, $indexCode, $indexCode);

        $output->renderBlock([
            "",
            sprintf("The index code '%s' has been delete with the configuration '%s'", $indexCode, $driverCode),
            ""
        ], "bg=green;fg=black");

        return 0;
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
