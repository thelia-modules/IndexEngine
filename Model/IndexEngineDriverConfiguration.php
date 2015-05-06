<?php
/**
 * This class has been generated by TheliaStudio
 * For more information, see https://github.com/thelia-modules/TheliaStudio
 */

namespace IndexEngine\Model;

use IndexEngine\Model\Base\IndexEngineDriverConfiguration as BaseIndexEngineDriverConfiguration;

/**
 * Class IndexEngineDriverConfiguration
 * @package IndexEngine\Model
 */
class IndexEngineDriverConfiguration extends BaseIndexEngineDriverConfiguration
{
    public function getConfiguration()
    {
        $data = json_decode(base64_decode($this->getSerializedConfiguration()), true);

        if (null === $data) {
            $data = [];
        }

        return $data;
    }

    public function setConfiguration(array $conditions)
    {
        return $this->setSerializedConfiguration(base64_encode(json_encode($conditions)));
    }
}
