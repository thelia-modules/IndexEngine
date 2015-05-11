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

namespace IndexEngine\Form;

use Thelia\Form\EmptyForm;

/**
 * Class IndexTaskConfigurationForm
 * @package IndexEngine\Form
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexTaskConfigurationForm extends EmptyForm
{
    const FORM_NAME = "index_engine_task_configuration";

    public function getName()
    {
        return static::FORM_NAME;
    }
}
