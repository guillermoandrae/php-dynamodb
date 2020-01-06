<?php

namespace Guillermoandrae\Db\DynamoDb;

final class QueryRequest extends AbstractSearchRequest
{
    private $queryFilter = [];
    private $keyConditionExpression = '';
    private $keyConditions = [];

    public function setPartitionKeyConditionExpression(string $keyName, string $operator, string $value): QueryRequest
    {
        $partitionKeyConditionExpression = $this->parseExpression($operator, $keyName);
        $this->addExpressionAttributeValue($keyName, $value);
        $this->keyConditionExpression = $partitionKeyConditionExpression;
        return $this;
    }

    public function setSortKeyConditionExpression(string $keyName, string $operator, string $value): QueryRequest
    {
        $sortKeyConditionExpression = $this->parseExpression($operator, $keyName);
        $this->addExpressionAttributeValue($keyName, $value);
        $this->keyConditionExpression .= ' AND ' . $sortKeyConditionExpression;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        $query = parent::get();
        $query['KeyConditionExpression'] = $this->keyConditionExpression;
        return $query;
    }
}
