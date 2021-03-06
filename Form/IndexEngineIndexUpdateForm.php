<?php
/**
 * This class has been generated by TheliaStudio
 * For more information, see https://github.com/thelia-modules/TheliaStudio
 */

namespace IndexEngine\Form;

use IndexEngine\Form\Base\IndexEngineIndexUpdateForm as BaseIndexEngineIndexUpdateForm;
use IndexEngine\IndexEngine;
use IndexEngine\Model\IndexEngineIndexQuery;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Class IndexEngineIndexUpdateForm
 * @package IndexEngine\Form
 */
class IndexEngineIndexUpdateForm extends BaseIndexEngineIndexUpdateForm
{
    public function getTranslationKeys()
    {
        return array(
            "code" => "Code",
            "title" => "Title",
            "index_engine_driver_configuration_id" => "Driver configuration",
        );
    }

    public function buildForm()
    {
        parent::buildForm();

        $this->formBuilder
            ->add("columns", "collection", [
                "type" => "text",
                "allow_add" => true,
                "allow_delete" => true,
            ])
            ->add("conditions", "collection", [
                "type" => "index_condition",
                "allow_add" => true,
                "allow_delete" => true,
            ])
            ->add("mapping", "collection", [
                "type" => "index_mapping",
                "allow_add" => true,
                "allow_delete" => true,
            ])
        ;
    }

    protected function addCodeField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("code", "text", array(
            "label" => $this->translator->trans($this->readKey("code", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("code", $fieldsIdKeys)],
            "required" => true,
            "constraints" => array(
                new NotBlank(),
                new Callback([
                    "methods" => [
                        [$this, "checkDuplicateCode"],
                    ],
                ]),
            ),
            "attr" => array(
            ),
        ));
    }

    public function checkDuplicateCode($value, ExecutionContextInterface $context)
    {
        $index = IndexEngineIndexQuery::create()->findOneByCode($value);
        $indexId = $this->getForm()->getData()["id"];

        if (null !== $index && $indexId != $index->getId()) {
            $context->addViolation(
                $this->translator->trans("The code %code already exists", ["%code" => $value], IndexEngine::MESSAGE_DOMAIN)
            );
        }
    }

    protected function addEntityField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("entity", "text", array(
            "label" => $this->translator->trans($this->readKey("entity", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("entity", $fieldsIdKeys)],
        ));
    }

    protected function addTypeField(array $translationKeys, array $fieldsIdKeys)
    {
        $this->formBuilder->add("type", "index_type", array(
            "label" => $this->translator->trans($this->readKey("type", $translationKeys), [], IndexEngine::MESSAGE_DOMAIN),
            "label_attr" => ["for" => $this->readKey("type", $fieldsIdKeys)],
            "required" => true,
            "constraints" => array(
                new NotBlank(),
            ),
        ));
    }
}
