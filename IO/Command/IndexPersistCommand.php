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

use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Thelia\Command\ContainerAwareCommand;
use Thelia\Core\HttpFoundation\Request;

/**
 * Class IndexPersistCommand
 * @package IndexEngine\IO\Command
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexPersistCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("index:persist")
            ->setDescription("Collect the data and store it in the index server")
            ->addArgument("index-configuration", InputArgument::REQUIRED, "The index configuration code to use")
            ->addOption("dry-run", null, InputOption::VALUE_NONE, "Only collect data, do not call the server")
            ->addOption("debug", null, InputOption::VALUE_NONE, "Show the collected data")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->enterRequestScope();

        $dryRun = $input->getOption("dry-run");
        $debug = $input->getOption("debug");

        $configurationCode = $input->getArgument("index-configuration");
        $indexManager = $this->getIndexManager();
        $indexConfiguration = $indexManager->getConfigurationEntityFromCode($configurationCode);

        $driver = $indexConfiguration->getLoadedDriver();
        $dataToIndex = $indexManager->collectDataForType($indexConfiguration);

        if (false === $dryRun) {
            $driver->persistIndexes(
                $indexConfiguration->getType(),
                $indexConfiguration->getCode(),
                $indexConfiguration->getTitle(),
                $dataToIndex,
                $indexConfiguration->getMapping()
            );
        }

        if (true === $debug) {
            if (0 === $dataToIndex->count()) {
                $output->renderBlock([
                    "",
                    "There is no data to index",
                    ""
                ], "bg=blue;fg=white");
            } else {
                $tableHelper = new TableHelper();
                $dataToIndex->rewind();

                $row = $dataToIndex->current();

                $tableHelper->setHeaders(array_keys($row->getData()));

                /** @var \IndexEngine\Entity\IndexData $indexData */
                foreach ($dataToIndex as $indexData) {
                    $tableHelper->addRow($indexData->getData());
                }

                $tableHelper->render($output);
            }
        } else {
            $output->renderBlock([
                "",
                sprintf(
                    "The data for index code '%s' have been stored with the driver '%s'. %d treated rows",
                    $indexConfiguration->getCode(),
                    $driver->getCode(),
                    $dataToIndex->count()
                ),
                ""
            ], "bg=green;fg=black");
        }
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
