<?php
/*************************************************************************************/
/* This file is part of the Thelia package.                                          */
/*                                                                                   */
/* Copyright (c) OpenStudio                                                          */
/* email : dev@thelia.net                                                            */
/* web : http://www.thelia.net                                                       */
/*                                                                                   */
/* For the full copyright and license information, please view the LICENSE.txt       */
/* file that was distributed with this source code.                                  */
/*************************************************************************************/

namespace IndexEngine\Loop;

use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\ProductQuery;
use Thelia\Type\BooleanOrBothType;

/**
 * Class ProductIndex
 * @package IndexEngine\Loop
 * @author Benjamin Perche <benjamin@thelia.net>
 *
 * This loop collects a lot of data about a product, copy some images in the public cache.
 *
 * We don't need this class to be efficient, it only has to be used in an index, not in front.
 */
class ProductIndex extends BaseLoop implements ArraySearchLoopInterface
{

    /**
     * this method returns an array
     *
     * @return array
     */
    public function buildArray()
    {

    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        $query = ProductQuery::create();

        if (BooleanOrBothType::ANY !== $visible = $this->getVisible()) {
            $query->filterByVisible($visible);
        }
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
            Argument::createBooleanOrBothTypeArgument("visible", BooleanOrBothType::ANY)
        );
    }
}
