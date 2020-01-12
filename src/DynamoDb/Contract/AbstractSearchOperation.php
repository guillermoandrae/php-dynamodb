<?php

namespace Guillermoandrae\DynamoDb\Contract;

/**
 * Abstract for search operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
abstract class AbstractSearchOperation extends AbstractOperation implements SearchOperationInterface
{
    use LimitAwareOperationTrait,
        ExpressionAttributeValueAwareOperationTrait,
        ReturnConsumedCapacityAwareOperationTrait {
        LimitAwareOperationTrait::toArray as limitAwareTraitToArray;
        ExpressionAttributeValueAwareOperationTrait::toArray as expressionAwareTraitToArray;
        ReturnConsumedCapacityAwareOperationTrait::toArray as returnConsumedCapacityAwareTraitToArray;
    }

    /**
     * @var boolean Whether or not the read should be consistent.
     */
    private $consistentRead = false;

    /**
     * @var string The name of a secondary index to request against.
     */
    private $indexName = '';

    /**
     * @var string The attributes to retrieve from the specified table or index.
     */
    private $projectionExpression = '';

    /**
     * @var string The attributes to be returned in the result.
     */
    private $select = '';

    final public function setConsistentRead(bool $consistentRead): SearchOperationInterface
    {
        $this->consistentRead = $consistentRead;
        return $this;
    }

    final public function setIndexName(string $indexName): SearchOperationInterface
    {
        $this->indexName = $indexName;
        return $this;
    }

    final public function setSelect(string $select): SearchOperationInterface
    {
        $this->select = $select;
        return $this;
    }

    final public function setProjectionExpression(string $projectionExpression): SearchOperationInterface
    {
        $this->projectionExpression = $projectionExpression;
        return $this;
    }

    public function toArray(): array
    {
        $operation = parent::toArray();
        if ($this->limit) {
            $operation += $this->limitAwareTraitToArray();
        }
        $operation += $this->returnConsumedCapacityAwareTraitToArray();
        $operation += $this->expressionAwareTraitToArray();
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
