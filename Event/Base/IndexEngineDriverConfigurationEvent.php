<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine\Event\Base;

use Thelia\Core\Event\ActionEvent;
use \IndexEngineDriverConfiguration;

/**
* Class IndexEngineDriverConfigurationEvent
* @package IndexEngine\Event\Base
* @author TheliaStudio
*/
class IndexEngineDriverConfigurationEvent extends ActionEvent
{
    protected $id;
    protected $driverCode;
    protected $title;
    protected $serializedConfiguration;
    protected $indexEngineDriverConfiguration;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getDriverCode()
    {
        return $this->driverCode;
    }

    public function setDriverCode($driverCode)
    {
        $this->driverCode = $driverCode;

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

    public function getSerializedConfiguration()
    {
        return $this->serializedConfiguration;
    }

    public function setSerializedConfiguration($serializedConfiguration)
    {
        $this->serializedConfiguration = $serializedConfiguration;

        return $this;
    }

    public function getIndexEngineDriverConfiguration()
    {
        return $this->indexEngineDriverConfiguration;
    }

    public function setIndexEngineDriverConfiguration(IndexEngineDriverConfiguration $indexEngineDriverConfiguration)
    {
        $this->indexEngineDriverConfiguration = $indexEngineDriverConfiguration;

        return $this;
    }
}
