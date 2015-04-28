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

    /**
     * @return mixed|string
     *
     * Get the current index type. Return the first returned by the repository if there's currently no type
     */
    public function getCurrentType();

    /**
     * @return null|\IndexEngine\Model\IndexEngineIndex
     *
     * Get the current index based on the query parameter
     */
    public function getCurrencyIndex();

    /**
     * @return mixed
     *
     * Get the current index id based on the query parameter
     */
    public function getCurrentIndexId();

    /**
     * @return null|string
     *
     * Return null if the index doesn't exists, or its entity if it exists.
     */
    public function getCurrentEntity();

    /**
     * @return array
     *
     * Get the current columns, or an empty array if the index doesn't exist
     */
    public function getCurrentColumns();

    /**
     * @return array
     *
     * Get the current conditions, or an empty array if the index doesn't exist
     */
    public function getCurrentConditions();

    /**
     * @return array
     *
     * Get the current criteria from the conditions.
     * If it doesn't exist, return an empty array
     */
    public function getCurrentConditionsCriteria();
}