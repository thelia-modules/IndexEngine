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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class IndexExistsCommand
 * @package IndexEngine\IO\Command
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexExistsCommand extends IndexEngineCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName("index:exists")
            ->setDescription("Check that the index for the given index configuration exists")
            ->addArgument("index-configuration", InputArgument::REQUIRED, "The index configuration code to use")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->enterRequestScope($input);
        $configurationCode = $input->getArgument("index-configuration");

        $ret = $this->getTaskRegistry()->getTask("exists")->runFromArray(["index_configuration_code" => $configurationCode]);

        if ($ret) {
            $output->renderBlock([
                "",
                sprintf("The index for configuration '%s' exists", $configurationCode),
                "",
            ], "bg=green;fg=black");

            return 0;
        }

        $output->renderBlock([
            "",
            sprintf("The index for configuration '%s' doesn't exist", $configurationCode),
            "",
        ], "bg=red;fg=white");

        return 1;
    }
}
