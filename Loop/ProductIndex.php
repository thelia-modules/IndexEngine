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

use Thelia\Core\Event\Image\ImageEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\ConfigQuery;
use Thelia\Model\Country;
use Thelia\Model\CurrencyQuery;
use Thelia\Model\LangQuery;
use Thelia\Model\Map\LangTableMap;
use Thelia\Model\ProductImageQuery;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductSaleElements;
use Thelia\TaxEngine\Calculator;
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
    /** @var  Country The shop country */
    protected $country;

    /** @var  Calculator */
    protected $calculator;

    protected $currencies;

    protected $locales;

    /**
     * this method returns an array
     *
     * @return array
     */
    public function buildArray()
    {
        // Initialization
        $query = ProductQuery::create();
        $this->country = Country::getShopLocation();
        $this->calculator = new Calculator();
        $this->currencies = CurrencyQuery::create()->find();
        $this->locales = $this->getExistingLocales();

        // Loop application
        if (BooleanOrBothType::ANY !== $visible = $this->getVisible()) {
            $query->filterByVisible($visible);
        }

        $limit = $this->getLimit();

        // Array building
        $results = [];

        /** @var \Thelia\Model\Product $product */
        foreach ($query->find() as $i => $product) {
            if ($i >= $limit) {
                break;
            }

            $row = &$results[];
            $productPrices = [];

            // Add descriptive
            foreach ($this->locales as $locale) {
                $translation = $product->getTranslation($locale);

                $row["url_".$locale] = $product->getUrl($locale);
                $row["title_".$locale] =  $translation->getTitle();
                $row["description_".$locale] =  $translation->getDescription();
            }

            $row["id"] = $product->getId();
            $row["ref"] = $product->getRef();

            $row["attribute"] = "";
            $row["attribute_av"] = "";

            // Handle PSE / price information
            /** @var \Thelia\Model\ProductSaleElements $pse */
            foreach ($product->getProductSaleElementss() as $pse) {
                $this->handleProductSaleElements($row, $productPrices, $pse);
            }

            foreach ($productPrices as $currencyCode => $productPrice) {
                $row["price_".$currencyCode] = $productPrice;
            }

            // Them add image url
            $image = ProductImageQuery::create()
                ->filterByProduct($product)
                ->orderByPosition()
                ->findOne()
            ;

            if (null !== $image) {
                $event = new ImageEvent();
                $width = $this->getWidth();
                $height = $this->getHeight();

                if (! is_null($width)) {
                    $event->setWidth($width);
                }
                if (! is_null($height)) {
                    $event->setHeight($height);
                }

                $sourceFilePath = sprintf(
                    "%s%s/%s/%s",
                    THELIA_ROOT,
                    ConfigQuery::read('images_library_path', 'local'.DS.'media'.DS.'images'),
                    "product",
                    $image->getFile()
                );

                $event->setSourceFilepath($sourceFilePath);
                $event->setCacheSubdirectory("product");

                $this->container->get("event_dispatcher")->dispatch(TheliaEvents::IMAGE_PROCESS, $event);

                $row["image_url"] = $event->getFileUrl();
            } else {
                $row["image_url"] = null;
            }
        }

        return $results;
    }

    protected function getExistingLocales()
    {
        return LangQuery::create()
            ->select([LangTableMap::LOCALE])
            ->find()
            ->toArray()
        ;
    }

    protected function handleProductSaleElements(array &$row, array &$prices, ProductSaleElements $pse)
    {
        /** @var \Thelia\Model\Currency $currency */
        foreach ($this->currencies as $currency) {
            $price = $pse->getPricesByCurrency($currency);
            $currentPrice = $pse->getPromo() ? $price->getPromoPrice() : $price->getPrice();

            if (! isset($prices[$currency->getCode()]) || $prices[$currency->getCode()] > $currentPrice) {
                $prices[$currency->getCode()] = $currentPrice;
            }
        }

        if ($pse->getIsDefault()) {
            $row["is_new"] = $pse->getNewness();
            $row["promo"] = $pse->getPromo();
        }

        /** @var \Thelia\Model\AttributeCombination $attributeCombination */
        foreach ($pse->getAttributeCombinations() as $attributeCombination) {
            foreach ($this->locales as $locale) {
                $row["attribute"] .= sprintf('"%s"', $attributeCombination->getAttribute()->getTranslation($locale)->getTitle());
                $row["attribute_av"] .= sprintf('"%s"', $attributeCombination->getAttributeAv()->getTranslation($locale)->getTitle());
            }
        }
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $result) {
            $row = new LoopResultRow();

            foreach ($result as $key => $value) {
                $row->set($key, $value);
            }

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
            Argument::createBooleanOrBothTypeArgument("visible", BooleanOrBothType::ANY),
            Argument::createIntTypeArgument('width'),
            Argument::createIntTypeArgument('height')
        );
    }
}
