<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Guillermoandrae\DynamoDb\Constant\ConsumedCapacityOptions;

abstract class AbstractItemOperation extends AbstractTableAwareOperation
{
    /**
     * @var array Values that can be substituted in an expression.
     */
    protected $expressionAttributeValues = [];
    
    /**
     * @var string The level of detail about provisioned throughput consumption that is returned in the response.
     */
    protected $returnConsumedCapacity = ConsumedCapacityOptions::NONE;

    /**
     * Adds an ExpressionAttributeValue to the request.
     *
     * @param string $key The attribute token.
     * @param mixed $value The attribute value.
     * @return AbstractItemOperation An implementation of this abstract.
     */
    final public function addExpressionAttributeValue(string $key, $value): AbstractItemOperation
    {
        $this->expressionAttributeValues[sprintf(':%s', $key)] = $this->marshaler->marshalValue($value);
        return $this;
    }

    /**
     * Registers the desired level of consumption detail to return.
     *
     * @param string $returnConsumedCapacity The level of consumption detail to return.
     * @return AbstractItemOperation An implementation of this abstract.
     */
    final public function setReturnConsumedCapacity(string $returnConsumedCapacity): AbstractItemOperation
    {
        $this->returnConsumedCapacity = $returnConsumedCapacity;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $query = parent::toArray();
        if (!empty($this->expressionAttributeValues)) {
            $query['ExpressionAttributeValues'] = $this->expressionAttributeValues;
        }
        $query['ReturnConsumedCapacity'] = $this->returnConsumedCapacity;
        return $query;
    }
}
