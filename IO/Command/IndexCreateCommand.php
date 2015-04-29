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
use Thelia\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thelia\Core\HttpFoundation\Request;

/**
 * Class IndexCreateCommand
 * @package IndexEngine\IO\Command
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("index:create")
            ->setDescription("Create the index for the given index configuration")
            ->addArgument("index-configuration", InputArgument::REQUIRED, "The index configuration code to use")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->enterRequestScope();
        $configurationCode = $input->getArgument("index-configuration");

        $indexConfiguration = $this->getIndexManager()->getConfigurationEntityFromCode($configurationCode);

        $driver = $indexConfiguration->getLoadedDriver();

        $driver->createIndex(
            $indexConfiguration->getType(),
            $code = $indexConfiguration->getCode(),
            $indexConfiguration->getTitle(),
            $indexConfiguration->getMapping()
        );

        $output->renderBlock([
            "",
            sprintf("The index '%s' has been created", $code),
            ""
        ], "bg=green;fg=black");
    }


    /**
     * @return \IndexEngine\Manager\IndexConfigurationManagerInterface
     */
    protected function getIndexManager()
    {
        return $this->getContainer()->get("index_engine.index_configuration_manager");
    }

    protected function enterRequestScope()
    {
        $container = $this->getContainer();

        if (!$container->isScopeActive("request")) {
            $container->enterScope("request");
            $container->set("request", new Request());
        }
    }
}
