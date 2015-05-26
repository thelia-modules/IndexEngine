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

namespace IndexEngine\Driver\Configuration;

use IndexEngine\Driver\Configuration\Exception\LogicException;
use Thelia\Core\Template\ParserInterface;
use Thelia\Form\BaseForm;

/**
 * Class ParserAwareStringVectorArgument
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class ParserAwareStringVectorArgument extends StringVectorArgument implements ViewBuilderParserAwareInterface
{
    /** @var ParserInterface */
    private $parser;

    /**
     * @param BaseForm
     * @return string
     *
     * Generates the view for the configuration form.
     * It must return a valid html view.
     */
    public function buildView(BaseForm $form)
    {
        if (null === $this->parser) {
            throw new LogicException(sprintf("You must inject a parser before call %s", __METHOD__));
        }

        return $this->parser->render("form-field/render-string-vector.html", [
            "form" => $form,
            "field_name" => $this->getName(),
            "filtered_name" => str_replace(".", "-", $this->getName()),
        ]);
    }

    /**
     * @param  ParserInterface $parser
     * @return $this
     *
     * Inject the parser into the argument
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return null|ParserInterface
     *
     * Retrieves the current parser, or null if there is currently no parser
     */
    public function getParser()
    {
        return $this->parser;
    }
}
