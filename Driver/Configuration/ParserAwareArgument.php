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

use Thelia\Core\Template\ParserInterface;

/**
 * Interface ParserAwareArgument
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface ParserAwareArgument
{
    /**
     * @param ParserInterface $parser
     * @return $this
     *
     * Inject the parser into the argument
     */
    public function setParser(ParserInterface $parser);

    /**
     * @return null|ParserInterface
     *
     * Retrieves the current parser, or null if there is currently no parser
     */
    public function getParser();
}
