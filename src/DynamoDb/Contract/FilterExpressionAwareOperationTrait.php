<?php

namespace Guillermoandrae\DynamoDb\Contract;

use ErrorException;
use Guillermoandrae\DynamoDb\Constant\Operators;

trait FilterExpressionAwareOperationTrait
{
    use DynamoDbClientAwareTrait;

    /**
     * @var string The filter expression.
     */
    protected $filterExpression;

    /**
     * @var array Values that can be substituted in an expression.
     */
    protected $expressionAttributeValues = [];

    /**
     * Registers the filter expression with this object.
     *
     * @param array $data The filter expression data.
     * @return mixed This object.
     * @throws ErrorException
     */
    final public function setFilterExpression(array $data)
    {
        $filterExpressionArray = [];
        foreach ($data as $key => $options) {
            $filterExpressionArray[] = $this->parseExpression($options['operator'], $key);
            $this->addExpressionAttributeValue($key, $options['value']);
        }
        $this->filterExpression = implode(' and ', $filterExpressionArray);
        return $this;
    }

    /**
     * Uses the operator to build the filter expression.
     *
     * @param string $operator The request operator.
     * @param string $key The attribute key.
     * @return string The expression.
     * @throws ErrorException
     */
    protected function parseExpression(string $operator, string $key): string
    {
        switch ($operator) {
            case Operators::BEGINS_WITH:
                return sprintf('begins_with(%s, :%s)', $key, $key);
            case Operators::CONTAINS:
                return sprintf('contains(%s, :%s)', $key, $key);
            case Operators::EQ:
                return sprintf('%s = :%s', $key, $key);
            case Operators::GT:
                return sprintf('%s > :%s', $key, $key);
            case Operators::GTE:
                return sprintf('%s >= :%s', $key, $key);
            case Operators::LT:
                return sprintf('%s < :%s', $key, $key);
            case Operators::LTE:
                return sprintf('%s <= :%s', $key, $key);
            default:
                throw new ErrorException('The provided operator is not supported.');
        }
    }

    /**
     * Adds an ExpressionAttributeValue to the request.
     *
     * @param string $key The attribute token.
     * @param mixed $value The attribute value.
     * @return mixed This object.
     */
    final public function addExpressionAttributeValue(string $key, $value)
    {
        $this->expressionAttributeValues[sprintf(':%s', $key)] = $this->getMarshaler()->marshalValue($value);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $operation = [];
        if (!empty($this->filterExpression)) {
            $operation['FilterExpression'] = $this->filterExpression;
        }
        if (!empty($this->expressionAttributeValues)) {
            $operation['ExpressionAttributeValues'] = $this->expressionAttributeValues;
        }
        return $operation;
    }
}
