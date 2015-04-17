<?php

namespace IndexEngine\Model\Map;

use IndexEngine\Model\IndexEngineIndexTemplateVersion;
use IndexEngine\Model\IndexEngineIndexTemplateVersionQuery;
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
 * This class defines the structure of the 'index_engine_index_template_version' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class IndexEngineIndexTemplateVersionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'IndexEngine.Model.Map.IndexEngineIndexTemplateVersionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'index_engine_index_template_version';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\IndexEngine\\Model\\IndexEngineIndexTemplateVersion';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'IndexEngine.Model.IndexEngineIndexTemplateVersion';

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
    const ID = 'index_engine_index_template_version.ID';

    /**
     * the column name for the VISIBLE field
     */
    const VISIBLE = 'index_engine_index_template_version.VISIBLE';

    /**
     * the column name for the CODE field
     */
    const CODE = 'index_engine_index_template_version.CODE';

    /**
     * the column name for the TITLE field
     */
    const TITLE = 'index_engine_index_template_version.TITLE';

    /**
     * the column name for the ENTITY field
     */
    const ENTITY = 'index_engine_index_template_version.ENTITY';

    /**
     * the column name for the SERIALIZED_COLUMNS field
     */
    const SERIALIZED_COLUMNS = 'index_engine_index_template_version.SERIALIZED_COLUMNS';

    /**
     * the column name for the SERIALIZED_CONDITION field
     */
    const SERIALIZED_CONDITION = 'index_engine_index_template_version.SERIALIZED_CONDITION';

    /**
     * the column name for the INDEX_ENGINE_DRIVER_CONFIGURATION_ID field
     */
    const INDEX_ENGINE_DRIVER_CONFIGURATION_ID = 'index_engine_index_template_version.INDEX_ENGINE_DRIVER_CONFIGURATION_ID';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'index_engine_index_template_version.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'index_engine_index_template_version.UPDATED_AT';

    /**
     * the column name for the VERSION field
     */
    const VERSION = 'index_engine_index_template_version.VERSION';

    /**
     * the column name for the VERSION_CREATED_AT field
     */
    const VERSION_CREATED_AT = 'index_engine_index_template_version.VERSION_CREATED_AT';

    /**
     * the column name for the VERSION_CREATED_BY field
     */
    const VERSION_CREATED_BY = 'index_engine_index_template_version.VERSION_CREATED_BY';

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
        self::TYPE_COLNAME       => array(IndexEngineIndexTemplateVersionTableMap::ID, IndexEngineIndexTemplateVersionTableMap::VISIBLE, IndexEngineIndexTemplateVersionTableMap::CODE, IndexEngineIndexTemplateVersionTableMap::TITLE, IndexEngineIndexTemplateVersionTableMap::ENTITY, IndexEngineIndexTemplateVersionTableMap::SERIALIZED_COLUMNS, IndexEngineIndexTemplateVersionTableMap::SERIALIZED_CONDITION, IndexEngineIndexTemplateVersionTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID, IndexEngineIndexTemplateVersionTableMap::CREATED_AT, IndexEngineIndexTemplateVersionTableMap::UPDATED_AT, IndexEngineIndexTemplateVersionTableMap::VERSION, IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_AT, IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_BY, ),
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
        self::TYPE_COLNAME       => array(IndexEngineIndexTemplateVersionTableMap::ID => 0, IndexEngineIndexTemplateVersionTableMap::VISIBLE => 1, IndexEngineIndexTemplateVersionTableMap::CODE => 2, IndexEngineIndexTemplateVersionTableMap::TITLE => 3, IndexEngineIndexTemplateVersionTableMap::ENTITY => 4, IndexEngineIndexTemplateVersionTableMap::SERIALIZED_COLUMNS => 5, IndexEngineIndexTemplateVersionTableMap::SERIALIZED_CONDITION => 6, IndexEngineIndexTemplateVersionTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID => 7, IndexEngineIndexTemplateVersionTableMap::CREATED_AT => 8, IndexEngineIndexTemplateVersionTableMap::UPDATED_AT => 9, IndexEngineIndexTemplateVersionTableMap::VERSION => 10, IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_AT => 11, IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_BY => 12, ),
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
        $this->setName('index_engine_index_template_version');
        $this->setPhpName('IndexEngineIndexTemplateVersion');
        $this->setClassName('\\IndexEngine\\Model\\IndexEngineIndexTemplateVersion');
        $this->setPackage('IndexEngine.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('ID', 'Id', 'INTEGER' , 'index_engine_index_template', 'ID', true, null, null);
        $this->addColumn('VISIBLE', 'Visible', 'TINYINT', true, null, 0);
        $this->addColumn('CODE', 'Code', 'VARCHAR', true, 255, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('ENTITY', 'Entity', 'VARCHAR', true, 64, null);
        $this->addColumn('SERIALIZED_COLUMNS', 'SerializedColumns', 'CLOB', true, null, null);
        $this->addColumn('SERIALIZED_CONDITION', 'SerializedCondition', 'CLOB', true, null, null);
        $this->addColumn('INDEX_ENGINE_DRIVER_CONFIGURATION_ID', 'IndexEngineDriverConfigurationId', 'INTEGER', false, null, null);
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
        $this->addRelation('IndexEngineIndexTemplate', '\\IndexEngine\\Model\\IndexEngineIndexTemplate', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \IndexEngine\Model\IndexEngineIndexTemplateVersion $obj A \IndexEngine\Model\IndexEngineIndexTemplateVersion object.
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
     * @param mixed $value A \IndexEngine\Model\IndexEngineIndexTemplateVersion object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \IndexEngine\Model\IndexEngineIndexTemplateVersion) {
                $key = serialize(array((string) $value->getId(), (string) $value->getVersion()));

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \IndexEngine\Model\IndexEngineIndexTemplateVersion object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
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
        return $withPrefix ? IndexEngineIndexTemplateVersionTableMap::CLASS_DEFAULT : IndexEngineIndexTemplateVersionTableMap::OM_CLASS;
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
     * @return array (IndexEngineIndexTemplateVersion object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = IndexEngineIndexTemplateVersionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = IndexEngineIndexTemplateVersionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + IndexEngineIndexTemplateVersionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = IndexEngineIndexTemplateVersionTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            IndexEngineIndexTemplateVersionTableMap::addInstanceToPool($obj, $key);
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
            $key = IndexEngineIndexTemplateVersionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = IndexEngineIndexTemplateVersionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                IndexEngineIndexTemplateVersionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::ID);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::VISIBLE);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::CODE);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::TITLE);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::ENTITY);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::SERIALIZED_COLUMNS);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::SERIALIZED_CONDITION);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::CREATED_AT);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::UPDATED_AT);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::VERSION);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_AT);
            $criteria->addSelectColumn(IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_BY);
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
        return Propel::getServiceContainer()->getDatabaseMap(IndexEngineIndexTemplateVersionTableMap::DATABASE_NAME)->getTable(IndexEngineIndexTemplateVersionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(IndexEngineIndexTemplateVersionTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(IndexEngineIndexTemplateVersionTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new IndexEngineIndexTemplateVersionTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a IndexEngineIndexTemplateVersion or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or IndexEngineIndexTemplateVersion object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexTemplateVersionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \IndexEngine\Model\IndexEngineIndexTemplateVersion) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(IndexEngineIndexTemplateVersionTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(IndexEngineIndexTemplateVersionTableMap::ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(IndexEngineIndexTemplateVersionTableMap::VERSION, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = IndexEngineIndexTemplateVersionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { IndexEngineIndexTemplateVersionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { IndexEngineIndexTemplateVersionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the index_engine_index_template_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return IndexEngineIndexTemplateVersionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a IndexEngineIndexTemplateVersion or Criteria object.
     *
     * @param mixed               $criteria Criteria or IndexEngineIndexTemplateVersion object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexTemplateVersionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from IndexEngineIndexTemplateVersion object
        }


        // Set the correct dbName
        $query = IndexEngineIndexTemplateVersionQuery::create()->mergeWith($criteria);

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

} // IndexEngineIndexTemplateVersionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
IndexEngineIndexTemplateVersionTableMap::buildTableMap();
