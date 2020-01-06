<?php

namespace Guillermoandrae\Db\DynamoDb;

use ErrorException;

final class QueryRequest extends AbstractSearchRequest
{
    /**
     * @var string The condition that specifies the key values for items to be retrieved.
     */
    private $keyConditionExpression = '';

    /**
     * Sets condition expression for the partition key.
     *
     * @param string $keyName The name of the partition key.
     * @param string $operator The operator.
     * @param string $value The desired value.
     * @return QueryRequest This object.
     * @throws ErrorException Thrown when an unsupported operator is requested.
     */
    public function setPartitionKeyConditionExpression(string $keyName, string $operator, string $value): QueryRequest
    {
        $partitionKeyConditionExpression = $this->parseExpression($operator, $keyName);
        $this->addExpressionAttributeValue($keyName, $value);
        $this->keyConditionExpression = $partitionKeyConditionExpression;
        return $this;
    }

    /**
     * Sets condition expressions for the sort key.
     *
     * @param string $keyName The name of the sort key.
     * @param string $operator The operator.
     * @param string $value The desired value.
     * @return QueryRequest This object.
     * @throws ErrorException Thrown when an unsupported operator is requested.
     */
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
