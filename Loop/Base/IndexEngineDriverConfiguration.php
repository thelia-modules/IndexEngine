<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace IndexEngine\Loop\Base;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Type\BooleanOrBothType;
use \IndexEngineDriverConfigurationQuery;

/**
 * Class IndexEngineDriverConfiguration
 * @package IndexEngine\Loop\Base
 * @author TheliaStudio
 */
class IndexEngineDriverConfiguration extends BaseLoop implements PropelSearchLoopInterface
{

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \\IndexEngineDriverConfiguration $entry */
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow($entry);

            $row
                ->set("ID", $entry->getId())
                ->set("DRIVER_CODE", $entry->getDriverCode())
                ->set("TITLE", $entry->getTitle())
                ->set("SERIALIZED_CONFIGURATION", $entry->getSerializedConfiguration())
            ;

            $this->addMoreResults($row, $entry);

            $loopResult->addRow($row);
        }

        return $loopResult;
    }

    /**
     * Definition of loop arguments
     *
     * example :
     *
     * public function getArgDefinitions()
     * {
     *  return new ArgumentCollection(
     *
     *       Argument::createIntListTypeArgument('id'),
     *           new Argument(
     *           'ref',
     *           new TypeCollection(
     *               new Type\AlphaNumStringListType()
     *           )
     *       ),
     *       Argument::createIntListTypeArgument('category'),
     *       Argument::createBooleanTypeArgument('new'),
     *       ...
     *   );
     * }
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument("id"),
            Argument::createAnyTypeArgument("driver_code"),
            Argument::createAnyTypeArgument("title"),
            Argument::createEnumListTypeArgument(
                "order",
                [
                    "id",
                    "id-reverse",
                    "driver_code",
                    "driver_code-reverse",
                    "title",
                    "title-reverse",
                    "serialized_configuration",
                    "serialized_configuration-reverse",
                ],
                "id"
            )
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $query = new IndexEngineDriverConfigurationQuery();

        if (null !== $id = $this->getId()) {
            $query->filterById($id);
        }

        if (null !== $driver_code = $this->getDriverCode()) {
            $driver_code = array_map("trim", explode(",", $driver_code));
            $query->filterByDriverCode($driver_code);
        }

        if (null !== $title = $this->getTitle()) {
            $title = array_map("trim", explode(",", $title));
            $query->filterByTitle($title);
        }

        foreach ($this->getOrder() as $order) {
            switch ($order) {
                case "id":
                    $query->orderById();
                    break;
                case "id-reverse":
                    $query->orderById(Criteria::DESC);
                    break;
                case "driver_code":
                    $query->orderByDriverCode();
                    break;
                case "driver_code-reverse":
                    $query->orderByDriverCode(Criteria::DESC);
                    break;
                case "title":
                    $query->orderByTitle();
                    break;
                case "title-reverse":
                    $query->orderByTitle(Criteria::DESC);
                    break;
                case "serialized_configuration":
                    $query->orderBySerializedConfiguration();
                    break;
                case "serialized_configuration-reverse":
                    $query->orderBySerializedConfiguration(Criteria::DESC);
                    break;
            }
        }

        return $query;
    }

    protected function addMoreResults(LoopResultRow $row, $entryObject)
    {
    }
}
