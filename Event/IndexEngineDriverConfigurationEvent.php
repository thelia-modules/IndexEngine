<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine\Event;

use IndexEngine\Event\Base\IndexEngineDriverConfigurationEvent as BaseIndexEngineDriverConfigurationEvent;

/**
 * Class IndexEngineDriverConfigurationEvent
 * @package IndexEngine\Event
 */
class IndexEngineDriverConfigurationEvent extends BaseIndexEngineDriverConfigurationEvent
{
    public function dumpParameters()
    {
        return $this->parameters;
    }
}
