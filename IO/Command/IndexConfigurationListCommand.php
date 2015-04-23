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

use IndexEngine\Model\IndexEngineDriverConfigurationQuery;
use IndexEngine\Model\Map\IndexEngineDriverConfigurationTableMap;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thelia\Command\ContainerAwareCommand;

/**
 * Class IndexConfigurationListCommand
 * @package IndexEngine\IO\Command
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexConfigurationListCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName("index:configuration:list");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configurations = IndexEngineDriverConfigurationQuery::create()
            ->select([
                IndexEngineDriverConfigurationTableMap::ID,
                IndexEngineDriverConfigurationTableMap::CODE,
                IndexEngineDriverConfigurationTableMap::TITLE,
                IndexEngineDriverConfigurationTableMap::DRIVER_CODE,
            ])
            ->find()
            ->toArray()
        ;

        $table = new TableHelper();
        $table
            ->setHeaders(["ID", "Code", "Title", "Driver code"])
            ->setRows($configurations)
            ->render($output)
        ;
    }
}
