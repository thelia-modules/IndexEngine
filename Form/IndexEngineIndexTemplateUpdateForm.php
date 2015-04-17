<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine\Form;

use IndexEngine\Form\Base\IndexEngineIndexTemplateUpdateForm as BaseIndexEngineIndexTemplateUpdateForm;

/**
 * Class IndexEngineIndexTemplateUpdateForm
 * @package IndexEngine\Form
 */
class IndexEngineIndexTemplateUpdateForm extends BaseIndexEngineIndexTemplateUpdateForm
{
    public function getTranslationKeys()
    {
        return array(
            "id" => "id",
            "visible" => "visible",
            "code" => "code",
            "title" => "title",
            "entity" => "entity",
            "serialized_columns" => "serialized_columns",
            "serialized_condition" => "serialized_condition",
            "index_engine_driver_configuration_id" => "index_engine_driver_configuration_id",
        );
    }
}
