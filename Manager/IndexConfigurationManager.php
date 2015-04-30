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

use IndexEngine\Discovering\Repository\IndexableEntityRepositoryInterface;
use IndexEngine\Driver\Event\IndexEvent;
use IndexEngine\Entity\IndexConfiguration;
use IndexEngine\Entity\IndexMapping;
use IndexEngine\Event\IndexEngineIndexEvents;
use IndexEngine\Event\Module\IndexEngineEvents;
use IndexEngine\Event\RenderConfigurationEvent;
use IndexEngine\Exception\InvalidArgumentException;
use IndexEngine\Model\IndexEngineIndex;
use IndexEngine\Model\IndexEngineIndexQuery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\HttpFoundation\Request;

/**
 * Class IndexConfigurationManager
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexConfigurationManager implements IndexConfigurationManagerInterface
{
    const INDEX_ID_PARAMETER = "index_engine_index_id";

    /**
     * @var EventDispatcherInterface
     */
    private $theliaDispatcher;

    /**
     * @var EventDispatcherInterface
     */
    private $indexEngineDispatcher;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var IndexableEntityRepositoryInterface
     */
    private $repository;

    /** @var ConfigurationManagerInterface */
    private $driverManager;

    public function __construct(
        EventDispatcherInterface $theliaDispatcher,
        EventDispatcherInterface $indexEngineDispatcher,
        Request $request,
        IndexableEntityRepositoryInterface $repository,
        ConfigurationManagerInterface $driverManager
    ) {
        $this->theliaDispatcher = $theliaDispatcher;
        $this->indexEngineDispatcher = $indexEngineDispatcher;
        $this->request = $request;
        $this->repository = $repository;
        $this->driverManager = $driverManager;
    }

    /**
     * @param $type
     * @return string
     *
     * Render the configuration template for the given type
     */
    public function renderConfigurationTemplate($type)
    {
        return $this->theliaDispatcher
            ->dispatch(IndexEngineIndexEvents::RENDER_CONFIGURATION_TEMPLATE, new RenderConfigurationEvent($type))
            ->getContent()
        ;
    }

    /**
     * @return string
     *
     * Render the configuration template for the current type
     */
    public function renderCurrentConfigurationTemplate()
    {
        return $this->renderConfigurationTemplate($this->getCurrentType());
    }

    /**
     * @return mixed|string
     *
     * Get the current index type. Return the first returned by the repository if there's currently no type
     */
    public function getCurrentType()
    {
        if (null !== $index = $this->getCurrencyIndex()) {
            return $index->getType();
        }

        $types = $this->repository->listIndexableEntityTypes();
        $type = array_shift($types);

        return $type;
    }

    /**
     * @return null|string
     *
     * Return null if the index doesn't exists, or its entity if it exists.
     */
    public function getCurrentEntity()
    {
        if (null !== $index = $this->getCurrencyIndex()) {
            return $index->getEntity();
        }

        return null;
    }

    /**
     * @return null|\IndexEngine\Model\IndexEngineIndex
     *
     * Get the current index based on the query parameter
     */
    public function getCurrencyIndex()
    {
        return IndexEngineIndexQuery::create()->findPk($this->getCurrentIndexId());
    }

    /**
     * @return mixed
     *
     * Get the current index id based on the query parameter
     */
    public function getCurrentIndexId()
    {
        return $this->request->query->get(static::INDEX_ID_PARAMETER);
    }

    public function renderConfigurationColumnsTemplate($type, $entity)
    {
        return $this->theliaDispatcher
            ->dispatch(IndexEngineIndexEvents::RENDER_CONFIGURATION_COLUMNS_TEMPLATE, new RenderConfigurationEvent($type, $entity))
            ->getContent()
        ;
    }

    /**
     * @return array
     *
     * Get the current columns, or an empty array if the index doesn't exist
     */
    public function getCurrentColumns()
    {
        if (null !== $index = $this->getCurrencyIndex()) {
            return $index->getColumns();
        }

        return [];
    }

    /**
     * @return array
     *
     * Get the current conditions, or an empty array if the index doesn't exist
     */
    public function getCurrentConditions()
    {
        if (null !== $index = $this->getCurrencyIndex()) {
            return $index->getConditions();
        }

        return [];
    }

    /**
     * @return array
     *
     * Get the current criteria from the conditions.
     * If it doesn't exist, return an empty array
     */
    public function getCurrentConditionsCriteria()
    {
        $conditions = $this->getCurrentConditions();

        if (isset($conditions["criteria"]) && is_array($conditions["criteria"])) {
            return $conditions["criteria"];
        }

        return [];
    }

    /**
     * @return array
     *
     * Get the current mapping from the conditions.
     * If it doesn't exist, return an empty array
     */
    public function getCurrentMapping()
    {
        $conditions = $this->getCurrentConditions();

        if (isset($conditions["mapping"]) && is_array($conditions["mapping"])) {
            return $conditions["mapping"];
        }

        return [];
    }

    /**
     * @return \IndexEngine\Entity\IndexMapping
     *
     * Get the entity corresponding to the current mapping
     */
    public function getCurrentMappingEntity()
    {
        $mapping = $this->getCurrentMapping();

        $entity = new IndexMapping();
        return $entity->setMapping($mapping);
    }

    /**
     * @param $code
     * @return \IndexEngine\Entity\IndexConfiguration
     *
     * @throws \IndexEngine\Exception\InvalidArgumentException
     */
    public function getConfigurationEntityFromCode($code)
    {
        $dbConfiguration = IndexEngineIndexQuery::create()->findOneByCode($code);

        if (null === $dbConfiguration) {
            throw new InvalidArgumentException(sprintf("The index configuration code '%s' doesn't exist", $code));
        }

        return $this->getConfigurationEntityFromPropelEntity($dbConfiguration);
    }

    public function getConfigurationEntityFromPropelEntity(IndexEngineIndex $dbConfiguration)
    {
        $configuration = new IndexConfiguration();
        $configuration
            ->setCode($dbConfiguration->getCode())
            ->setTitle($dbConfiguration->getTitle())
            ->setType($dbConfiguration->getType())
            ->setEntity($dbConfiguration->getEntity())
            ->setColumns($dbConfiguration->getColumns())
            ->setDriverConfiguration($this->driverManager->getConfigurationFromCode(
                $dbConfiguration->getIndexEngineDriverConfiguration()->getCode()
            ))
        ;

        $extraConfiguration = $dbConfiguration->getConditions();

        if (isset($extraConfiguration["criteria"])) {
            $configuration->setCriteria($extraConfiguration["criteria"]);
            unset($extraConfiguration["criteria"]);
        }

        if (isset($extraConfiguration["mapping"])) {
            $configuration->setMapping((new IndexMapping())->setMapping($extraConfiguration["mapping"]));
            unset($extraConfiguration["mapping"]);
        }

        $configuration->setExtraData($extraConfiguration);

        return $configuration;
    }

    /**
     * @param IndexConfiguration $configuration
     * @return \IndexEngine\Entity\IndexDataVector
     *
     * This method has to collect the data to index and return an IndexData vector
     */
    public function collectDataForType(IndexConfiguration $configuration)
    {
        $event = new IndexEvent(
            $configuration->getType(),
            $configuration->getCode(),
            $configuration->getTitle(),
            $configuration->getMapping()
        );

        $this->indexEngineDispatcher->dispatch(IndexEngineEvents::COLLECT_DATA_TO_INDEX, $event);

        return $event->getIndexDataVector();
    }
}
