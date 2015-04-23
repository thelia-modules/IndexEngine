<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine\Form;

use IndexEngine\Form\Base\IndexEngineDriverConfigurationUpdateForm as BaseIndexEngineDriverConfigurationUpdateForm;

/**
 * Class IndexEngineDriverConfigurationUpdateForm
 * @package IndexEngine\Form
 */
class IndexEngineDriverConfigurationUpdateForm extends BaseIndexEngineDriverConfigurationUpdateForm
{
    public function getTranslationKeys()
    {
        return array(
            "title" => "Title",
            "code" => "Code",
        );
    }

    public function buildForm()
    {
        parent::buildForm();

        $this->formBuilder->remove("driver_code");
    }
}
