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
namespace IndexEngine\Form\Builder;

use IndexEngine\Driver\Configuration\ArgumentInterface;
use Symfony\Component\Form\FormBuilderInterface as SfFormBuilderInterface;

/**
 * Class ArgumentFormBuilder
 * @package IndexEngine\Form\Builder
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface ArgumentFormBuilderInterface
{
    /**
     * @param  ArgumentInterface      $argument
     * @param  SfFormBuilderInterface $builder
     * @param  mixed                  $defaultValue
     * @return void
     *
     * Add the needed field(s) into the symfony form builder for the given argument
     */
    public function addField(ArgumentInterface $argument, SfFormBuilderInterface $builder, $defaultValue = null);
}
