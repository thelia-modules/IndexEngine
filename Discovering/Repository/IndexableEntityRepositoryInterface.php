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

/**
 * Class IndexableEntityRepository
 * @package IndexEngine\Repository
 * @author Benjamin Perche <benjamin@thelia.net>
 */
interface IndexableEntityRepositoryInterface
{
    public function listIndexableEntityTypes($useCache = true);

    public function listIndexableEntities($type, $useCache = true);

    public function listIndexableEntityColumns($type, $entity, $useCache = true);
}
