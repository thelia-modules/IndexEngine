<?php

namespace IndexEngine\Model\Map;

use IndexEngine\Model\IndexEngineIndexVersion;
use IndexEngine\Model\IndexEngineIndexVersionQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'index_engine_index_version' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class IndexEngineIndexVersionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'IndexEngine.Model.Map.IndexEngineIndexVersionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'index_engine_index_version';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\IndexEngine\\Model\\IndexEngineIndexVersion';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'IndexEngine.Model.IndexEngineIndexVersion';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the ID field
     */
    const ID = 'index_engine_index_version.ID';

    /**
     * the column name for the VISIBLE field
     */
    const VISIBLE = 'index_engine_index_version.VISIBLE';

    /**
     * the column name for the CODE field
     */
    const CODE = 'index_engine_index_version.CODE';

    /**
     * the column name for the TITLE field
     */
    const TITLE = 'index_engine_index_version.TITLE';

    /**
     * the column name for the ENTITY field
     */
    const ENTITY = 'index_engine_index_version.ENTITY';

    /**
     * the column name for the SERIALIZED_COLUMNS field
     */
    const SERIALIZED_COLUMNS = 'index_engine_index_version.SERIALIZED_COLUMNS';

    /**
     * the column name for the SERIALIZED_CONDITION field
     */
    const SERIALIZED_CONDITION = 'index_engine_index_version.SERIALIZED_CONDITION';

    /**
     * the column name for the INDEX_ENGINE_DRIVER_CONFIGURATION_ID field
     */
    const INDEX_ENGINE_DRIVER_CONFIGURATION_ID = 'index_engine_index_version.INDEX_ENGINE_DRIVER_CONFIGURATION_ID';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'index_engine_index_version.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'index_engine_index_version.UPDATED_AT';

    /**
     * the column name for the VERSION field
     */
    const VERSION = 'index_engine_index_version.VERSION';

    /**
     * the column name for the VERSION_CREATED_AT field
     */
    const VERSION_CREATED_AT = 'index_engine_index_version.VERSION_CREATED_AT';

    /**
     * the column name for the VERSION_CREATED_BY field
     */
    const VERSION_CREATED_BY = 'index_engine_index_version.VERSION_CREATED_BY';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Visible', 'Code', 'Title', 'Entity', 'SerializedColumns', 'SerializedCondition', 'IndexEngineDriverConfigurationId', 'CreatedAt', 'UpdatedAt', 'Version', 'VersionCreatedAt', 'VersionCreatedBy', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'visible', 'code', 'title', 'entity', 'serializedColumns', 'serializedCondition', 'indexEngineDriverConfigurationId', 'createdAt', 'updatedAt', 'version', 'versionCreatedAt', 'versionCreatedBy', ),
        self::TYPE_COLNAME       => array(IndexEngineIndexVersionTableMap::ID, IndexEngineIndexVersionTableMap::VISIBLE, IndexEngineIndexVersionTableMap::CODE, IndexEngineIndexVersionTableMap::TITLE, IndexEngineIndexVersionTableMap::ENTITY, IndexEngineIndexVersionTableMap::SERIALIZED_COLUMNS, IndexEngineIndexVersionTableMap::SERIALIZED_CONDITION, IndexEngineIndexVersionTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID, IndexEngineIndexVersionTableMap::CREATED_AT, IndexEngineIndexVersionTableMap::UPDATED_AT, IndexEngineIndexVersionTableMap::VERSION, IndexEngineIndexVersionTableMap::VERSION_CREATED_AT, IndexEngineIndexVersionTableMap::VERSION_CREATED_BY, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'VISIBLE', 'CODE', 'TITLE', 'ENTITY', 'SERIALIZED_COLUMNS', 'SERIALIZED_CONDITION', 'INDEX_ENGINE_DRIVER_CONFIGURATION_ID', 'CREATED_AT', 'UPDATED_AT', 'VERSION', 'VERSION_CREATED_AT', 'VERSION_CREATED_BY', ),
        self::TYPE_FIELDNAME     => array('id', 'visible', 'code', 'title', 'entity', 'serialized_columns', 'serialized_condition', 'index_engine_driver_configuration_id', 'created_at', 'updated_at', 'version', 'version_created_at', 'version_created_by', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Visible' => 1, 'Code' => 2, 'Title' => 3, 'Entity' => 4, 'SerializedColumns' => 5, 'SerializedCondition' => 6, 'IndexEngineDriverConfigurationId' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, 'Version' => 10, 'VersionCreatedAt' => 11, 'VersionCreatedBy' => 12, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'visible' => 1, 'code' => 2, 'title' => 3, 'entity' => 4, 'serializedColumns' => 5, 'serializedCondition' => 6, 'indexEngineDriverConfigurationId' => 7, 'createdAt' => 8, 'updatedAt' => 9, 'version' => 10, 'versionCreatedAt' => 11, 'versionCreatedBy' => 12, ),
        self::TYPE_COLNAME       => array(IndexEngineIndexVersionTableMap::ID => 0, IndexEngineIndexVersionTableMap::VISIBLE => 1, IndexEngineIndexVersionTableMap::CODE => 2, IndexEngineIndexVersionTableMap::TITLE => 3, IndexEngineIndexVersionTableMap::ENTITY => 4, IndexEngineIndexVersionTableMap::SERIALIZED_COLUMNS => 5, IndexEngineIndexVersionTableMap::SERIALIZED_CONDITION => 6, IndexEngineIndexVersionTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID => 7, IndexEngineIndexVersionTableMap::CREATED_AT => 8, IndexEngineIndexVersionTableMap::UPDATED_AT => 9, IndexEngineIndexVersionTableMap::VERSION => 10, IndexEngineIndexVersionTableMap::VERSION_CREATED_AT => 11, IndexEngineIndexVersionTableMap::VERSION_CREATED_BY => 12, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'VISIBLE' => 1, 'CODE' => 2, 'TITLE' => 3, 'ENTITY' => 4, 'SERIALIZED_COLUMNS' => 5, 'SERIALIZED_CONDITION' => 6, 'INDEX_ENGINE_DRIVER_CONFIGURATION_ID' => 7, 'CREATED_AT' => 8, 'UPDATED_AT' => 9, 'VERSION' => 10, 'VERSION_CREATED_AT' => 11, 'VERSION_CREATED_BY' => 12, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'visible' => 1, 'code' => 2, 'title' => 3, 'entity' => 4, 'serialized_columns' => 5, 'serialized_condition' => 6, 'index_engine_driver_configuration_id' => 7, 'created_at' => 8, 'updated_at' => 9, 'version' => 10, 'version_created_at' => 11, 'version_created_by' => 12, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('index_engine_index_version');
        $this->setPhpName('IndexEngineIndexVersion');
        $this->setClassName('\\IndexEngine\\Model\\IndexEngineIndexVersion');
        $this->setPackage('IndexEngine.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('ID', 'Id', 'INTEGER' , 'index_engine_index', 'ID', true, null, null);
        $this->addColumn('VISIBLE', 'Visible', 'TINYINT', true, null, 0);
        $this->addColumn('CODE', 'Code', 'VARCHAR', true, 255, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('ENTITY', 'Entity', 'VARCHAR', true, 64, null);
        $this->addColumn('SERIALIZED_COLUMNS', 'SerializedColumns', 'CLOB', true, null, null);
        $this->addColumn('SERIALIZED_CONDITION', 'SerializedCondition', 'CLOB', true, null, null);
        $this->addColumn('INDEX_ENGINE_DRIVER_CONFIGURATION_ID', 'IndexEngineDriverConfigurationId', 'INTEGER', true, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addPrimaryKey('VERSION', 'Version', 'INTEGER', true, null, 0);
        $this->addColumn('VERSION_CREATED_AT', 'VersionCreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('VERSION_CREATED_BY', 'VersionCreatedBy', 'VARCHAR', false, 100, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('IndexEngineIndex', '\\IndexEngine\\Model\\IndexEngineIndex', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \IndexEngine\Model\IndexEngineIndexVersion $obj A \IndexEngine\Model\IndexEngineIndexVersion object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getId(), (string) $obj->getVersion()));
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \IndexEngine\Model\IndexEngineIndexVersion object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \IndexEngine\Model\IndexEngineIndexVersion) {
                $key = serialize(array((string) $value->getId(), (string) $value->getVersion()));

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \IndexEngine\Model\IndexEngineIndexVersion object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 10 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 10 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)]));
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return $pks;
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? IndexEngineIndexVersionTableMap::CLASS_DEFAULT : IndexEngineIndexVersionTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (IndexEngineIndexVersion object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = IndexEngineIndexVersionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = IndexEngineIndexVersionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + IndexEngineIndexVersionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = IndexEngineIndexVersionTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            IndexEngineIndexVersionTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = IndexEngineIndexVersionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = IndexEngineIndexVersionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                IndexEngineIndexVersionTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::ID);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::VISIBLE);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::CODE);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::TITLE);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::ENTITY);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::SERIALIZED_COLUMNS);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::SERIALIZED_CONDITION);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::CREATED_AT);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::UPDATED_AT);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::VERSION);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::VERSION_CREATED_AT);
            $criteria->addSelectColumn(IndexEngineIndexVersionTableMap::VERSION_CREATED_BY);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.VISIBLE');
            $criteria->addSelectColumn($alias . '.CODE');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.ENTITY');
            $criteria->addSelectColumn($alias . '.SERIALIZED_COLUMNS');
            $criteria->addSelectColumn($alias . '.SERIALIZED_CONDITION');
            $criteria->addSelectColumn($alias . '.INDEX_ENGINE_DRIVER_CONFIGURATION_ID');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
            $criteria->addSelectColumn($alias . '.VERSION');
            $criteria->addSelectColumn($alias . '.VERSION_CREATED_AT');
            $criteria->addSelectColumn($alias . '.VERSION_CREATED_BY');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(IndexEngineIndexVersionTableMap::DATABASE_NAME)->getTable(IndexEngineIndexVersionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(IndexEngineIndexVersionTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(IndexEngineIndexVersionTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new IndexEngineIndexVersionTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a IndexEngineIndexVersion or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or IndexEngineIndexVersion object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexVersionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \IndexEngine\Model\IndexEngineIndexVersion) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(IndexEngineIndexVersionTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(IndexEngineIndexVersionTableMap::ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(IndexEngineIndexVersionTableMap::VERSION, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = IndexEngineIndexVersionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { IndexEngineIndexVersionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { IndexEngineIndexVersionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the index_engine_index_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return IndexEngineIndexVersionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a IndexEngineIndexVersion or Criteria object.
     *
     * @param mixed               $criteria Criteria or IndexEngineIndexVersion object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexVersionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from IndexEngineIndexVersion object
        }


        // Set the correct dbName
        $query = IndexEngineIndexVersionQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // IndexEngineIndexVersionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
IndexEngineIndexVersionTableMap::buildTableMap();
