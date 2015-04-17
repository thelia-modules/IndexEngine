<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine\Event\Base;

use Thelia\Core\Event\ActionEvent;
use IndexEngine\Model\IndexEngineIndexTemplate;

/**
* Class IndexEngineIndexTemplateEvent
* @package IndexEngine\Event\Base
* @author TheliaStudio
*/
class IndexEngineIndexTemplateEvent extends ActionEvent
{
    protected $id;
    protected $visible;
    protected $code;
    protected $title;
    protected $entity;
    protected $serializedColumns;
    protected $serializedCondition;
    protected $indexEngineDriverConfigurationId;
    protected $indexEngineIndexTemplate;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getVisible()
    {
        return $this->visible;
    }

    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    public function getSerializedColumns()
    {
        return $this->serializedColumns;
    }

    public function setSerializedColumns($serializedColumns)
    {
        $this->serializedColumns = $serializedColumns;

        return $this;
    }

    public function getSerializedCondition()
    {
        return $this->serializedCondition;
    }

    public function setSerializedCondition($serializedCondition)
    {
        $this->serializedCondition = $serializedCondition;

        return $this;
    }

    public function getIndexEngineDriverConfigurationId()
    {
        return $this->indexEngineDriverConfigurationId;
    }

    public function setIndexEngineDriverConfigurationId($indexEngineDriverConfigurationId)
    {
        $this->indexEngineDriverConfigurationId = $indexEngineDriverConfigurationId;

        return $this;
    }

    public function getIndexEngineIndexTemplate()
    {
        return $this->indexEngineIndexTemplate;
    }

    public function setIndexEngineIndexTemplate(IndexEngineIndexTemplate $indexEngineIndexTemplate)
    {
        $this->indexEngineIndexTemplate = $indexEngineIndexTemplate;

        return $this;
    }
}
