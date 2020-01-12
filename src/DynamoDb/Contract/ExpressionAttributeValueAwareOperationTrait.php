<?php

namespace Guillermoandrae\DynamoDb\Contract;

use ErrorException;
use Guillermoandrae\DynamoDb\Constant\Operators;

/**
 * Trait for ExpressionAttributeValue aware operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
trait ExpressionAttributeValueAwareOperationTrait
{
    use DynamoDbClientAwareTrait;

    /**
     * @var string The name of the desired expression field.
     */
    protected $expressionFieldName = 'FilterExpression';

    /**
     * @var string The expression.
     */
    protected $expression = '';

    /**
     * @var array Values that can be substituted in an expression.
     */
    protected $expressionAttributeValues = [];

    /**
     * Registers the expression with this object.
     *
     * @param array $data The filter expression data.
     * @return mixed This object.
     * @throws ErrorException Thrown when an invalid operator is provided.
     */
    final public function setExpression(array $data)
    {
        $expressionArray = [];
        foreach ($data as $key => $options) {
            $expressionArray[] = $this->parseExpression($options['operator'], $key);
            $this->addExpressionAttributeValue($key, $options['value']);
        }
        $this->expression = implode(' and ', $expressionArray);
        return $this;
    }

    /**
     * Uses the operator to build the filter expression.
     *
     * @param string $operator The request operator.
     * @param string $key The attribute key.
     * @return string The expression.
     * @throws ErrorException Thrown when an invalid operator is provided.
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

    public function toArray(): array
    {
        $operation = [];
        if (!empty($this->expression)) {
            $operation[$this->expressionFieldName] = $this->expression;
        }
        if (!empty($this->expressionAttributeValues)) {
            $operation['ExpressionAttributeValues'] = $this->expressionAttributeValues;
        }
        return $operation;
    }
}
