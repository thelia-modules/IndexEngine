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

use IndexEngine\IndexEngine;
use Thelia\Form\BaseForm;

/**
 * Class IndexTaskExecuteForm
 * @package IndexEngine\Form
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexTaskExecuteForm extends BaseForm
{
    /**
     *
     * in this function you add all the fields you need for your Form.
     * Form this you have to call add method on $this->formBuilder attribute :
     *
     * $this->formBuilder->add("name", "text")
     *   ->add("email", "email", array(
     *           "attr" => array(
     *               "class" => "field"
     *           ),
     *           "label" => "email",
     *           "constraints" => array(
     *               new \Symfony\Component\Validator\Constraints\NotBlank()
     *           )
     *       )
     *   )
     *   ->add('age', 'integer');
     *
     * @return null
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add("task_code", "index_task", [
                "label" => $this->translator->trans("Task", [], IndexEngine::MESSAGE_DOMAIN),
                "label_attr" => ["for" => "task_code"],
                "required" => true,
            ])
        ;
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return "index_task_execute";
    }
}
