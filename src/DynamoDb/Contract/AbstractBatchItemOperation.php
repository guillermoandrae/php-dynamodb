<?php

namespace Guillermoandrae\DynamoDb\Contract;

abstract class AbstractBatchItemOperation extends AbstractOperation implements BatchItemOperationInterface
{
    /**
     * The request items.
     *
     * @var array
     */
    protected array $requestItems = [];

    use ReturnConsumedCapacityAwareOperationTrait {
        ReturnConsumedCapacityAwareOperationTrait::toArray as returnConsumedCapacityAwareOperationTraitToArray;
    }

    final public function setRequestItems(array $requestItems): BatchItemOperationInterface
    {
        $this->requestItems = $requestItems;
        return $this;
    }

    public function toArray(): array
    {
        $operation = $this->returnConsumedCapacityAwareOperationTraitToArray();
        $operation['RequestItems'] = $this->requestItems;
        return $operation;
    }
}
