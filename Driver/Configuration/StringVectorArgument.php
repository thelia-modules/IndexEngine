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
use IndexEngine\Driver\Configuration\Exception\LogicException;
use IndexEngine\Driver\Exception\InvalidNameException;
use Thelia\Core\Template\ParserInterface;
use Thelia\Form\BaseForm;

/**
 * Class StringVectorArgument
 * @package IndexEngine\Driver\Configuration
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class StringVectorArgument extends AbstractCollection implements
    VectorArgumentInterface,
    ViewBuilderParserAwareInterface
{
    /** @var string */
    private $name;

    /** @var array */
    private $collection = array();

    /** @var ParserInterface */
    private $parser;

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
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
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
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->collection[$offset] = $this->resolveString($value, __METHOD__);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
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
     * @param BaseForm
     * @return string
     *
     * Generates the view for the configuration form.
     * It must return a valid html view.
     */
    public function buildView(BaseForm $form)
    {
        if (null === $this->parser) {
            throw new LogicException(sprintf("You must inject a parser before call %s", __METHOD__));
        }

        return $this->parser->render("form-field/render-string-vector.html", [
            "form" => $form,
            "field_name" => $this->name,
            "filtered_name" => str_replace(".", "-", $this->name),
        ]);
    }

    /**
     * @param ParserInterface $parser
     * @return $this
     *
     * Inject the parser into the argument
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return null|ParserInterface
     *
     * Retrieves the current parser, or null if there is currently no parser
     */
    public function getParser()
    {
        return $this->parser;
    }
}
