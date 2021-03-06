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


/**
 * Class IndexDeleteCommand
 * @package IndexEngine\IO\Command
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexDeleteCommand extends IndexEngineCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName("index:delete")
            ->setDescription("Delete the index for the given index configuration")
            ->addArgument("index-configuration", InputArgument::REQUIRED, "The index configuration code to use")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->enterRequestScope($input);
        $configurationCode = $input->getArgument("index-configuration");

        $this->getTaskRegistry()->getTask("delete")->runFromArray(["index_configuration_code" => $configurationCode]);

        $output->renderBlock([
            "",
            sprintf("The index from '%s' has been deleted", $configurationCode),
            "",
        ], "bg=green;fg=black");
    }
}
