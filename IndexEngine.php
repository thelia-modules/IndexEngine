<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine;

use IndexEngine\DependencyInjection\Compiler\RegisterIndexEngineListenerPass;
use IndexEngine\DependencyInjection\Compiler\RegisterDriverPass;
use Symfony\Component\HttpKernel\DependencyInjection\RegisterListenersPass;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Module\BaseModule;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;

/**
 * Class IndexEngine
 * @package IndexEngine
 */
class IndexEngine extends BaseModule
{
    const MESSAGE_DOMAIN = "indexengine";
    const ROUTER = "router.indexengine";

    public function postActivation(ConnectionInterface $con = null)
    {
        $database = new Database($con);

        $database->insertSql(null, [__DIR__ . "/Config/create.sql", __DIR__ . "/Config/insert.sql"]);
    }

    public static function getCompilers()
    {
        return [
            new RegisterDriverPass(),
            new RegisterListenersPass("index_engine.event_dispatcher", "index_engine.event_listener", "index_engine.event_subscriber"),
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
                "active" => true
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
        ];
    }
}
