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

namespace IndexEngine\Manager;

use IndexEngine\Driver\Configuration\ArgumentCollection;
use IndexEngine\Driver\Configuration\ParserAwareArgumentInterface;
use IndexEngine\Driver\Configuration\VectorArgumentInterface;
use IndexEngine\Driver\Configuration\ViewBuilderInterface;
use Thelia\Core\Template\ParserInterface;
use Thelia\Core\Template\TemplateHelper;


/**
 * Class ConfigurationRenderManager
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ConfigurationRenderManager implements ConfigurationRenderManagerInterface
{
    /** @var ParserInterface */
    private $parser;

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param ArgumentCollection $collection
     * @param string $formName
     * @param null|string $driverCode
     * @return string
     *
     * Transforms an argument collection into a HTML string
     */
    public function renderFormFromCollection(ArgumentCollection $collection, $formName = "thelia.empty", $driverCode = null)
    {
        $i = 0;
        $content = "";

        // Ensure that the template definition is on the BO
        $this->parser->setTemplateDefinition(TemplateHelper::getInstance()->getActiveAdminTemplate());

        /** @var \IndexEngine\Driver\Configuration\ArgumentInterface $argument */
        foreach ($collection->getArguments() as $argument) {
            if ($argument instanceof ParserAwareArgumentInterface) {
                $argument->setParser($this->parser);
            }

            $formattedTitle = $this->formatTitle($argument->getName());

            $content .= $this->parser->render("form-field/render-form-field.html", [
                "driver_code" => $driverCode,
                "form_name" => $formName,
                "form_field" => $argument->getName(),
                "argument" => $argument,
                "is_vector" => $argument instanceof VectorArgumentInterface,
                "is_view_builder" => $argument instanceof ViewBuilderInterface,
                "is_field_count_even" => $i % 2 === 0,
                "field_count" => $i++,
                "formatted_title" => $formattedTitle,
            ]);
        }

        return $content;
    }

    protected function formatTitle($name)
    {
        return preg_replace("/[_\.\-]/", " ", ucfirst($name));
    }

}
