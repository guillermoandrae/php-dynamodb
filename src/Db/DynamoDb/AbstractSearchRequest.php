<?php

namespace Guillermoandrae\Db\DynamoDb;

abstract class AbstractSearchRequest extends AbstractFilterExpressionAwareRequest
{
    /**
     * @var string The name of the index.
     */
    const SELECT_ALL_ATTRIBUTES = 'ALL_ATTRIBUTES';

    /**
     * @var string The name of the index.
     */
    const SELECT_ALL_PROJECTED_ATTRIBUTES = 'ALL_PROJECTED_ATTRIBUTES';

    /**
     * @var string The name of the index.
     */
    const SELECT_SPECIFIC_ATTRIBUTES = 'SPECIFIC_ATTRIBUTES';

    /**
     * @var string The name of the index.
     */
    const SELECT_COUNT = 'COUNT';

    /**
     * @var boolean Whether or not to scan forward.
     */
    private $scanIndexForward = false;

    /**
     * @var boolean Whether or not the read should be consistent.
     */
    private $consistentRead = false;

    /**
     * @var string The name of a secondary index to request against.
     */
    private $indexName = '';

    /**
     * @var string The attributes to be returned in the result.
     */
    private $select = '';

    /**
     * @var string The attributes to retrieve from the specified table or index.
     */
    private $projectionExpression = '';

    /**
     * Registers the ScanIndexForward value with this object.
     *
     * @param boolean $scanIndexForward Whether or not to scan forward.
     * @return AbstractSearchRequest This object.
     */
    public function setScanIndexForward(bool $scanIndexForward): AbstractSearchRequest
    {
        $this->scanIndexForward = $scanIndexForward;
        return $this;
    }

    /**
     * Registers the ConsistentRead value with this object.
     *
     * @param boolean $consistentRead Whether or not the read should be consistent.
     * @return AbstractSearchRequest This object.
     */
    public function setConsistentRead(bool $consistentRead): AbstractSearchRequest
    {
        $this->consistentRead = $consistentRead;
        return $this;
    }

    /**
     * Registers the name of the secondary index to use.
     *
     * @param string $indexName The name of the secondary index to use.
     * @return AbstractSearchRequest This object.
     */
    public function setIndexName(string $indexName): AbstractSearchRequest
    {
        $this->indexName = $indexName;
        return $this;
    }

    /**
     * Registers the attributes to be return in the result.
     *
     * @param string $select The attributes to be return in the result.
     * @return AbstractSearchRequest This object.
     */
    public function setSelect(string $select): AbstractSearchRequest
    {
        $this->select = $select;
        return $this;
    }

    /**
     * Registers the attributes to retrieve from the specified table or index.
     *
     * @param string $projectionExpression The attributes to retrieve from the specified table or index.
     * @return AbstractSearchRequest This object.
     */
    public function setProjectionExpression(string $projectionExpression): AbstractSearchRequest
    {
        $this->projectionExpression = $projectionExpression;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        $query = parent::get();
        $query['ScanIndexForward'] = $this->scanIndexForward;
        $query['ConsistentRead'] = $this->consistentRead;
        if (!empty($this->indexName)) {
            $query['IndexName'] = $this->indexName;
        }
        if (!empty($this->select)) {
            $query['Select'] = $this->select;
        }
        if (!empty($this->projectionExpression)) {
            $query['ProjectionExpression'] = $this->projectionExpression;
        }
        return $query;
    }
}
