<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine\Form\Base;

use IndexEngine\IndexEngine;
use Thelia\Form\BaseForm;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class IndexEngineIndexCreateForm
 * @package IndexEngine\Form\Base
 * @author TheliaStudio
 */
class IndexEngineIndexCreateForm extends BaseForm
{
    const FORM_NAME = "index_engine_index_create";

    public function buildForm()
    {
        $translationKeys = $this->getTranslationKeys();
        $fieldsIdKeys = $this->getFieldsIdKeys();

        $this->addVisibleField($translationKeys, $fieldsIdKeys);
        $this->addCodeField($translationKeys, $fieldsIdKeys);
        $this->addTitleField($translationKeys, $fieldsIdKeys);
        $this->addTypeField($translationKeys, $fieldsIdKeys);
        $this->addEntityField($translationKeys, $fieldsIdKeys);
        $this->addSerializedColumnsField($translationKeys, $fieldsIdKeys);
        $this->addSerializedConditionField($translationKeys, $fieldsIdKeys);
        $this->addIndexEngineDriverConfigurationIdField($translationKeys, $fieldsIdKeys);
    }

    protected function addVisibleField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("visible", "checkbox", array(
            "label" => $this->translator->trans($this->readKey("visible", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("visible", $fieldsIdKeys)],
            "required" => false,
            "constraints" => array(
            ),
            "attr" => array(
            )
        ));
    }

    protected function addCodeField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("code", "text", array(
            "label" => $this->translator->trans($this->readKey("code", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("code", $fieldsIdKeys)],
            "required" => true,
            "constraints" => array(
                new NotBlank(),
            ),
            "attr" => array(
            )
        ));
    }

    protected function addTitleField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("title", "text", array(
            "label" => $this->translator->trans($this->readKey("title", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("title", $fieldsIdKeys)],
            "required" => true,
            "constraints" => array(
                new NotBlank(),
            ),
            "attr" => array(
            )
        ));
    }

    protected function addTypeField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("type", "text", array(
            "label" => $this->translator->trans($this->readKey("type", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("type", $fieldsIdKeys)],
            "required" => true,
            "constraints" => array(
                new NotBlank(),
            ),
            "attr" => array(
            )
        ));
    }

    protected function addEntityField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("entity", "text", array(
            "label" => $this->translator->trans($this->readKey("entity", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("entity", $fieldsIdKeys)],
            "required" => true,
            "constraints" => array(
                new NotBlank(),
            ),
            "attr" => array(
            )
        ));
    }

    protected function addSerializedColumnsField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("serialized_columns", "textarea", array(
            "label" => $this->translator->trans($this->readKey("serialized_columns", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("serialized_columns", $fieldsIdKeys)],
            "required" => true,
            "constraints" => array(
                new NotBlank(),
            ),
            "attr" => array(
            )
        ));
    }

    protected function addSerializedConditionField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("serialized_condition", "textarea", array(
            "label" => $this->translator->trans($this->readKey("serialized_condition", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("serialized_condition", $fieldsIdKeys)],
            "required" => true,
            "constraints" => array(
                new NotBlank(),
            ),
            "attr" => array(
            )
        ));
    }

    protected function addIndexEngineDriverConfigurationIdField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("index_engine_driver_configuration_id", "integer", array(
            "label" => $this->translator->trans($this->readKey("index_engine_driver_configuration_id", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("index_engine_driver_configuration_id", $fieldsIdKeys)],
            "required" => true,
            "constraints" => array(
                new NotBlank(),
            ),
            "attr" => array(
            )
        ));
    }

    public function getName()
    {
        return static::FORM_NAME;
    }

    public function readKey($key, array $keys, $default = '')
    {
        if (isset($keys[$key])) {
            return $keys[$key];
        }

        return $default;
    }

    public function getTranslationKeys()
    {
        return array();
    }

    public function getFieldsIdKeys()
    {
        return array(
            "visible" => "index_engine_index_visible",
            "code" => "index_engine_index_code",
            "title" => "index_engine_index_title",
            "type" => "index_engine_index_type",
            "entity" => "index_engine_index_entity",
            "serialized_columns" => "index_engine_index_serialized_columns",
            "serialized_condition" => "index_engine_index_serialized_condition",
            "index_engine_driver_configuration_id" => "index_engine_index_index_engine_driver_configuration_id",
        );
    }
}
