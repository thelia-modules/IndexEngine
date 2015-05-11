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

    protected $task;

    /**
     * Very swag constructor
     *
     * We could have done parent::__construct(...$args) with PHP 5.6 ...
     */
    public function __construct()
    {
        $args = func_get_args();
        $options = &$args[3]; // options

        if (isset($options["task"])) {
            $this->task = $options["task"];

            unset($options["task"]);
        }

        call_user_func_array(["Thelia\\Form\\BaseForm", "__construct"], $args);
    }

    /**
     * @return array
     */
    public function getTask()
    {
        return $this->task;
    }

    public function getName()
    {
        return static::FORM_NAME;
    }
}
