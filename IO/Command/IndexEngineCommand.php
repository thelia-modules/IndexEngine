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

use IndexEngine\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Thelia\Command\ContainerAwareCommand;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Model\ConfigQuery;
use Thelia\Model\LangQuery;
use Thelia\Tools\URL;

/**
 * Class IndexEngineCommand
 * @package IndexEngine\IO\Command
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexEngineCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->addOption("locale", "l", InputOption::VALUE_REQUIRED, "The locale to work with", "en_US");
    }

    /**
     * @return \IndexEngine\Manager\IndexConfigurationManagerInterface
     */
    protected function getIndexManager()
    {
        return $this->getContainer()->get("index_engine.index_configuration_manager");
    }

    /**
     * @return \IndexEngine\Manager\ConfigurationManagerInterface
     */
    protected function getDriverManager()
    {
        return $this->getContainer()->get("index_engine.configuration.manager");
    }

    protected function enterRequestScope(InputInterface $input)
    {
        $container = $this->getContainer();

        if (!$container->isScopeActive("request")) {
            // Prepare the fake request
            $container->enterScope("request");

            $request = Request::create(ConfigQuery::getConfiguredShopUrl());
            $session = new Session(new MockArraySessionStorage());
            $locale = $input->getOption("locale");
            $lang = LangQuery::create()->findOneByLocale($locale);

            if (null === $lang) {
                throw new InvalidArgumentException(sprintf("The locale '%s' doesn't exist", $locale));
            }

            $session->setLang($lang);
            $request->setSession($session);
            $container->set("request", $request);

            $container->get("request.context")->fromRequest($request);

            // Initialize tools
            $container->get("thelia.translator");
            new URL($container);
        }
    }
}
