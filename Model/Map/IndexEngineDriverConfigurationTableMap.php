<?php

namespace IndexEngine\Model\Map;

use IndexEngine\Model\IndexEngineDriverConfiguration;
use IndexEngine\Model\IndexEngineDriverConfigurationQuery;
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
 * This class defines the structure of the 'index_engine_driver_configuration' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class IndexEngineDriverConfigurationTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'IndexEngine.Model.Map.IndexEngineDriverConfigurationTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'index_engine_driver_configuration';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\IndexEngine\\Model\\IndexEngineDriverConfiguration';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'IndexEngine.Model.IndexEngineDriverConfiguration';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 4;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 4;

    /**
     * the column name for the ID field
     */
    const ID = 'index_engine_driver_configuration.ID';

    /**
     * the column name for the DRIVER_CODE field
     */
    const DRIVER_CODE = 'index_engine_driver_configuration.DRIVER_CODE';

    /**
     * the column name for the TITLE field
     */
    const TITLE = 'index_engine_driver_configuration.TITLE';

    /**
     * the column name for the SERIALIZED_CONFIGURATION field
     */
    const SERIALIZED_CONFIGURATION = 'index_engine_driver_configuration.SERIALIZED_CONFIGURATION';

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
        self::TYPE_PHPNAME       => array('Id', 'DriverCode', 'Title', 'SerializedConfiguration', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'driverCode', 'title', 'serializedConfiguration', ),
        self::TYPE_COLNAME       => array(IndexEngineDriverConfigurationTableMap::ID, IndexEngineDriverConfigurationTableMap::DRIVER_CODE, IndexEngineDriverConfigurationTableMap::TITLE, IndexEngineDriverConfigurationTableMap::SERIALIZED_CONFIGURATION, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'DRIVER_CODE', 'TITLE', 'SERIALIZED_CONFIGURATION', ),
        self::TYPE_FIELDNAME     => array('id', 'driver_code', 'title', 'serialized_configuration', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'DriverCode' => 1, 'Title' => 2, 'SerializedConfiguration' => 3, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'driverCode' => 1, 'title' => 2, 'serializedConfiguration' => 3, ),
        self::TYPE_COLNAME       => array(IndexEngineDriverConfigurationTableMap::ID => 0, IndexEngineDriverConfigurationTableMap::DRIVER_CODE => 1, IndexEngineDriverConfigurationTableMap::TITLE => 2, IndexEngineDriverConfigurationTableMap::SERIALIZED_CONFIGURATION => 3, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'DRIVER_CODE' => 1, 'TITLE' => 2, 'SERIALIZED_CONFIGURATION' => 3, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'driver_code' => 1, 'title' => 2, 'serialized_configuration' => 3, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
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
        $this->setName('index_engine_driver_configuration');
        $this->setPhpName('IndexEngineDriverConfiguration');
        $this->setClassName('\\IndexEngine\\Model\\IndexEngineDriverConfiguration');
        $this->setPackage('IndexEngine.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('DRIVER_CODE', 'DriverCode', 'VARCHAR', true, 64, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('SERIALIZED_CONFIGURATION', 'SerializedConfiguration', 'CLOB', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('IndexEngineIndex', '\\IndexEngine\\Model\\IndexEngineIndex', RelationMap::ONE_TO_MANY, array('id' => 'index_engine_driver_configuration_id', ), 'CASCADE', 'CASCADE', 'IndexEngineIndices');
        $this->addRelation('IndexEngineIndexTemplate', '\\IndexEngine\\Model\\IndexEngineIndexTemplate', RelationMap::ONE_TO_MANY, array('id' => 'index_engine_driver_configuration_id', ), 'CASCADE', 'CASCADE', 'IndexEngineIndexTemplates');
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to index_engine_driver_configuration     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ".$this->getClassNameFromBuilder($joinedTableTableMapBuilder)." instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
                IndexEngineIndexTableMap::clearInstancePool();
                IndexEngineIndexTemplateTableMap::clearInstancePool();
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
        return $withPrefix ? IndexEngineDriverConfigurationTableMap::CLASS_DEFAULT : IndexEngineDriverConfigurationTableMap::OM_CLASS;
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
     * @return array (IndexEngineDriverConfiguration object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = IndexEngineDriverConfigurationTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = IndexEngineDriverConfigurationTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + IndexEngineDriverConfigurationTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = IndexEngineDriverConfigurationTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            IndexEngineDriverConfigurationTableMap::addInstanceToPool($obj, $key);
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
            $key = IndexEngineDriverConfigurationTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = IndexEngineDriverConfigurationTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                IndexEngineDriverConfigurationTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(IndexEngineDriverConfigurationTableMap::ID);
            $criteria->addSelectColumn(IndexEngineDriverConfigurationTableMap::DRIVER_CODE);
            $criteria->addSelectColumn(IndexEngineDriverConfigurationTableMap::TITLE);
            $criteria->addSelectColumn(IndexEngineDriverConfigurationTableMap::SERIALIZED_CONFIGURATION);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.DRIVER_CODE');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.SERIALIZED_CONFIGURATION');
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
        return Propel::getServiceContainer()->getDatabaseMap(IndexEngineDriverConfigurationTableMap::DATABASE_NAME)->getTable(IndexEngineDriverConfigurationTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(IndexEngineDriverConfigurationTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(IndexEngineDriverConfigurationTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new IndexEngineDriverConfigurationTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a IndexEngineDriverConfiguration or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or IndexEngineDriverConfiguration object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineDriverConfigurationTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \IndexEngine\Model\IndexEngineDriverConfiguration) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(IndexEngineDriverConfigurationTableMap::DATABASE_NAME);
            $criteria->add(IndexEngineDriverConfigurationTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = IndexEngineDriverConfigurationQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { IndexEngineDriverConfigurationTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { IndexEngineDriverConfigurationTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the index_engine_driver_configuration table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return IndexEngineDriverConfigurationQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a IndexEngineDriverConfiguration or Criteria object.
     *
     * @param mixed               $criteria Criteria or IndexEngineDriverConfiguration object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineDriverConfigurationTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from IndexEngineDriverConfiguration object
        }

        if ($criteria->containsKey(IndexEngineDriverConfigurationTableMap::ID) && $criteria->keyContainsValue(IndexEngineDriverConfigurationTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.IndexEngineDriverConfigurationTableMap::ID.')');
        }


        // Set the correct dbName
        $query = IndexEngineDriverConfigurationQuery::create()->mergeWith($criteria);

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

} // IndexEngineDriverConfigurationTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
IndexEngineDriverConfigurationTableMap::buildTableMap();
