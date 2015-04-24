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
use IndexEngine\Event\IndexEngineIndexEvents;
use IndexEngine\Event\RenderConfigurationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\HttpFoundation\Request;

/**
 * Class IndexConfigurationManager
 * @package IndexEngine\Manager
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexConfigurationManager implements IndexConfigurationManagerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var IndexableEntityRepositoryInterface
     */
    private $repository;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        Request $request,
        IndexableEntityRepositoryInterface $repository
    ) {
        $this->dispatcher = $dispatcher;
        $this->request = $request;
        $this->repository = $repository;
    }

    /**
     * @param $type
     * @return string
     *
     * Render the configuration template for the given type
     */
    public function renderConfigurationTemplate($type)
    {
        return $this->dispatcher
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

    protected function getCurrentType()
    {
        $type = $this->request->query->get("index_type");

        if (null === $type) {
            $types = $this->repository->listIndexableEntityTypes();

            $type = array_shift($types);
        }

        return $type;
    }

    public function renderConfigurationColumnsTemplate($type, $entity)
    {
        return $this->dispatcher
            ->dispatch(IndexEngineIndexEvents::RENDER_CONFIGURATION_COLUMNS_TEMPLATE, new RenderConfigurationEvent($type, $entity))
            ->getContent()
        ;
    }
}
