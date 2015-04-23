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
            ->setName("index:create")
            ->addArgument("driver-configuration", InputArgument::REQUIRED, "The driver configuration title to use")
            ->addArgument("index-type", InputArgument::REQUIRED, "The index type to check")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configurationTitle = $input->getArgument("driver-configuration");


    }
}
