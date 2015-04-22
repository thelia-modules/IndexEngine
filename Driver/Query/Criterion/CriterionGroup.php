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

namespace IndexEngine\Driver\Query\Criterion;

use IndexEngine\Driver\AbstractCollection;
use IndexEngine\Driver\Exception\InvalidNameException;

/**
 * Class CriterionGroup
 * @package IndexEngine\Driver\Query\Criterion
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class CriterionGroup extends AbstractCollection implements CriterionGroupInterface
{
    private $collection = array();

    // Add Mode Relations

    public function addCriterion(CriterionInterface $criterion, $name = null)
    {
        if (null === $name) {
            $this->collection[] = $criterion;
        } else {
            $this->collection[$this->resolveString($name, __METHOD__)] = $criterion;
        }

        return $this;
    }

    public function hasCriterion($name)
    {
        return isset($this->collection[$this->resolveString($name, __METHOD__)]);
    }

    public function deleteCriterion($name)
    {
        if (!$this->hasCriterion($name)) {
            throw new InvalidNameException(sprintf("The criterion '%s' doesn't exist in this group", $name));
        }

        unset($this->collection[$this->resolveString($name, __METHOD__)]);
    }

    public function getCriterion($name)
    {
        if (!$this->hasCriterion($name)) {
            throw new InvalidNameException(sprintf("The criterion '%s' doesn't exist in this group", $name));
        }

        return $this->collection[$this->resolveString($name, __METHOD__)];
    }

    public function getCriteria()
    {
        return $this->collection;
    }
}
