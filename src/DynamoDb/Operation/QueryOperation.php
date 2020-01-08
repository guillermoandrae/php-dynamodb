<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use ErrorException;
use Guillermoandrae\Common\Collection;
use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\DynamoDb\Constant\Operators;
use Guillermoandrae\DynamoDb\Contract\AbstractSearchOperation;
use Guillermoandrae\DynamoDb\Exception;

/**
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#query
 */
final class QueryOperation extends AbstractSearchOperation
{
    /**
     * @var string The condition that specifies the key values for items to be retrieved.
     */
    private $keyConditionExpression = '';

    /**
     * QueryRequest constructor.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $keyConditions OPTIONAL The key conditions.
     * @throws ErrorException
     */
    public function __construct(
        DynamoDbClient $client,
        Marshaler $marshaler,
        string $tableName,
        array $keyConditions = []
    ) {
        parent::__construct($client, $marshaler, $tableName);
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
     * @return QueryOperation This object.
     * @throws ErrorException Thrown when an unsupported operator is requested.
     */
    public function setPartitionKeyConditionExpression(string $keyName, $value): QueryOperation
    {
        $partitionKeyConditionExpression = $this->parseExpression(Operators::EQ, $keyName);
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
     * @return QueryOperation This object.
     * @throws ErrorException Thrown when an unsupported operator is requested.
     */
    public function setSortKeyConditionExpression(string $keyName, string $operator, $value): QueryOperation
    {
        $sortKeyConditionExpression = $this->parseExpression($operator, $keyName);
        $this->addExpressionAttributeValue($keyName, $value);
        $this->keyConditionExpression .= ' AND ' . $sortKeyConditionExpression;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $query = parent::toArray();
        $query['KeyConditionExpression'] = $this->keyConditionExpression;
        return $query;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(): CollectionInterface
    {
        try {
            $results = $this->client->query($this->toArray());
            $rows = [];
            foreach ($results['Items'] as $item) {
                $rows[] = $this->marshaler->unmarshalItem($item);
            }
            return Collection::make($rows);
        } catch (DynamoDbException $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
