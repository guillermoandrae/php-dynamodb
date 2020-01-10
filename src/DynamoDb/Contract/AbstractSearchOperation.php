<?php

namespace Guillermoandrae\DynamoDb\Contract;

abstract class AbstractSearchOperation extends AbstractOperation implements SearchOperationInterface
{
    use LimitAwareOperationTrait, FilterExpressionAwareOperationTrait, ReturnConsumedCapacityAwareOperationTrait {
        LimitAwareOperationTrait::toArray as limitAwareTraitToArray;
        FilterExpressionAwareOperationTrait::toArray as filterExpressionAwareTraitToArray;
        ReturnConsumedCapacityAwareOperationTrait::toArray as returnConsumedCapacityAwareTraitToArray;
    }

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
     * @return AbstractSearchOperation This object.
     */
    public function setScanIndexForward(bool $scanIndexForward): AbstractSearchOperation
    {
        $this->scanIndexForward = $scanIndexForward;
        return $this;
    }

    /**
     * Registers the ConsistentRead value with this object.
     *
     * @param boolean $consistentRead Whether or not the read should be consistent.
     * @return AbstractSearchOperation This object.
     */
    public function setConsistentRead(bool $consistentRead): AbstractSearchOperation
    {
        $this->consistentRead = $consistentRead;
        return $this;
    }

    /**
     * Registers the name of the secondary index to use.
     *
     * @param string $indexName The name of the secondary index to use.
     * @return AbstractSearchOperation This object.
     */
    public function setIndexName(string $indexName): AbstractSearchOperation
    {
        $this->indexName = $indexName;
        return $this;
    }

    /**
     * Registers the attributes to be return in the result.
     *
     * @param string $select The attributes to be return in the result.
     * @return AbstractSearchOperation This object.
     */
    public function setSelect(string $select): AbstractSearchOperation
    {
        $this->select = $select;
        return $this;
    }

    /**
     * Registers the attributes to retrieve from the specified table or index.
     *
     * @param string $projectionExpression The attributes to retrieve from the specified table or index.
     * @return AbstractSearchOperation This object.
     */
    public function setProjectionExpression(string $projectionExpression): AbstractSearchOperation
    {
        $this->projectionExpression = $projectionExpression;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $operation = parent::toArray();
        if ($this->limit) {
            $operation += $this->limitAwareTraitToArray();
        }
        $operation += $this->returnConsumedCapacityAwareTraitToArray();
        $operation += $this->filterExpressionAwareTraitToArray();
        $operation['ScanIndexForward'] = $this->scanIndexForward;
        $operation['ConsistentRead'] = $this->consistentRead;
        if (!empty($this->indexName)) {
            $operation['IndexName'] = $this->indexName;
        }
        if (!empty($this->select)) {
            $operation['Select'] = $this->select;
        }
        if (!empty($this->projectionExpression)) {
            $operation['ProjectionExpression'] = $this->projectionExpression;
        }
        return $operation;
    }
}
