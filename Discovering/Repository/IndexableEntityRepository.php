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

namespace IndexEngine\Discovering\Repository;

use IndexEngine\Event\Module\CollectEvent;
use IndexEngine\Event\Module\EntityCollectEvent;
use IndexEngine\Event\Module\EntityColumnsCollectEvent;
use IndexEngine\Event\Module\IndexEngineEvents;
use IndexEngine\Exception\InvalidArgumentException;
use IndexEngine\Discovering\Repository\IndexableEntityRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class IndexableEntityRepository
 * @package IndexEngine\Discovering\Repository
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexableEntityRepository implements IndexableEntityRepositoryInterface
{
    /** @var EventDispatcherInterface */
    private $dispatcher;

    private $entityTypes = array();

    /** @var array  */
    private $entities = array();

    /** @var array  */
    private $entityColumns = array();

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function listIndexableEntityTypes($useCache = true)
    {
        if ([] === $this->entityTypes || false === $useCache) {
            $this->entityTypes = $this->dispatcher
                ->dispatch(IndexEngineEvents::COLLECT_ENTITY_TYPES, new CollectEvent())
                ->getData()
            ;
        }

        return $this->entityTypes;
    }

    public function listIndexableEntities($type, $useCache = true)
    {
        if (! isset($this->entities[$type]) || false === $useCache) {
            $this->entities[$type] = $this->dispatcher
                ->dispatch(IndexEngineEvents::COLLECT_ENTITIES, new EntityCollectEvent($type))
                ->getData()
            ;
        }

        return $this->entities[$type];
    }

    public function listIndexableEntityColumns($type, $entity, $useCache = true)
    {
        $this->listIndexableEntities($type, $useCache);

        if (! in_array($entity, $this->entities[$type])) {
            throw new InvalidArgumentException(sprintf(
                "The entity '%s' doesn't exist in the current context",
                $entity
            ));
        }

        if (! isset($this->entityColumns[$type][$entity]) || false === $useCache) {
            $this->entityColumns[$type][$entity] = $this->dispatcher
                ->dispatch(IndexEngineEvents::COLLECT_ENTITY_COLUMNS, new EntityColumnsCollectEvent($type, $entity))
                ->getData()
            ;
        }

        return $this->entityColumns[$type][$entity];
    }
}
