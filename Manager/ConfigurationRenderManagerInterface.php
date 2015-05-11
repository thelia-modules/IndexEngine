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

/**
 * Interface ConfigurationRenderManagerInterface
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface ConfigurationRenderManagerInterface
{
    /**
     * @param ArgumentCollection $collection
     * @param string $formName
     * @param null|string $driverCode
     * @return string
     *
     * Transforms an argument collection into a HTML string
     */
    public function renderFormFromCollection(ArgumentCollection $collection, $formName = "thelia.empty", $driverCode = null);
}
