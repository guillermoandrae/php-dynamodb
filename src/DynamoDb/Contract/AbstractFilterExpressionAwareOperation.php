<?php

namespace Guillermoandrae\DynamoDb\Contract;

use ErrorException;
use Guillermoandrae\DynamoDb\Constant\Operators;

abstract class AbstractFilterExpressionAwareOperation extends AbstractItemOperation
{
    use LimitAwareOperationTrait;

    /**
     * @var string The filter expression.
     */
    protected $filterExpression;

    /**
     * Registers the filter expression with this object.
     *
     * @param array $data The filter expression data.
     * @return AbstractFilterExpressionAwareOperation An implementation of this abstract.
     * @throws ErrorException
     */
    final public function setFilterExpression(array $data): AbstractFilterExpressionAwareOperation
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
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $query = parent::toArray();
        if ($this->limit) {
            $query['Limit'] = $this->limit;
        }
        if ($this->filterExpression) {
            $query['FilterExpression'] = $this->filterExpression;
        }
        return $query;
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
}
