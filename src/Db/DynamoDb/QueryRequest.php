<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use ErrorException;

final class QueryRequest extends AbstractSearchRequest
{
    /**
     * @var string The condition that specifies the key values for items to be retrieved.
     */
    private $keyConditionExpression = '';

    /**
     * Registers the Marshaler, table name, and key conditions with this object.
     *
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $keyConditions OPTIONAL The key conditions.
     * @throws ErrorException
     */
    public function __construct(Marshaler $marshaler, string $tableName, array $keyConditions = [])
    {
        parent::__construct($marshaler, $tableName);
        if (!empty($keyConditions)) {
            $this->setPartitionKeyConditionExpression(
                $keyConditions['partition']['name'],
                $keyConditions['partition']['value']
            );
            if (isset($keyConditions['sort'])) {
                $this->setSortKeyConditionExpression(
                    $keyConditions['sort']['name'],
                    $keyConditions['sort']['operator'],
                    $keyConditions['sort']['value']
                );
            }
        }
    }

    /**
     * Sets condition expression for the partition key.
     *
     * @param string $keyName The name of the partition key.
     * @param mixed $value The desired value.
     * @return QueryRequest This object.
     * @throws ErrorException Thrown when an unsupported operator is requested.
     */
    public function setPartitionKeyConditionExpression(string $keyName, $value): QueryRequest
    {
        $partitionKeyConditionExpression = $this->parseExpression(RequestOperators::EQ, $keyName);
        $this->addExpressionAttributeValue($keyName, $value);
        $this->keyConditionExpression = $partitionKeyConditionExpression;
        return $this;
    }

    /**
     * Sets condition expressions for the sort key.
     *
     * @param string $keyName The name of the sort key.
     * @param string $operator The operator.
     * @param mixed $value The desired value.
     * @return QueryRequest This object.
     * @throws ErrorException Thrown when an unsupported operator is requested.
     */
    public function setSortKeyConditionExpression(string $keyName, string $operator, $value): QueryRequest
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
