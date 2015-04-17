<?php

namespace IndexEngine\Model\Base;

use \Exception;
use \PDO;
use IndexEngine\Model\IndexEngineDriverConfiguration as ChildIndexEngineDriverConfiguration;
use IndexEngine\Model\IndexEngineDriverConfigurationQuery as ChildIndexEngineDriverConfigurationQuery;
use IndexEngine\Model\Map\IndexEngineDriverConfigurationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'index_engine_driver_configuration' table.
 *
 *
 *
 * @method     ChildIndexEngineDriverConfigurationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildIndexEngineDriverConfigurationQuery orderByDriverCode($order = Criteria::ASC) Order by the driver_code column
 * @method     ChildIndexEngineDriverConfigurationQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildIndexEngineDriverConfigurationQuery orderBySerializedConfiguration($order = Criteria::ASC) Order by the serialized_configuration column
 *
 * @method     ChildIndexEngineDriverConfigurationQuery groupById() Group by the id column
 * @method     ChildIndexEngineDriverConfigurationQuery groupByDriverCode() Group by the driver_code column
 * @method     ChildIndexEngineDriverConfigurationQuery groupByTitle() Group by the title column
 * @method     ChildIndexEngineDriverConfigurationQuery groupBySerializedConfiguration() Group by the serialized_configuration column
 *
 * @method     ChildIndexEngineDriverConfigurationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildIndexEngineDriverConfigurationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildIndexEngineDriverConfigurationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildIndexEngineDriverConfigurationQuery leftJoinIndexEngineIndex($relationAlias = null) Adds a LEFT JOIN clause to the query using the IndexEngineIndex relation
 * @method     ChildIndexEngineDriverConfigurationQuery rightJoinIndexEngineIndex($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IndexEngineIndex relation
 * @method     ChildIndexEngineDriverConfigurationQuery innerJoinIndexEngineIndex($relationAlias = null) Adds a INNER JOIN clause to the query using the IndexEngineIndex relation
 *
 * @method     ChildIndexEngineDriverConfigurationQuery leftJoinIndexEngineIndexTemplate($relationAlias = null) Adds a LEFT JOIN clause to the query using the IndexEngineIndexTemplate relation
 * @method     ChildIndexEngineDriverConfigurationQuery rightJoinIndexEngineIndexTemplate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IndexEngineIndexTemplate relation
 * @method     ChildIndexEngineDriverConfigurationQuery innerJoinIndexEngineIndexTemplate($relationAlias = null) Adds a INNER JOIN clause to the query using the IndexEngineIndexTemplate relation
 *
 * @method     ChildIndexEngineDriverConfiguration findOne(ConnectionInterface $con = null) Return the first ChildIndexEngineDriverConfiguration matching the query
 * @method     ChildIndexEngineDriverConfiguration findOneOrCreate(ConnectionInterface $con = null) Return the first ChildIndexEngineDriverConfiguration matching the query, or a new ChildIndexEngineDriverConfiguration object populated from the query conditions when no match is found
 *
 * @method     ChildIndexEngineDriverConfiguration findOneById(int $id) Return the first ChildIndexEngineDriverConfiguration filtered by the id column
 * @method     ChildIndexEngineDriverConfiguration findOneByDriverCode(string $driver_code) Return the first ChildIndexEngineDriverConfiguration filtered by the driver_code column
 * @method     ChildIndexEngineDriverConfiguration findOneByTitle(string $title) Return the first ChildIndexEngineDriverConfiguration filtered by the title column
 * @method     ChildIndexEngineDriverConfiguration findOneBySerializedConfiguration(string $serialized_configuration) Return the first ChildIndexEngineDriverConfiguration filtered by the serialized_configuration column
 *
 * @method     array findById(int $id) Return ChildIndexEngineDriverConfiguration objects filtered by the id column
 * @method     array findByDriverCode(string $driver_code) Return ChildIndexEngineDriverConfiguration objects filtered by the driver_code column
 * @method     array findByTitle(string $title) Return ChildIndexEngineDriverConfiguration objects filtered by the title column
 * @method     array findBySerializedConfiguration(string $serialized_configuration) Return ChildIndexEngineDriverConfiguration objects filtered by the serialized_configuration column
 *
 */
abstract class IndexEngineDriverConfigurationQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \IndexEngine\Model\Base\IndexEngineDriverConfigurationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\IndexEngine\\Model\\IndexEngineDriverConfiguration', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildIndexEngineDriverConfigurationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildIndexEngineDriverConfigurationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \IndexEngine\Model\IndexEngineDriverConfigurationQuery) {
            return $criteria;
        }
        $query = new \IndexEngine\Model\IndexEngineDriverConfigurationQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildIndexEngineDriverConfiguration|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = IndexEngineDriverConfigurationTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(IndexEngineDriverConfigurationTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildIndexEngineDriverConfiguration A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, DRIVER_CODE, TITLE, SERIALIZED_CONFIGURATION FROM index_engine_driver_configuration WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildIndexEngineDriverConfiguration();
            $obj->hydrate($row);
            IndexEngineDriverConfigurationTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildIndexEngineDriverConfiguration|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(IndexEngineDriverConfigurationTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(IndexEngineDriverConfigurationTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(IndexEngineDriverConfigurationTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(IndexEngineDriverConfigurationTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IndexEngineDriverConfigurationTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the driver_code column
     *
     * Example usage:
     * <code>
     * $query->filterByDriverCode('fooValue');   // WHERE driver_code = 'fooValue'
     * $query->filterByDriverCode('%fooValue%'); // WHERE driver_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $driverCode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function filterByDriverCode($driverCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($driverCode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $driverCode)) {
                $driverCode = str_replace('*', '%', $driverCode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IndexEngineDriverConfigurationTableMap::DRIVER_CODE, $driverCode, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IndexEngineDriverConfigurationTableMap::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the serialized_configuration column
     *
     * Example usage:
     * <code>
     * $query->filterBySerializedConfiguration('fooValue');   // WHERE serialized_configuration = 'fooValue'
     * $query->filterBySerializedConfiguration('%fooValue%'); // WHERE serialized_configuration LIKE '%fooValue%'
     * </code>
     *
     * @param     string $serializedConfiguration The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function filterBySerializedConfiguration($serializedConfiguration = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($serializedConfiguration)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $serializedConfiguration)) {
                $serializedConfiguration = str_replace('*', '%', $serializedConfiguration);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IndexEngineDriverConfigurationTableMap::SERIALIZED_CONFIGURATION, $serializedConfiguration, $comparison);
    }

    /**
     * Filter the query by a related \IndexEngine\Model\IndexEngineIndex object
     *
     * @param \IndexEngine\Model\IndexEngineIndex|ObjectCollection $indexEngineIndex  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function filterByIndexEngineIndex($indexEngineIndex, $comparison = null)
    {
        if ($indexEngineIndex instanceof \IndexEngine\Model\IndexEngineIndex) {
            return $this
                ->addUsingAlias(IndexEngineDriverConfigurationTableMap::ID, $indexEngineIndex->getIndexEngineDriverConfigurationId(), $comparison);
        } elseif ($indexEngineIndex instanceof ObjectCollection) {
            return $this
                ->useIndexEngineIndexQuery()
                ->filterByPrimaryKeys($indexEngineIndex->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIndexEngineIndex() only accepts arguments of type \IndexEngine\Model\IndexEngineIndex or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the IndexEngineIndex relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function joinIndexEngineIndex($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('IndexEngineIndex');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'IndexEngineIndex');
        }

        return $this;
    }

    /**
     * Use the IndexEngineIndex relation IndexEngineIndex object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \IndexEngine\Model\IndexEngineIndexQuery A secondary query class using the current class as primary query
     */
    public function useIndexEngineIndexQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinIndexEngineIndex($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'IndexEngineIndex', '\IndexEngine\Model\IndexEngineIndexQuery');
    }

    /**
     * Filter the query by a related \IndexEngine\Model\IndexEngineIndexTemplate object
     *
     * @param \IndexEngine\Model\IndexEngineIndexTemplate|ObjectCollection $indexEngineIndexTemplate  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function filterByIndexEngineIndexTemplate($indexEngineIndexTemplate, $comparison = null)
    {
        if ($indexEngineIndexTemplate instanceof \IndexEngine\Model\IndexEngineIndexTemplate) {
            return $this
                ->addUsingAlias(IndexEngineDriverConfigurationTableMap::ID, $indexEngineIndexTemplate->getIndexEngineDriverConfigurationId(), $comparison);
        } elseif ($indexEngineIndexTemplate instanceof ObjectCollection) {
            return $this
                ->useIndexEngineIndexTemplateQuery()
                ->filterByPrimaryKeys($indexEngineIndexTemplate->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIndexEngineIndexTemplate() only accepts arguments of type \IndexEngine\Model\IndexEngineIndexTemplate or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the IndexEngineIndexTemplate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function joinIndexEngineIndexTemplate($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('IndexEngineIndexTemplate');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'IndexEngineIndexTemplate');
        }

        return $this;
    }

    /**
     * Use the IndexEngineIndexTemplate relation IndexEngineIndexTemplate object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \IndexEngine\Model\IndexEngineIndexTemplateQuery A secondary query class using the current class as primary query
     */
    public function useIndexEngineIndexTemplateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIndexEngineIndexTemplate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'IndexEngineIndexTemplate', '\IndexEngine\Model\IndexEngineIndexTemplateQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildIndexEngineDriverConfiguration $indexEngineDriverConfiguration Object to remove from the list of results
     *
     * @return ChildIndexEngineDriverConfigurationQuery The current query, for fluid interface
     */
    public function prune($indexEngineDriverConfiguration = null)
    {
        if ($indexEngineDriverConfiguration) {
            $this->addUsingAlias(IndexEngineDriverConfigurationTableMap::ID, $indexEngineDriverConfiguration->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the index_engine_driver_configuration table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineDriverConfigurationTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            IndexEngineDriverConfigurationTableMap::clearInstancePool();
            IndexEngineDriverConfigurationTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildIndexEngineDriverConfiguration or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildIndexEngineDriverConfiguration object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineDriverConfigurationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(IndexEngineDriverConfigurationTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        IndexEngineDriverConfigurationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            IndexEngineDriverConfigurationTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // IndexEngineDriverConfigurationQuery
