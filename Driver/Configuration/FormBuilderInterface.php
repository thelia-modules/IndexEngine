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

use Symfony\Component\Form\FormBuilderInterface as SfFormBuilderInterface;

/**
 * Interface FormBuilderInterface
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface FormBuilderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     * @return void
     *
     * This method is an equivalent of \Symfony\Component\Form\AbstractType::buildForm,
     * but for configuration argument fields that wants to build themselves.
     */
    public function buildForm(SfFormBuilderInterface $builder, array $options);
}
