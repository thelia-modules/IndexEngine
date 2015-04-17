<?php

namespace IndexEngine\Model\Map;

use IndexEngine\Model\IndexEngineIndex;
use IndexEngine\Model\IndexEngineIndexQuery;
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
 * This class defines the structure of the 'index_engine_index' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class IndexEngineIndexTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'IndexEngine.Model.Map.IndexEngineIndexTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'index_engine_index';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\IndexEngine\\Model\\IndexEngineIndex';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'IndexEngine.Model.IndexEngineIndex';

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
    const ID = 'index_engine_index.ID';

    /**
     * the column name for the VISIBLE field
     */
    const VISIBLE = 'index_engine_index.VISIBLE';

    /**
     * the column name for the CODE field
     */
    const CODE = 'index_engine_index.CODE';

    /**
     * the column name for the TITLE field
     */
    const TITLE = 'index_engine_index.TITLE';

    /**
     * the column name for the ENTITY field
     */
    const ENTITY = 'index_engine_index.ENTITY';

    /**
     * the column name for the SERIALIZED_COLUMNS field
     */
    const SERIALIZED_COLUMNS = 'index_engine_index.SERIALIZED_COLUMNS';

    /**
     * the column name for the SERIALIZED_CONDITION field
     */
    const SERIALIZED_CONDITION = 'index_engine_index.SERIALIZED_CONDITION';

    /**
     * the column name for the INDEX_ENGINE_DRIVER_CONFIGURATION_ID field
     */
    const INDEX_ENGINE_DRIVER_CONFIGURATION_ID = 'index_engine_index.INDEX_ENGINE_DRIVER_CONFIGURATION_ID';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'index_engine_index.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'index_engine_index.UPDATED_AT';

    /**
     * the column name for the VERSION field
     */
    const VERSION = 'index_engine_index.VERSION';

    /**
     * the column name for the VERSION_CREATED_AT field
     */
    const VERSION_CREATED_AT = 'index_engine_index.VERSION_CREATED_AT';

    /**
     * the column name for the VERSION_CREATED_BY field
     */
    const VERSION_CREATED_BY = 'index_engine_index.VERSION_CREATED_BY';

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
        self::TYPE_COLNAME       => array(IndexEngineIndexTableMap::ID, IndexEngineIndexTableMap::VISIBLE, IndexEngineIndexTableMap::CODE, IndexEngineIndexTableMap::TITLE, IndexEngineIndexTableMap::ENTITY, IndexEngineIndexTableMap::SERIALIZED_COLUMNS, IndexEngineIndexTableMap::SERIALIZED_CONDITION, IndexEngineIndexTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID, IndexEngineIndexTableMap::CREATED_AT, IndexEngineIndexTableMap::UPDATED_AT, IndexEngineIndexTableMap::VERSION, IndexEngineIndexTableMap::VERSION_CREATED_AT, IndexEngineIndexTableMap::VERSION_CREATED_BY, ),
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
        self::TYPE_COLNAME       => array(IndexEngineIndexTableMap::ID => 0, IndexEngineIndexTableMap::VISIBLE => 1, IndexEngineIndexTableMap::CODE => 2, IndexEngineIndexTableMap::TITLE => 3, IndexEngineIndexTableMap::ENTITY => 4, IndexEngineIndexTableMap::SERIALIZED_COLUMNS => 5, IndexEngineIndexTableMap::SERIALIZED_CONDITION => 6, IndexEngineIndexTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID => 7, IndexEngineIndexTableMap::CREATED_AT => 8, IndexEngineIndexTableMap::UPDATED_AT => 9, IndexEngineIndexTableMap::VERSION => 10, IndexEngineIndexTableMap::VERSION_CREATED_AT => 11, IndexEngineIndexTableMap::VERSION_CREATED_BY => 12, ),
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
        $this->setName('index_engine_index');
        $this->setPhpName('IndexEngineIndex');
        $this->setClassName('\\IndexEngine\\Model\\IndexEngineIndex');
        $this->setPackage('IndexEngine.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('VISIBLE', 'Visible', 'TINYINT', true, null, 0);
        $this->addColumn('CODE', 'Code', 'VARCHAR', true, 255, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('ENTITY', 'Entity', 'VARCHAR', true, 64, null);
        $this->addColumn('SERIALIZED_COLUMNS', 'SerializedColumns', 'CLOB', true, null, null);
        $this->addColumn('SERIALIZED_CONDITION', 'SerializedCondition', 'CLOB', true, null, null);
        $this->addForeignKey('INDEX_ENGINE_DRIVER_CONFIGURATION_ID', 'IndexEngineDriverConfigurationId', 'INTEGER', 'index_engine_driver_configuration', 'ID', true, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('VERSION', 'Version', 'INTEGER', false, null, 0);
        $this->addColumn('VERSION_CREATED_AT', 'VersionCreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('VERSION_CREATED_BY', 'VersionCreatedBy', 'VARCHAR', false, 100, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('IndexEngineDriverConfiguration', '\\IndexEngineDriverConfiguration', RelationMap::MANY_TO_ONE, array('index_engine_driver_configuration_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('IndexEngineIndexVersion', '\\IndexEngine\\Model\\IndexEngineIndexVersion', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'IndexEngineIndexVersions');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
            'versionable' => array('version_column' => 'version', 'version_table' => '', 'log_created_at' => 'true', 'log_created_by' => 'true', 'log_comment' => 'false', 'version_created_at_column' => 'version_created_at', 'version_created_by_column' => 'version_created_by', 'version_comment_column' => 'version_comment', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to index_engine_index     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ".$this->getClassNameFromBuilder($joinedTableTableMapBuilder)." instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
                IndexEngineIndexVersionTableMap::clearInstancePool();
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
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
        return $withPrefix ? IndexEngineIndexTableMap::CLASS_DEFAULT : IndexEngineIndexTableMap::OM_CLASS;
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
     * @return array (IndexEngineIndex object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = IndexEngineIndexTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = IndexEngineIndexTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + IndexEngineIndexTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = IndexEngineIndexTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            IndexEngineIndexTableMap::addInstanceToPool($obj, $key);
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
            $key = IndexEngineIndexTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = IndexEngineIndexTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                IndexEngineIndexTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(IndexEngineIndexTableMap::ID);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::VISIBLE);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::CODE);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::TITLE);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::ENTITY);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::SERIALIZED_COLUMNS);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::SERIALIZED_CONDITION);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::CREATED_AT);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::UPDATED_AT);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::VERSION);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::VERSION_CREATED_AT);
            $criteria->addSelectColumn(IndexEngineIndexTableMap::VERSION_CREATED_BY);
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
        return Propel::getServiceContainer()->getDatabaseMap(IndexEngineIndexTableMap::DATABASE_NAME)->getTable(IndexEngineIndexTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(IndexEngineIndexTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(IndexEngineIndexTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new IndexEngineIndexTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a IndexEngineIndex or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or IndexEngineIndex object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \IndexEngine\Model\IndexEngineIndex) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(IndexEngineIndexTableMap::DATABASE_NAME);
            $criteria->add(IndexEngineIndexTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = IndexEngineIndexQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { IndexEngineIndexTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { IndexEngineIndexTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the index_engine_index table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return IndexEngineIndexQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a IndexEngineIndex or Criteria object.
     *
     * @param mixed               $criteria Criteria or IndexEngineIndex object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from IndexEngineIndex object
        }

        if ($criteria->containsKey(IndexEngineIndexTableMap::ID) && $criteria->keyContainsValue(IndexEngineIndexTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.IndexEngineIndexTableMap::ID.')');
        }


        // Set the correct dbName
        $query = IndexEngineIndexQuery::create()->mergeWith($criteria);

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

} // IndexEngineIndexTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
IndexEngineIndexTableMap::buildTableMap();
