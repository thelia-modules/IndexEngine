<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine\Loop;

use IndexEngine\Loop\Base\IndexEngineIndex as BaseIndexEngineIndexLoop;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;

/**
 * Class IndexEngineIndex
 * @package IndexEngine\Loop
 */
class IndexEngineIndex extends BaseIndexEngineIndexLoop
{
    protected function getArgDefinitions()
    {
        $definition = parent::getArgDefinitions();
        $definition->addArgument(Argument::createAnyTypeArgument("index_type"));

        return $definition;
    }

    public function buildModelCriteria()
    {
        /** @var \IndexEngine\Model\IndexEngineIndexQuery $query */
        $query = parent::buildModelCriteria();

        if (null !== $type = $this->getIndexType()) {
            $type = array_map("trim", explode(",", $type));
            $query->filterByType($type);
        }

        return $query;
    }

    /**
     * @param LoopResultRow $row
     * @param \IndexEngine\Model\IndexEngineIndex $entryObject
     */
    protected function addMoreResults(LoopResultRow $row, $entryObject)
    {
        $row
            ->set("COLUMNS", $entryObject->getColumns())
            ->set("CONDITIONS", $entryObject->getConditions())
        ;
    }

    // HotFix
    protected function getType()
    {
        return null;
    }
}
