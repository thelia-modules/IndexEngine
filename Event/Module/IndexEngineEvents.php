<?php
/**
 * This class has been generated by TheliaStudio
 * For more information, see https://github.com/thelia-modules/TheliaStudio
 */

namespace IndexEngine\Event\Module;

use IndexEngine\Event\Module\Base\IndexEngineEvents as BaseIndexEngineEvents;

/**
 * Class IndexEngineEvents
 * @package IndexEngine\Event\Module
 * @author TheliaStudio
 */
class IndexEngineEvents extends BaseIndexEngineEvents
{
    const COLLECT_ENTITY_TYPES      = "collect.entity_types";
    const COLLECT_ENTITIES          = "collect.entities";
    const COLLECT_ENTITY_COLUMNS    = "collect.entity_columns";

    const COLLECT_DATA_TO_INDEX     = "collect.data_to_index";
}
