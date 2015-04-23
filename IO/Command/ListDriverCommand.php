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

use IndexEngine\DependencyInjection\Compiler\RegisterDriverPass;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thelia\Command\ContainerAwareCommand;

/**
 * Class ListDriverCommand
 * @package IndexEngine\IO\Command
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ListDriverCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName("index:driver:list");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \IndexEngine\Driver\DriverRegistryInterface $registry */
        $registry = $this->getContainer()->get(RegisterDriverPass::REGISTRY_NAME);

        $table = new TableHelper();
        $table->setHeaders(["Code", "Class"]);

        foreach ($registry->getDrivers() as $code => $driver) {
            $table->addRow([$code, get_class($driver)]);
        }

        $table->render($output);
    }
}
