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

use IndexEngine\Driver\AbstractCollection;
use IndexEngine\Driver\Exception\InvalidNameException;

/**
 * Class StringVectorArgument
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class StringVectorArgument extends AbstractCollection implements VectorArgumentInterface
{
    /** @var string */
    private $name;

    /** @var array */
    private $collection = array();

    /** @var bool */
    private $allowNullValue = false;

    public function __construct($name, array $values = array())
    {
        $this->isValid($this->name, static::MODE_THROW_EXCEPTION_ON_ERROR, __METHOD__);
        $this->name = $name;

        $this->setValue($values);
    }

    /**
     * @param $value
     * @return $this
     *
     * @throws \IndexEngine\Exception\InvalidArgumentException
     *
     * This method returns the object itself.
     */
    public function setValue($value)
    {
        $this->clear();

        if (!is_array($value)) {
            $value = [$value];
        }

        foreach ($value as $argument) {
            $this[] = $argument;
        }
    }

    /**
     * @return mixed
     *
     * This method return the casted value of the argument
     */
    public function getValue()
    {
        return $this->collection;
    }

    /**
     * @return string
     *
     * This method return the argument type.
     * It must be one of the constants that begins with "TYPE_" defined in the interface
     *
     * If the argument is a vector, it must return: Vector<TYPE>
     */
    public function getType()
    {
        return sprintf("Vector<%s>", static::TYPE_STRING);
    }

    /**
     * @return string
     *
     * This method returns the argument name.
     * It must be a unique string, less than 64 characters and only composed of
     * lower and upper case letters, numbers, underscores, dashes and points.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param  mixed   $offset <p>
     *                         An offset to check for.
     *                         </p>
     * @return boolean true on success or false on failure.
     *                        </p>
     *                        <p>
     *                        The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param  mixed $offset <p>
     *                       The offset to retrieve.
     *                       </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new InvalidNameException(sprintf("The offset '%s' doesn't exist in this String vector", $offset));
        }

        return $this->collection[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param  mixed $offset <p>
     *                       The offset to assign the value to.
     *                       </p>
     * @param  mixed $value  <p>
     *                       The value to set.
     *                       </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if ($this->allowNullValue === true || null !== $value) {
            if (null === $offset) {
                $this->collection[] = $this->resolveString($value, __METHOD__);
            } else {
                $this->collection[$offset] = $this->resolveString($value, __METHOD__);
            }
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param  mixed $offset <p>
     *                       The offset to unset.
     *                       </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new InvalidNameException(sprintf("The offset '%s' doesn't exist in this String vector", $offset));
        }

        unset($this->collection[$offset]);
    }

    /**
     * @return void
     *
     * Clear the vector from all the entries
     */
    public function clear()
    {
        $this->collection = array();
    }

    /**
     * @param  boolean $allowNullValue
     * @return $this
     */
    public function setAllowNullValue($allowNullValue = true)
    {
        $this->allowNullValue = $allowNullValue;

        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *                 Returns true on success or false on failure.
     */
    public function valid()
    {
        return false !== $this->current();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->collection);
    }

    /**
     * @return boolean
     */
    public function isAllowNullValue()
    {
        return $this->allowNullValue;
    }
}
