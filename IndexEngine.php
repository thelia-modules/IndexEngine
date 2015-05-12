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

namespace IndexEngine;

use IndexEngine\DependencyInjection\Compiler\RegisterDriverPass;
use IndexEngine\DependencyInjection\Compiler\RegisterTaskPass;
use Symfony\Component\HttpKernel\DependencyInjection\RegisterListenersPass;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Module\BaseModule;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;

/**
 * Class IndexEngine
 * @package IndexEngine
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexEngine extends BaseModule
{
    const MESSAGE_DOMAIN = "indexengine";
    const ROUTER = "router.indexengine";

    public function postActivation(ConnectionInterface $con = null)
    {
        $database = new Database($con);

        $database->insertSql(null, [__DIR__."/Config/create.sql", __DIR__."/Config/insert.sql"]);
    }

    public static function getCompilers()
    {
        return [
            new RegisterDriverPass(),
            new RegisterListenersPass("index_engine.event_dispatcher", "index_engine.event_listener", "index_engine.event_subscriber"),
            new RegisterTaskPass(),
        ];
    }

    public function getHooks()
    {
        return [
            [
                "code" => "index_engine.driver.form",
                "type" => TemplateDefinition::BACK_OFFICE,
                "title" => [
                    "fr_FR" => "Formulaire de configuration des drivers d'index",
                    "en_US" => "Config form for the index drivers",
                ],
                "active" => true,
            ],
            [
                "code" => "index_engine.driver.form-javascript",
                "type" => TemplateDefinition::BACK_OFFICE,
                "title" => [
                    "fr_FR" => "Formulaire de configuration des drivers d'index - javascript",
                    "en_US" => "Config form for the index drivers - javascript",
                ],
                "active" => true,
            ],
            [
                "code" => "index_engine.index.form",
                "type" => TemplateDefinition::BACK_OFFICE,
                "title" => [
                    "fr_FR" => "Formulaire de configuration d'un index",
                    "en_US" => "Config form for an index",
                ],
                "active" => true,
            ],
            [
                "code" => "index_engine.index.form-javascript",
                "type" => TemplateDefinition::BACK_OFFICE,
                "title" => [
                    "fr_FR" => "Formulaire de configuration d'un index - javascript",
                    "en_US" => "Config form for an index - javascript",
                ],
                "active" => true,
            ],
            [
                "code" => "index_engine.index.after-columns",
                "type" => TemplateDefinition::BACK_OFFICE,
                "title" => [
                    "fr_FR" => "Formulaire de configuration d'un index - après la selection des colonnes",
                    "en_US" => "Config form for an index - after columns binding",
                ],
                "active" => true,
            ],
        ];
    }
}
