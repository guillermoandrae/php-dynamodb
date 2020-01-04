<?php

namespace Guillermoandrae\Db\DynamoDb;

abstract class AbstractFilterExpressionAwareRequest extends AbstractItemRequest
{
    use LimitAwareRequestTrait;

    /**
     * @var string The filter expression.
     */
    protected $filterExpression;

    /**
     * Registers the filter expression with this object.
     *
     * @param array $data The filter expression data.
     * @return AbstractFilterExpressionAwareRequest An implementation of this abstract.
     */
    final public function setFilterExpression(array $data): AbstractFilterExpressionAwareRequest
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
    public function get(): array
    {
        $query = parent::get();
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
     */
    protected function parseExpression(string $operator, string $key): string
    {
        switch ($operator) {
            case RequestOperators::GT:
                return sprintf('%s > :%s', $key, $key);
            case RequestOperators::GTE:
                return sprintf('%s >= :%s', $key, $key);
            case RequestOperators::LT:
                return sprintf('%s < :%s', $key, $key);
            case RequestOperators::LTE:
                return sprintf('%s <= :%s', $key, $key);
            case RequestOperators::CONTAINS:
                return sprintf('contains(%s, :%s)', $key, $key);
            case RequestOperators::EQ:
            default:
                return sprintf('%s = :%s', $key, $key);
        }
    }
}
