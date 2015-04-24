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


/**
 * Class IndexConfigurationManager
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface IndexConfigurationManagerInterface
{
    /**
     * @param $type
     * @return string
     *
     * Render the configuration template for the given type
     */
    public function renderConfigurationTemplate($type);

    /**
     * @param string $type
     * @param string $entity
     * @return string
     *
     * Render the columns configuration template for the given type & entity
     */
    public function renderConfigurationColumnsTemplate($type, $entity);

    /**
     * @return string
     *
     * Render the configuration template for the current type
     */
    public function renderCurrentConfigurationTemplate();
}