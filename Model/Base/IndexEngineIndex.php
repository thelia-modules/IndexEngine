<?php

namespace IndexEngine\Model\Base;

use DateTime;
use Exception;
use PDO;
use IndexEngine\Model\IndexEngineDriverConfiguration as ChildIndexEngineDriverConfiguration;
use IndexEngine\Model\IndexEngineDriverConfigurationQuery as ChildIndexEngineDriverConfigurationQuery;
use IndexEngine\Model\IndexEngineIndex as ChildIndexEngineIndex;
use IndexEngine\Model\IndexEngineIndexQuery as ChildIndexEngineIndexQuery;
use IndexEngine\Model\IndexEngineIndexVersion as ChildIndexEngineIndexVersion;
use IndexEngine\Model\IndexEngineIndexVersionQuery as ChildIndexEngineIndexVersionQuery;
use IndexEngine\Model\Map\IndexEngineIndexTableMap;
use IndexEngine\Model\Map\IndexEngineIndexVersionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

abstract class IndexEngineIndex implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\IndexEngine\\Model\\Map\\IndexEngineIndexTableMap';

    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the visible field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $visible;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the type field.
     * @var        string
     */
    protected $type;

    /**
     * The value for the entity field.
     * @var        string
     */
    protected $entity;

    /**
     * The value for the serialized_columns field.
     * @var        string
     */
    protected $serialized_columns;

    /**
     * The value for the serialized_condition field.
     * @var        string
     */
    protected $serialized_condition;

    /**
     * The value for the index_engine_driver_configuration_id field.
     * @var        int
     */
    protected $index_engine_driver_configuration_id;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * The value for the version field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $version;

    /**
     * The value for the version_created_at field.
     * @var        string
     */
    protected $version_created_at;

    /**
     * The value for the version_created_by field.
     * @var        string
     */
    protected $version_created_by;

    /**
     * @var        IndexEngineDriverConfiguration
     */
    protected $aIndexEngineDriverConfiguration;

    /**
     * @var        ObjectCollection|ChildIndexEngineIndexVersion[] Collection to store aggregation of ChildIndexEngineIndexVersion objects.
     */
    protected $collIndexEngineIndexVersions;
    protected $collIndexEngineIndexVersionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // versionable behavior


    /**
     * @var bool
     */
    protected $enforceVersion = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $indexEngineIndexVersionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->visible = 0;
        $this->version = 0;
    }

    /**
     * Initializes internal state of IndexEngine\Model\Base\IndexEngineIndex object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>IndexEngineIndex</code> instance.  If
     * <code>obj</code> is an instance of <code>IndexEngineIndex</code>, delegates to
     * <code>equals(IndexEngineIndex)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return IndexEngineIndex The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this).': '.$msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed  $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data   The source data to import from
     *
     * @return IndexEngineIndex The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [visible] column value.
     *
     * @return int
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [type] column value.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the [entity] column value.
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Get the [serialized_columns] column value.
     *
     * @return string
     */
    public function getSerializedColumns()
    {
        return $this->serialized_columns;
    }

    /**
     * Get the [serialized_condition] column value.
     *
     * @return string
     */
    public function getSerializedCondition()
    {
        return $this->serialized_condition;
    }

    /**
     * Get the [index_engine_driver_configuration_id] column value.
     *
     * @return int
     */
    public function getIndexEngineDriverConfigurationId()
    {
        return $this->index_engine_driver_configuration_id;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                       If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                       If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Get the [version] column value.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get the [optionally formatted] temporal [version_created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                       If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getVersionCreatedAt($format = null)
    {
        if ($format === null) {
            return $this->version_created_at;
        } else {
            return $this->version_created_at instanceof \DateTime ? $this->version_created_at->format($format) : null;
        }
    }

    /**
     * Get the [version_created_by] column value.
     *
     * @return string
     */
    public function getVersionCreatedBy()
    {
        return $this->version_created_by;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int                                 $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [visible] column.
     *
     * @param  int                                 $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setVisible($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->visible !== $v) {
            $this->visible = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::VISIBLE] = true;
        }

        return $this;
    } // setVisible()

    /**
     * Set the value of [code] column.
     *
     * @param  string                              $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::CODE] = true;
        }

        return $this;
    } // setCode()

    /**
     * Set the value of [title] column.
     *
     * @param  string                              $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [type] column.
     *
     * @param  string                              $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::TYPE] = true;
        }

        return $this;
    } // setType()

    /**
     * Set the value of [entity] column.
     *
     * @param  string                              $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setEntity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->entity !== $v) {
            $this->entity = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::ENTITY] = true;
        }

        return $this;
    } // setEntity()

    /**
     * Set the value of [serialized_columns] column.
     *
     * @param  string                              $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setSerializedColumns($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->serialized_columns !== $v) {
            $this->serialized_columns = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::SERIALIZED_COLUMNS] = true;
        }

        return $this;
    } // setSerializedColumns()

    /**
     * Set the value of [serialized_condition] column.
     *
     * @param  string                              $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setSerializedCondition($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->serialized_condition !== $v) {
            $this->serialized_condition = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::SERIALIZED_CONDITION] = true;
        }

        return $this;
    } // setSerializedCondition()

    /**
     * Set the value of [index_engine_driver_configuration_id] column.
     *
     * @param  int                                 $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setIndexEngineDriverConfigurationId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->index_engine_driver_configuration_id !== $v) {
            $this->index_engine_driver_configuration_id = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID] = true;
        }

        if ($this->aIndexEngineDriverConfiguration !== null && $this->aIndexEngineDriverConfiguration->getId() !== $v) {
            $this->aIndexEngineDriverConfiguration = null;
        }

        return $this;
    } // setIndexEngineDriverConfigurationId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed                               $v string, integer (timestamp), or \DateTime value.
     *                                                Empty strings are treated as NULL.
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[IndexEngineIndexTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed                               $v string, integer (timestamp), or \DateTime value.
     *                                                Empty strings are treated as NULL.
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[IndexEngineIndexTableMap::UPDATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [version] column.
     *
     * @param  int                                 $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->version !== $v) {
            $this->version = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::VERSION] = true;
        }

        return $this;
    } // setVersion()

    /**
     * Sets the value of [version_created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed                               $v string, integer (timestamp), or \DateTime value.
     *                                                Empty strings are treated as NULL.
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setVersionCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->version_created_at !== null || $dt !== null) {
            if ($dt !== $this->version_created_at) {
                $this->version_created_at = $dt;
                $this->modifiedColumns[IndexEngineIndexTableMap::VERSION_CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setVersionCreatedAt()

    /**
     * Set the value of [version_created_by] column.
     *
     * @param  string                              $v new value
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function setVersionCreatedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->version_created_by !== $v) {
            $this->version_created_by = $v;
            $this->modifiedColumns[IndexEngineIndexTableMap::VERSION_CREATED_BY] = true;
        }

        return $this;
    } // setVersionCreatedBy()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        if ($this->visible !== 0) {
            return false;
        }

        if ($this->version !== 0) {
            return false;
        }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
     One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {
            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : IndexEngineIndexTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : IndexEngineIndexTableMap::translateFieldName('Visible', TableMap::TYPE_PHPNAME, $indexType)];
            $this->visible = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : IndexEngineIndexTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : IndexEngineIndexTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : IndexEngineIndexTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : IndexEngineIndexTableMap::translateFieldName('Entity', TableMap::TYPE_PHPNAME, $indexType)];
            $this->entity = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : IndexEngineIndexTableMap::translateFieldName('SerializedColumns', TableMap::TYPE_PHPNAME, $indexType)];
            $this->serialized_columns = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : IndexEngineIndexTableMap::translateFieldName('SerializedCondition', TableMap::TYPE_PHPNAME, $indexType)];
            $this->serialized_condition = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : IndexEngineIndexTableMap::translateFieldName('IndexEngineDriverConfigurationId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->index_engine_driver_configuration_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : IndexEngineIndexTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : IndexEngineIndexTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : IndexEngineIndexTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : IndexEngineIndexTableMap::translateFieldName('VersionCreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->version_created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : IndexEngineIndexTableMap::translateFieldName('VersionCreatedBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version_created_by = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 14; // 14 = IndexEngineIndexTableMap::NUM_HYDRATE_COLUMNS.
        } catch (Exception $e) {
            throw new PropelException("Error populating \IndexEngine\Model\IndexEngineIndex object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aIndexEngineDriverConfiguration !== null && $this->index_engine_driver_configuration_id !== $this->aIndexEngineDriverConfiguration->getId()) {
            $this->aIndexEngineDriverConfiguration = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param  boolean             $deep (optional) Whether to also de-associated any related objects.
     * @param  ConnectionInterface $con  (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException     - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(IndexEngineIndexTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildIndexEngineIndexQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aIndexEngineDriverConfiguration = null;
            $this->collIndexEngineIndexVersions = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param  ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see IndexEngineIndex::setDeleted()
     * @see IndexEngineIndex::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildIndexEngineIndexQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param  ConnectionInterface $con
     * @return int                 The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // versionable behavior
            if ($this->isVersioningNecessary()) {
                $this->setVersion($this->isNew() ? 1 : $this->getLastVersionNumber($con) + 1);
                if (!$this->isColumnModified(IndexEngineIndexTableMap::VERSION_CREATED_AT)) {
                    $this->setVersionCreatedAt(time());
                }
                $createVersion = true; // for postSave hook
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(IndexEngineIndexTableMap::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(IndexEngineIndexTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(IndexEngineIndexTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                // versionable behavior
                if (isset($createVersion)) {
                    $this->addVersion($con);
                }
                IndexEngineIndexTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param  ConnectionInterface $con
     * @return int                 The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aIndexEngineDriverConfiguration !== null) {
                if ($this->aIndexEngineDriverConfiguration->isModified() || $this->aIndexEngineDriverConfiguration->isNew()) {
                    $affectedRows += $this->aIndexEngineDriverConfiguration->save($con);
                }
                $this->setIndexEngineDriverConfiguration($this->aIndexEngineDriverConfiguration);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->indexEngineIndexVersionsScheduledForDeletion !== null) {
                if (!$this->indexEngineIndexVersionsScheduledForDeletion->isEmpty()) {
                    \IndexEngine\Model\IndexEngineIndexVersionQuery::create()
                        ->filterByPrimaryKeys($this->indexEngineIndexVersionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->indexEngineIndexVersionsScheduledForDeletion = null;
                }
            }

            if ($this->collIndexEngineIndexVersions !== null) {
                foreach ($this->collIndexEngineIndexVersions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;
        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[IndexEngineIndexTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.IndexEngineIndexTableMap::ID.')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(IndexEngineIndexTableMap::ID)) {
            $modifiedColumns[':p'.$index++]  = 'ID';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::VISIBLE)) {
            $modifiedColumns[':p'.$index++]  = 'VISIBLE';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::CODE)) {
            $modifiedColumns[':p'.$index++]  = 'CODE';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::TITLE)) {
            $modifiedColumns[':p'.$index++]  = 'TITLE';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::TYPE)) {
            $modifiedColumns[':p'.$index++]  = 'TYPE';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::ENTITY)) {
            $modifiedColumns[':p'.$index++]  = 'ENTITY';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::SERIALIZED_COLUMNS)) {
            $modifiedColumns[':p'.$index++]  = 'SERIALIZED_COLUMNS';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::SERIALIZED_CONDITION)) {
            $modifiedColumns[':p'.$index++]  = 'SERIALIZED_CONDITION';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID)) {
            $modifiedColumns[':p'.$index++]  = 'INDEX_ENGINE_DRIVER_CONFIGURATION_ID';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::CREATED_AT)) {
            $modifiedColumns[':p'.$index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::UPDATED_AT)) {
            $modifiedColumns[':p'.$index++]  = 'UPDATED_AT';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::VERSION)) {
            $modifiedColumns[':p'.$index++]  = 'VERSION';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::VERSION_CREATED_AT)) {
            $modifiedColumns[':p'.$index++]  = 'VERSION_CREATED_AT';
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::VERSION_CREATED_BY)) {
            $modifiedColumns[':p'.$index++]  = 'VERSION_CREATED_BY';
        }

        $sql = sprintf(
            'INSERT INTO index_engine_index (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'VISIBLE':
                        $stmt->bindValue($identifier, $this->visible, PDO::PARAM_INT);
                        break;
                    case 'CODE':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'TITLE':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'TYPE':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);
                        break;
                    case 'ENTITY':
                        $stmt->bindValue($identifier, $this->entity, PDO::PARAM_STR);
                        break;
                    case 'SERIALIZED_COLUMNS':
                        $stmt->bindValue($identifier, $this->serialized_columns, PDO::PARAM_STR);
                        break;
                    case 'SERIALIZED_CONDITION':
                        $stmt->bindValue($identifier, $this->serialized_condition, PDO::PARAM_STR);
                        break;
                    case 'INDEX_ENGINE_DRIVER_CONFIGURATION_ID':
                        $stmt->bindValue($identifier, $this->index_engine_driver_configuration_id, PDO::PARAM_INT);
                        break;
                    case 'CREATED_AT':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'UPDATED_AT':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'VERSION':
                        $stmt->bindValue($identifier, $this->version, PDO::PARAM_INT);
                        break;
                    case 'VERSION_CREATED_AT':
                        $stmt->bindValue($identifier, $this->version_created_at ? $this->version_created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'VERSION_CREATED_BY':
                        $stmt->bindValue($identifier, $this->version_created_by, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param  string $name name
     * @param  string $type The type of fieldname the $name is of:
     *                      one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                      TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                      Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed  Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = IndexEngineIndexTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int   $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getVisible();
                break;
            case 2:
                return $this->getCode();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getType();
                break;
            case 5:
                return $this->getEntity();
                break;
            case 6:
                return $this->getSerializedColumns();
                break;
            case 7:
                return $this->getSerializedCondition();
                break;
            case 8:
                return $this->getIndexEngineDriverConfigurationId();
                break;
            case 9:
                return $this->getCreatedAt();
                break;
            case 10:
                return $this->getUpdatedAt();
                break;
            case 11:
                return $this->getVersion();
                break;
            case 12:
                return $this->getVersionCreatedAt();
                break;
            case 13:
                return $this->getVersionCreatedBy();
                break;
            default:
                return;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param string  $keyType                (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                                        TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                                        Defaults to TableMap::TYPE_PHPNAME.
     * @param boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array   $alreadyDumpedObjects   List of objects to skip to avoid recursion
     * @param boolean $includeForeignObjects  (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['IndexEngineIndex'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['IndexEngineIndex'][$this->getPrimaryKey()] = true;
        $keys = IndexEngineIndexTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getVisible(),
            $keys[2] => $this->getCode(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getType(),
            $keys[5] => $this->getEntity(),
            $keys[6] => $this->getSerializedColumns(),
            $keys[7] => $this->getSerializedCondition(),
            $keys[8] => $this->getIndexEngineDriverConfigurationId(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
            $keys[11] => $this->getVersion(),
            $keys[12] => $this->getVersionCreatedAt(),
            $keys[13] => $this->getVersionCreatedBy(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aIndexEngineDriverConfiguration) {
                $result['IndexEngineDriverConfiguration'] = $this->aIndexEngineDriverConfiguration->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collIndexEngineIndexVersions) {
                $result['IndexEngineIndexVersions'] = $this->collIndexEngineIndexVersions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type  The type of fieldname the $name is of:
     *                       one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                       TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                       Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = IndexEngineIndexTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int   $pos   position in xml schema
     * @param  mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setVisible($value);
                break;
            case 2:
                $this->setCode($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setType($value);
                break;
            case 5:
                $this->setEntity($value);
                break;
            case 6:
                $this->setSerializedColumns($value);
                break;
            case 7:
                $this->setSerializedCondition($value);
                break;
            case 8:
                $this->setIndexEngineDriverConfigurationId($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
                $this->setUpdatedAt($value);
                break;
            case 11:
                $this->setVersion($value);
                break;
            case 12:
                $this->setVersionCreatedAt($value);
                break;
            case 13:
                $this->setVersionCreatedBy($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param  array  $arr     An array to populate the object from.
     * @param  string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = IndexEngineIndexTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setVisible($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCode($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTitle($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setType($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setEntity($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSerializedColumns($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSerializedCondition($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setIndexEngineDriverConfigurationId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setCreatedAt($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setUpdatedAt($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setVersion($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setVersionCreatedAt($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setVersionCreatedBy($arr[$keys[13]]);
        }
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(IndexEngineIndexTableMap::DATABASE_NAME);

        if ($this->isColumnModified(IndexEngineIndexTableMap::ID)) {
            $criteria->add(IndexEngineIndexTableMap::ID, $this->id);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::VISIBLE)) {
            $criteria->add(IndexEngineIndexTableMap::VISIBLE, $this->visible);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::CODE)) {
            $criteria->add(IndexEngineIndexTableMap::CODE, $this->code);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::TITLE)) {
            $criteria->add(IndexEngineIndexTableMap::TITLE, $this->title);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::TYPE)) {
            $criteria->add(IndexEngineIndexTableMap::TYPE, $this->type);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::ENTITY)) {
            $criteria->add(IndexEngineIndexTableMap::ENTITY, $this->entity);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::SERIALIZED_COLUMNS)) {
            $criteria->add(IndexEngineIndexTableMap::SERIALIZED_COLUMNS, $this->serialized_columns);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::SERIALIZED_CONDITION)) {
            $criteria->add(IndexEngineIndexTableMap::SERIALIZED_CONDITION, $this->serialized_condition);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID)) {
            $criteria->add(IndexEngineIndexTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID, $this->index_engine_driver_configuration_id);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::CREATED_AT)) {
            $criteria->add(IndexEngineIndexTableMap::CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::UPDATED_AT)) {
            $criteria->add(IndexEngineIndexTableMap::UPDATED_AT, $this->updated_at);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::VERSION)) {
            $criteria->add(IndexEngineIndexTableMap::VERSION, $this->version);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::VERSION_CREATED_AT)) {
            $criteria->add(IndexEngineIndexTableMap::VERSION_CREATED_AT, $this->version_created_at);
        }
        if ($this->isColumnModified(IndexEngineIndexTableMap::VERSION_CREATED_BY)) {
            $criteria->add(IndexEngineIndexTableMap::VERSION_CREATED_BY, $this->version_created_by);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(IndexEngineIndexTableMap::DATABASE_NAME);
        $criteria->add(IndexEngineIndexTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int  $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  object          $copyObj  An object of \IndexEngine\Model\IndexEngineIndex (or compatible) type.
     * @param  boolean         $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param  boolean         $makeNew  Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setVisible($this->getVisible());
        $copyObj->setCode($this->getCode());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setType($this->getType());
        $copyObj->setEntity($this->getEntity());
        $copyObj->setSerializedColumns($this->getSerializedColumns());
        $copyObj->setSerializedCondition($this->getSerializedCondition());
        $copyObj->setIndexEngineDriverConfigurationId($this->getIndexEngineDriverConfigurationId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setVersion($this->getVersion());
        $copyObj->setVersionCreatedAt($this->getVersionCreatedAt());
        $copyObj->setVersionCreatedBy($this->getVersionCreatedBy());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getIndexEngineIndexVersions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIndexEngineIndexVersion($relObj->copy($deepCopy));
                }
            }
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(null); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean                             $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \IndexEngine\Model\IndexEngineIndex Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildIndexEngineDriverConfiguration object.
     *
     * @param  ChildIndexEngineDriverConfiguration $v
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     * @throws PropelException
     */
    public function setIndexEngineDriverConfiguration(ChildIndexEngineDriverConfiguration $v = null)
    {
        if ($v === null) {
            $this->setIndexEngineDriverConfigurationId(null);
        } else {
            $this->setIndexEngineDriverConfigurationId($v->getId());
        }

        $this->aIndexEngineDriverConfiguration = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildIndexEngineDriverConfiguration object, it will not be re-added.
        if ($v !== null) {
            $v->addIndexEngineIndex($this);
        }

        return $this;
    }

    /**
     * Get the associated ChildIndexEngineDriverConfiguration object
     *
     * @param  ConnectionInterface                 $con Optional Connection object.
     * @return ChildIndexEngineDriverConfiguration The associated ChildIndexEngineDriverConfiguration object.
     * @throws PropelException
     */
    public function getIndexEngineDriverConfiguration(ConnectionInterface $con = null)
    {
        if ($this->aIndexEngineDriverConfiguration === null && ($this->index_engine_driver_configuration_id !== null)) {
            $this->aIndexEngineDriverConfiguration = ChildIndexEngineDriverConfigurationQuery::create()->findPk($this->index_engine_driver_configuration_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aIndexEngineDriverConfiguration->addIndexEngineIndices($this);
             */
        }

        return $this->aIndexEngineDriverConfiguration;
    }

    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param  string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('IndexEngineIndexVersion' == $relationName) {
            return $this->initIndexEngineIndexVersions();
        }
    }

    /**
     * Clears out the collIndexEngineIndexVersions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addIndexEngineIndexVersions()
     */
    public function clearIndexEngineIndexVersions()
    {
        $this->collIndexEngineIndexVersions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collIndexEngineIndexVersions collection loaded partially.
     */
    public function resetPartialIndexEngineIndexVersions($v = true)
    {
        $this->collIndexEngineIndexVersionsPartial = $v;
    }

    /**
     * Initializes the collIndexEngineIndexVersions collection.
     *
     * By default this just sets the collIndexEngineIndexVersions collection to an empty array (like clearcollIndexEngineIndexVersions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                  the collection even if it is not empty
     *
     * @return void
     */
    public function initIndexEngineIndexVersions($overrideExisting = true)
    {
        if (null !== $this->collIndexEngineIndexVersions && !$overrideExisting) {
            return;
        }
        $this->collIndexEngineIndexVersions = new ObjectCollection();
        $this->collIndexEngineIndexVersions->setModel('\IndexEngine\Model\IndexEngineIndexVersion');
    }

    /**
     * Gets an array of ChildIndexEngineIndexVersion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildIndexEngineIndex is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param  Criteria                                  $criteria optional Criteria object to narrow the query
     * @param  ConnectionInterface                       $con      optional connection object
     * @return Collection|ChildIndexEngineIndexVersion[] List of ChildIndexEngineIndexVersion objects
     * @throws PropelException
     */
    public function getIndexEngineIndexVersions($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collIndexEngineIndexVersionsPartial && !$this->isNew();
        if (null === $this->collIndexEngineIndexVersions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIndexEngineIndexVersions) {
                // return empty collection
                $this->initIndexEngineIndexVersions();
            } else {
                $collIndexEngineIndexVersions = ChildIndexEngineIndexVersionQuery::create(null, $criteria)
                    ->filterByIndexEngineIndex($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collIndexEngineIndexVersionsPartial && count($collIndexEngineIndexVersions)) {
                        $this->initIndexEngineIndexVersions(false);

                        foreach ($collIndexEngineIndexVersions as $obj) {
                            if (false == $this->collIndexEngineIndexVersions->contains($obj)) {
                                $this->collIndexEngineIndexVersions->append($obj);
                            }
                        }

                        $this->collIndexEngineIndexVersionsPartial = true;
                    }

                    reset($collIndexEngineIndexVersions);

                    return $collIndexEngineIndexVersions;
                }

                if ($partial && $this->collIndexEngineIndexVersions) {
                    foreach ($this->collIndexEngineIndexVersions as $obj) {
                        if ($obj->isNew()) {
                            $collIndexEngineIndexVersions[] = $obj;
                        }
                    }
                }

                $this->collIndexEngineIndexVersions = $collIndexEngineIndexVersions;
                $this->collIndexEngineIndexVersionsPartial = false;
            }
        }

        return $this->collIndexEngineIndexVersions;
    }

    /**
     * Sets a collection of IndexEngineIndexVersion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection            $indexEngineIndexVersions A Propel collection.
     * @param  ConnectionInterface   $con                      Optional connection object
     * @return ChildIndexEngineIndex The current object (for fluent API support)
     */
    public function setIndexEngineIndexVersions(Collection $indexEngineIndexVersions, ConnectionInterface $con = null)
    {
        $indexEngineIndexVersionsToDelete = $this->getIndexEngineIndexVersions(new Criteria(), $con)->diff($indexEngineIndexVersions);

        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->indexEngineIndexVersionsScheduledForDeletion = clone $indexEngineIndexVersionsToDelete;

        foreach ($indexEngineIndexVersionsToDelete as $indexEngineIndexVersionRemoved) {
            $indexEngineIndexVersionRemoved->setIndexEngineIndex(null);
        }

        $this->collIndexEngineIndexVersions = null;
        foreach ($indexEngineIndexVersions as $indexEngineIndexVersion) {
            $this->addIndexEngineIndexVersion($indexEngineIndexVersion);
        }

        $this->collIndexEngineIndexVersions = $indexEngineIndexVersions;
        $this->collIndexEngineIndexVersionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related IndexEngineIndexVersion objects.
     *
     * @param  Criteria            $criteria
     * @param  boolean             $distinct
     * @param  ConnectionInterface $con
     * @return int                 Count of related IndexEngineIndexVersion objects.
     * @throws PropelException
     */
    public function countIndexEngineIndexVersions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collIndexEngineIndexVersionsPartial && !$this->isNew();
        if (null === $this->collIndexEngineIndexVersions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIndexEngineIndexVersions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIndexEngineIndexVersions());
            }

            $query = ChildIndexEngineIndexVersionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByIndexEngineIndex($this)
                ->count($con);
        }

        return count($this->collIndexEngineIndexVersions);
    }

    /**
     * Method called to associate a ChildIndexEngineIndexVersion object to this object
     * through the ChildIndexEngineIndexVersion foreign key attribute.
     *
     * @param  ChildIndexEngineIndexVersion        $l ChildIndexEngineIndexVersion
     * @return \IndexEngine\Model\IndexEngineIndex The current object (for fluent API support)
     */
    public function addIndexEngineIndexVersion(ChildIndexEngineIndexVersion $l)
    {
        if ($this->collIndexEngineIndexVersions === null) {
            $this->initIndexEngineIndexVersions();
            $this->collIndexEngineIndexVersionsPartial = true;
        }

        if (!in_array($l, $this->collIndexEngineIndexVersions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddIndexEngineIndexVersion($l);
        }

        return $this;
    }

    /**
     * @param IndexEngineIndexVersion $indexEngineIndexVersion The indexEngineIndexVersion object to add.
     */
    protected function doAddIndexEngineIndexVersion($indexEngineIndexVersion)
    {
        $this->collIndexEngineIndexVersions[] = $indexEngineIndexVersion;
        $indexEngineIndexVersion->setIndexEngineIndex($this);
    }

    /**
     * @param  IndexEngineIndexVersion $indexEngineIndexVersion The indexEngineIndexVersion object to remove.
     * @return ChildIndexEngineIndex   The current object (for fluent API support)
     */
    public function removeIndexEngineIndexVersion($indexEngineIndexVersion)
    {
        if ($this->getIndexEngineIndexVersions()->contains($indexEngineIndexVersion)) {
            $this->collIndexEngineIndexVersions->remove($this->collIndexEngineIndexVersions->search($indexEngineIndexVersion));
            if (null === $this->indexEngineIndexVersionsScheduledForDeletion) {
                $this->indexEngineIndexVersionsScheduledForDeletion = clone $this->collIndexEngineIndexVersions;
                $this->indexEngineIndexVersionsScheduledForDeletion->clear();
            }
            $this->indexEngineIndexVersionsScheduledForDeletion[] = clone $indexEngineIndexVersion;
            $indexEngineIndexVersion->setIndexEngineIndex(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->visible = null;
        $this->code = null;
        $this->title = null;
        $this->type = null;
        $this->entity = null;
        $this->serialized_columns = null;
        $this->serialized_condition = null;
        $this->index_engine_driver_configuration_id = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->version = null;
        $this->version_created_at = null;
        $this->version_created_by = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collIndexEngineIndexVersions) {
                foreach ($this->collIndexEngineIndexVersions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collIndexEngineIndexVersions = null;
        $this->aIndexEngineDriverConfiguration = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(IndexEngineIndexTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return ChildIndexEngineIndex The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[IndexEngineIndexTableMap::UPDATED_AT] = true;

        return $this;
    }

    // versionable behavior

    /**
     * Enforce a new Version of this object upon next save.
     *
     * @return \IndexEngine\Model\IndexEngineIndex
     */
    public function enforceVersioning()
    {
        $this->enforceVersion = true;

        return $this;
    }

    /**
     * Checks whether the current state must be recorded as a version
     *
     * @return boolean
     */
    public function isVersioningNecessary($con = null)
    {
        if ($this->alreadyInSave) {
            return false;
        }

        if ($this->enforceVersion) {
            return true;
        }

        if (ChildIndexEngineIndexQuery::isVersioningEnabled() && ($this->isNew() || $this->isModified()) || $this->isDeleted()) {
            return true;
        }

        return false;
    }

    /**
     * Creates a version of the current object and saves it.
     *
     * @param ConnectionInterface $con the connection to use
     *
     * @return ChildIndexEngineIndexVersion A version object
     */
    public function addVersion($con = null)
    {
        $this->enforceVersion = false;

        $version = new ChildIndexEngineIndexVersion();
        $version->setId($this->getId());
        $version->setVisible($this->getVisible());
        $version->setCode($this->getCode());
        $version->setTitle($this->getTitle());
        $version->setType($this->getType());
        $version->setEntity($this->getEntity());
        $version->setSerializedColumns($this->getSerializedColumns());
        $version->setSerializedCondition($this->getSerializedCondition());
        $version->setIndexEngineDriverConfigurationId($this->getIndexEngineDriverConfigurationId());
        $version->setCreatedAt($this->getCreatedAt());
        $version->setUpdatedAt($this->getUpdatedAt());
        $version->setVersion($this->getVersion());
        $version->setVersionCreatedAt($this->getVersionCreatedAt());
        $version->setVersionCreatedBy($this->getVersionCreatedBy());
        $version->setIndexEngineIndex($this);
        $version->save($con);

        return $version;
    }

    /**
     * Sets the properties of the current object to the value they had at a specific version
     *
     * @param integer             $versionNumber The version number to read
     * @param ConnectionInterface $con           The connection to use
     *
     * @return ChildIndexEngineIndex The current object (for fluent API support)
     */
    public function toVersion($versionNumber, $con = null)
    {
        $version = $this->getOneVersion($versionNumber, $con);
        if (!$version) {
            throw new PropelException(sprintf('No ChildIndexEngineIndex object found with version %d', $version));
        }
        $this->populateFromVersion($version, $con);

        return $this;
    }

    /**
     * Sets the properties of the current object to the value they had at a specific version
     *
     * @param ChildIndexEngineIndexVersion $version       The version object to use
     * @param ConnectionInterface          $con           the connection to use
     * @param array                        $loadedObjects objects that been loaded in a chain of populateFromVersion calls on referrer or fk objects.
     *
     * @return ChildIndexEngineIndex The current object (for fluent API support)
     */
    public function populateFromVersion($version, $con = null, &$loadedObjects = array())
    {
        $loadedObjects['ChildIndexEngineIndex'][$version->getId()][$version->getVersion()] = $this;
        $this->setId($version->getId());
        $this->setVisible($version->getVisible());
        $this->setCode($version->getCode());
        $this->setTitle($version->getTitle());
        $this->setType($version->getType());
        $this->setEntity($version->getEntity());
        $this->setSerializedColumns($version->getSerializedColumns());
        $this->setSerializedCondition($version->getSerializedCondition());
        $this->setIndexEngineDriverConfigurationId($version->getIndexEngineDriverConfigurationId());
        $this->setCreatedAt($version->getCreatedAt());
        $this->setUpdatedAt($version->getUpdatedAt());
        $this->setVersion($version->getVersion());
        $this->setVersionCreatedAt($version->getVersionCreatedAt());
        $this->setVersionCreatedBy($version->getVersionCreatedBy());

        return $this;
    }

    /**
     * Gets the latest persisted version number for the current object
     *
     * @param ConnectionInterface $con the connection to use
     *
     * @return integer
     */
    public function getLastVersionNumber($con = null)
    {
        $v = ChildIndexEngineIndexVersionQuery::create()
            ->filterByIndexEngineIndex($this)
            ->orderByVersion('desc')
            ->findOne($con);
        if (!$v) {
            return 0;
        }

        return $v->getVersion();
    }

    /**
     * Checks whether the current object is the latest one
     *
     * @param ConnectionInterface $con the connection to use
     *
     * @return Boolean
     */
    public function isLastVersion($con = null)
    {
        return $this->getLastVersionNumber($con) == $this->getVersion();
    }

    /**
     * Retrieves a version object for this entity and a version number
     *
     * @param integer             $versionNumber The version number to read
     * @param ConnectionInterface $con           the connection to use
     *
     * @return ChildIndexEngineIndexVersion A version object
     */
    public function getOneVersion($versionNumber, $con = null)
    {
        return ChildIndexEngineIndexVersionQuery::create()
            ->filterByIndexEngineIndex($this)
            ->filterByVersion($versionNumber)
            ->findOne($con);
    }

    /**
     * Gets all the versions of this object, in incremental order
     *
     * @param ConnectionInterface $con the connection to use
     *
     * @return ObjectCollection A list of ChildIndexEngineIndexVersion objects
     */
    public function getAllVersions($con = null)
    {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(IndexEngineIndexVersionTableMap::VERSION);

        return $this->getIndexEngineIndexVersions($criteria, $con);
    }

    /**
     * Compares the current object with another of its version.
     * <code>
     * print_r($book->compareVersion(1));
     * => array(
     *   '1' => array('Title' => 'Book title at version 1'),
     *   '2' => array('Title' => 'Book title at version 2')
     * );
     * </code>
     *
     * @param integer             $versionNumber
     * @param string              $keys           Main key used for the result diff (versions|columns)
     * @param ConnectionInterface $con            the connection to use
     * @param array               $ignoredColumns The columns to exclude from the diff.
     *
     * @return array A list of differences
     */
    public function compareVersion($versionNumber, $keys = 'columns', $con = null, $ignoredColumns = array())
    {
        $fromVersion = $this->toArray();
        $toVersion = $this->getOneVersion($versionNumber, $con)->toArray();

        return $this->computeDiff($fromVersion, $toVersion, $keys, $ignoredColumns);
    }

    /**
     * Compares two versions of the current object.
     * <code>
     * print_r($book->compareVersions(1, 2));
     * => array(
     *   '1' => array('Title' => 'Book title at version 1'),
     *   '2' => array('Title' => 'Book title at version 2')
     * );
     * </code>
     *
     * @param integer             $fromVersionNumber
     * @param integer             $toVersionNumber
     * @param string              $keys              Main key used for the result diff (versions|columns)
     * @param ConnectionInterface $con               the connection to use
     * @param array               $ignoredColumns    The columns to exclude from the diff.
     *
     * @return array A list of differences
     */
    public function compareVersions($fromVersionNumber, $toVersionNumber, $keys = 'columns', $con = null, $ignoredColumns = array())
    {
        $fromVersion = $this->getOneVersion($fromVersionNumber, $con)->toArray();
        $toVersion = $this->getOneVersion($toVersionNumber, $con)->toArray();

        return $this->computeDiff($fromVersion, $toVersion, $keys, $ignoredColumns);
    }

    /**
     * Computes the diff between two versions.
     * <code>
     * print_r($book->computeDiff(1, 2));
     * => array(
     *   '1' => array('Title' => 'Book title at version 1'),
     *   '2' => array('Title' => 'Book title at version 2')
     * );
     * </code>
     *
     * @param array  $fromVersion    An array representing the original version.
     * @param array  $toVersion      An array representing the destination version.
     * @param string $keys           Main key used for the result diff (versions|columns).
     * @param array  $ignoredColumns The columns to exclude from the diff.
     *
     * @return array A list of differences
     */
    protected function computeDiff($fromVersion, $toVersion, $keys = 'columns', $ignoredColumns = array())
    {
        $fromVersionNumber = $fromVersion['Version'];
        $toVersionNumber = $toVersion['Version'];
        $ignoredColumns = array_merge(array(
            'Version',
            'VersionCreatedAt',
            'VersionCreatedBy',
        ), $ignoredColumns);
        $diff = array();
        foreach ($fromVersion as $key => $value) {
            if (in_array($key, $ignoredColumns)) {
                continue;
            }
            if ($toVersion[$key] != $value) {
                switch ($keys) {
                    case 'versions':
                        $diff[$fromVersionNumber][$key] = $value;
                        $diff[$toVersionNumber][$key] = $toVersion[$key];
                        break;
                    default:
                        $diff[$key] = array(
                            $fromVersionNumber => $value,
                            $toVersionNumber => $toVersion[$key],
                        );
                        break;
                }
            }
        }

        return $diff;
    }
    /**
     * retrieve the last $number versions.
     *
     * @param  Integer                $number the number of record to return.
     * @return PropelCollection|array \IndexEngine\Model\IndexEngineIndexVersion[] List of \IndexEngine\Model\IndexEngineIndexVersion objects
     */
    public function getLastVersions($number = 10, $criteria = null, $con = null)
    {
        $criteria = ChildIndexEngineIndexVersionQuery::create(null, $criteria);
        $criteria->addDescendingOrderByColumn(IndexEngineIndexVersionTableMap::VERSION);
        $criteria->limit($number);

        return $this->getIndexEngineIndexVersions($criteria, $con);
    }
    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
    }

    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }
}
