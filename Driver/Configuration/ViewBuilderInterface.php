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

use Thelia\Form\BaseForm;

/**
 * Interface ViewBuilderInterface
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface ViewBuilderInterface
{
    /**
     * @param  BaseForm $form
     * @return string
     *
     * Generates the view for the configuration form.
     * It must return a valid html view.
     */
    public function buildView(BaseForm $form);
}
