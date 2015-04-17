<?php

namespace IndexEngine\Model\Base;

use \Exception;
use \PDO;
use IndexEngine\Model\IndexEngineIndexTemplateVersion as ChildIndexEngineIndexTemplateVersion;
use IndexEngine\Model\IndexEngineIndexTemplateVersionQuery as ChildIndexEngineIndexTemplateVersionQuery;
use IndexEngine\Model\Map\IndexEngineIndexTemplateVersionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'index_engine_index_template_version' table.
 *
 *
 *
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByVisible($order = Criteria::ASC) Order by the visible column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByEntity($order = Criteria::ASC) Order by the entity column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderBySerializedColumns($order = Criteria::ASC) Order by the serialized_columns column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderBySerializedCondition($order = Criteria::ASC) Order by the serialized_condition column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByIndexEngineDriverConfigurationId($order = Criteria::ASC) Order by the index_engine_driver_configuration_id column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByVersionCreatedAt($order = Criteria::ASC) Order by the version_created_at column
 * @method     ChildIndexEngineIndexTemplateVersionQuery orderByVersionCreatedBy($order = Criteria::ASC) Order by the version_created_by column
 *
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupById() Group by the id column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByVisible() Group by the visible column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByCode() Group by the code column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByTitle() Group by the title column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByEntity() Group by the entity column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupBySerializedColumns() Group by the serialized_columns column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupBySerializedCondition() Group by the serialized_condition column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByIndexEngineDriverConfigurationId() Group by the index_engine_driver_configuration_id column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByVersion() Group by the version column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByVersionCreatedAt() Group by the version_created_at column
 * @method     ChildIndexEngineIndexTemplateVersionQuery groupByVersionCreatedBy() Group by the version_created_by column
 *
 * @method     ChildIndexEngineIndexTemplateVersionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildIndexEngineIndexTemplateVersionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildIndexEngineIndexTemplateVersionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildIndexEngineIndexTemplateVersionQuery leftJoinIndexEngineIndexTemplate($relationAlias = null) Adds a LEFT JOIN clause to the query using the IndexEngineIndexTemplate relation
 * @method     ChildIndexEngineIndexTemplateVersionQuery rightJoinIndexEngineIndexTemplate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IndexEngineIndexTemplate relation
 * @method     ChildIndexEngineIndexTemplateVersionQuery innerJoinIndexEngineIndexTemplate($relationAlias = null) Adds a INNER JOIN clause to the query using the IndexEngineIndexTemplate relation
 *
 * @method     ChildIndexEngineIndexTemplateVersion findOne(ConnectionInterface $con = null) Return the first ChildIndexEngineIndexTemplateVersion matching the query
 * @method     ChildIndexEngineIndexTemplateVersion findOneOrCreate(ConnectionInterface $con = null) Return the first ChildIndexEngineIndexTemplateVersion matching the query, or a new ChildIndexEngineIndexTemplateVersion object populated from the query conditions when no match is found
 *
 * @method     ChildIndexEngineIndexTemplateVersion findOneById(int $id) Return the first ChildIndexEngineIndexTemplateVersion filtered by the id column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByVisible(int $visible) Return the first ChildIndexEngineIndexTemplateVersion filtered by the visible column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByCode(string $code) Return the first ChildIndexEngineIndexTemplateVersion filtered by the code column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByTitle(string $title) Return the first ChildIndexEngineIndexTemplateVersion filtered by the title column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByEntity(string $entity) Return the first ChildIndexEngineIndexTemplateVersion filtered by the entity column
 * @method     ChildIndexEngineIndexTemplateVersion findOneBySerializedColumns(string $serialized_columns) Return the first ChildIndexEngineIndexTemplateVersion filtered by the serialized_columns column
 * @method     ChildIndexEngineIndexTemplateVersion findOneBySerializedCondition(string $serialized_condition) Return the first ChildIndexEngineIndexTemplateVersion filtered by the serialized_condition column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByIndexEngineDriverConfigurationId(int $index_engine_driver_configuration_id) Return the first ChildIndexEngineIndexTemplateVersion filtered by the index_engine_driver_configuration_id column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByCreatedAt(string $created_at) Return the first ChildIndexEngineIndexTemplateVersion filtered by the created_at column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByUpdatedAt(string $updated_at) Return the first ChildIndexEngineIndexTemplateVersion filtered by the updated_at column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByVersion(int $version) Return the first ChildIndexEngineIndexTemplateVersion filtered by the version column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByVersionCreatedAt(string $version_created_at) Return the first ChildIndexEngineIndexTemplateVersion filtered by the version_created_at column
 * @method     ChildIndexEngineIndexTemplateVersion findOneByVersionCreatedBy(string $version_created_by) Return the first ChildIndexEngineIndexTemplateVersion filtered by the version_created_by column
 *
 * @method     array findById(int $id) Return ChildIndexEngineIndexTemplateVersion objects filtered by the id column
 * @method     array findByVisible(int $visible) Return ChildIndexEngineIndexTemplateVersion objects filtered by the visible column
 * @method     array findByCode(string $code) Return ChildIndexEngineIndexTemplateVersion objects filtered by the code column
 * @method     array findByTitle(string $title) Return ChildIndexEngineIndexTemplateVersion objects filtered by the title column
 * @method     array findByEntity(string $entity) Return ChildIndexEngineIndexTemplateVersion objects filtered by the entity column
 * @method     array findBySerializedColumns(string $serialized_columns) Return ChildIndexEngineIndexTemplateVersion objects filtered by the serialized_columns column
 * @method     array findBySerializedCondition(string $serialized_condition) Return ChildIndexEngineIndexTemplateVersion objects filtered by the serialized_condition column
 * @method     array findByIndexEngineDriverConfigurationId(int $index_engine_driver_configuration_id) Return ChildIndexEngineIndexTemplateVersion objects filtered by the index_engine_driver_configuration_id column
 * @method     array findByCreatedAt(string $created_at) Return ChildIndexEngineIndexTemplateVersion objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildIndexEngineIndexTemplateVersion objects filtered by the updated_at column
 * @method     array findByVersion(int $version) Return ChildIndexEngineIndexTemplateVersion objects filtered by the version column
 * @method     array findByVersionCreatedAt(string $version_created_at) Return ChildIndexEngineIndexTemplateVersion objects filtered by the version_created_at column
 * @method     array findByVersionCreatedBy(string $version_created_by) Return ChildIndexEngineIndexTemplateVersion objects filtered by the version_created_by column
 *
 */
abstract class IndexEngineIndexTemplateVersionQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \IndexEngine\Model\Base\IndexEngineIndexTemplateVersionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\IndexEngine\\Model\\IndexEngineIndexTemplateVersion', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildIndexEngineIndexTemplateVersionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \IndexEngine\Model\IndexEngineIndexTemplateVersionQuery) {
            return $criteria;
        }
        $query = new \IndexEngine\Model\IndexEngineIndexTemplateVersionQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$id, $version] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildIndexEngineIndexTemplateVersion|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = IndexEngineIndexTemplateVersionTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(IndexEngineIndexTemplateVersionTableMap::DATABASE_NAME);
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
     * @return   ChildIndexEngineIndexTemplateVersion A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, VISIBLE, CODE, TITLE, ENTITY, SERIALIZED_COLUMNS, SERIALIZED_CONDITION, INDEX_ENGINE_DRIVER_CONFIGURATION_ID, CREATED_AT, UPDATED_AT, VERSION, VERSION_CREATED_AT, VERSION_CREATED_BY FROM index_engine_index_template_version WHERE ID = :p0 AND VERSION = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildIndexEngineIndexTemplateVersion();
            $obj->hydrate($row);
            IndexEngineIndexTemplateVersionTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildIndexEngineIndexTemplateVersion|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VERSION, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(IndexEngineIndexTemplateVersionTableMap::ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(IndexEngineIndexTemplateVersionTableMap::VERSION, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByIndexEngineIndexTemplate()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the visible column
     *
     * Example usage:
     * <code>
     * $query->filterByVisible(1234); // WHERE visible = 1234
     * $query->filterByVisible(array(12, 34)); // WHERE visible IN (12, 34)
     * $query->filterByVisible(array('min' => 12)); // WHERE visible > 12
     * </code>
     *
     * @param     mixed $visible The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByVisible($visible = null, $comparison = null)
    {
        if (is_array($visible)) {
            $useMinMax = false;
            if (isset($visible['min'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VISIBLE, $visible['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($visible['max'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VISIBLE, $visible['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VISIBLE, $visible, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::CODE, $code, $comparison);
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
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the entity column
     *
     * Example usage:
     * <code>
     * $query->filterByEntity('fooValue');   // WHERE entity = 'fooValue'
     * $query->filterByEntity('%fooValue%'); // WHERE entity LIKE '%fooValue%'
     * </code>
     *
     * @param     string $entity The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByEntity($entity = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($entity)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $entity)) {
                $entity = str_replace('*', '%', $entity);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::ENTITY, $entity, $comparison);
    }

    /**
     * Filter the query on the serialized_columns column
     *
     * Example usage:
     * <code>
     * $query->filterBySerializedColumns('fooValue');   // WHERE serialized_columns = 'fooValue'
     * $query->filterBySerializedColumns('%fooValue%'); // WHERE serialized_columns LIKE '%fooValue%'
     * </code>
     *
     * @param     string $serializedColumns The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterBySerializedColumns($serializedColumns = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($serializedColumns)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $serializedColumns)) {
                $serializedColumns = str_replace('*', '%', $serializedColumns);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::SERIALIZED_COLUMNS, $serializedColumns, $comparison);
    }

    /**
     * Filter the query on the serialized_condition column
     *
     * Example usage:
     * <code>
     * $query->filterBySerializedCondition('fooValue');   // WHERE serialized_condition = 'fooValue'
     * $query->filterBySerializedCondition('%fooValue%'); // WHERE serialized_condition LIKE '%fooValue%'
     * </code>
     *
     * @param     string $serializedCondition The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterBySerializedCondition($serializedCondition = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($serializedCondition)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $serializedCondition)) {
                $serializedCondition = str_replace('*', '%', $serializedCondition);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::SERIALIZED_CONDITION, $serializedCondition, $comparison);
    }

    /**
     * Filter the query on the index_engine_driver_configuration_id column
     *
     * Example usage:
     * <code>
     * $query->filterByIndexEngineDriverConfigurationId(1234); // WHERE index_engine_driver_configuration_id = 1234
     * $query->filterByIndexEngineDriverConfigurationId(array(12, 34)); // WHERE index_engine_driver_configuration_id IN (12, 34)
     * $query->filterByIndexEngineDriverConfigurationId(array('min' => 12)); // WHERE index_engine_driver_configuration_id > 12
     * </code>
     *
     * @param     mixed $indexEngineDriverConfigurationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByIndexEngineDriverConfigurationId($indexEngineDriverConfigurationId = null, $comparison = null)
    {
        if (is_array($indexEngineDriverConfigurationId)) {
            $useMinMax = false;
            if (isset($indexEngineDriverConfigurationId['min'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID, $indexEngineDriverConfigurationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($indexEngineDriverConfigurationId['max'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID, $indexEngineDriverConfigurationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::INDEX_ENGINE_DRIVER_CONFIGURATION_ID, $indexEngineDriverConfigurationId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion(1234); // WHERE version = 1234
     * $query->filterByVersion(array(12, 34)); // WHERE version IN (12, 34)
     * $query->filterByVersion(array('min' => 12)); // WHERE version > 12
     * </code>
     *
     * @param     mixed $version The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the version_created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByVersionCreatedAt('2011-03-14'); // WHERE version_created_at = '2011-03-14'
     * $query->filterByVersionCreatedAt('now'); // WHERE version_created_at = '2011-03-14'
     * $query->filterByVersionCreatedAt(array('max' => 'yesterday')); // WHERE version_created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $versionCreatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByVersionCreatedAt($versionCreatedAt = null, $comparison = null)
    {
        if (is_array($versionCreatedAt)) {
            $useMinMax = false;
            if (isset($versionCreatedAt['min'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_AT, $versionCreatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($versionCreatedAt['max'])) {
                $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_AT, $versionCreatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_AT, $versionCreatedAt, $comparison);
    }

    /**
     * Filter the query on the version_created_by column
     *
     * Example usage:
     * <code>
     * $query->filterByVersionCreatedBy('fooValue');   // WHERE version_created_by = 'fooValue'
     * $query->filterByVersionCreatedBy('%fooValue%'); // WHERE version_created_by LIKE '%fooValue%'
     * </code>
     *
     * @param     string $versionCreatedBy The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByVersionCreatedBy($versionCreatedBy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($versionCreatedBy)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $versionCreatedBy)) {
                $versionCreatedBy = str_replace('*', '%', $versionCreatedBy);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::VERSION_CREATED_BY, $versionCreatedBy, $comparison);
    }

    /**
     * Filter the query by a related \IndexEngine\Model\IndexEngineIndexTemplate object
     *
     * @param \IndexEngine\Model\IndexEngineIndexTemplate|ObjectCollection $indexEngineIndexTemplate The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function filterByIndexEngineIndexTemplate($indexEngineIndexTemplate, $comparison = null)
    {
        if ($indexEngineIndexTemplate instanceof \IndexEngine\Model\IndexEngineIndexTemplate) {
            return $this
                ->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::ID, $indexEngineIndexTemplate->getId(), $comparison);
        } elseif ($indexEngineIndexTemplate instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IndexEngineIndexTemplateVersionTableMap::ID, $indexEngineIndexTemplate->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function joinIndexEngineIndexTemplate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useIndexEngineIndexTemplateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinIndexEngineIndexTemplate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'IndexEngineIndexTemplate', '\IndexEngine\Model\IndexEngineIndexTemplateQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildIndexEngineIndexTemplateVersion $indexEngineIndexTemplateVersion Object to remove from the list of results
     *
     * @return ChildIndexEngineIndexTemplateVersionQuery The current query, for fluid interface
     */
    public function prune($indexEngineIndexTemplateVersion = null)
    {
        if ($indexEngineIndexTemplateVersion) {
            $this->addCond('pruneCond0', $this->getAliasedColName(IndexEngineIndexTemplateVersionTableMap::ID), $indexEngineIndexTemplateVersion->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(IndexEngineIndexTemplateVersionTableMap::VERSION), $indexEngineIndexTemplateVersion->getVersion(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the index_engine_index_template_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexTemplateVersionTableMap::DATABASE_NAME);
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
            IndexEngineIndexTemplateVersionTableMap::clearInstancePool();
            IndexEngineIndexTemplateVersionTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildIndexEngineIndexTemplateVersion or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildIndexEngineIndexTemplateVersion object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(IndexEngineIndexTemplateVersionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(IndexEngineIndexTemplateVersionTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        IndexEngineIndexTemplateVersionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            IndexEngineIndexTemplateVersionTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // IndexEngineIndexTemplateVersionQuery
