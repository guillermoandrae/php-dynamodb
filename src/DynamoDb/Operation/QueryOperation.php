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
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

/**
 * Query operation.
 *
 * Note about offset and limit: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Query.html#Query.Limit
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#query
 */
final class QueryOperation extends AbstractSearchOperation
{
    /**
     * @var string The condition that specifies the key values for items to be retrieved.
     */
    private string $keyConditionExpression = '';

    /**
     * @var boolean Whether or not to scan forward.
     */
    private bool $scanIndexForward = false;

    /**
     * QueryRequest constructor.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array|null $keyConditions OPTIONAL The key conditions.
     * @throws ErrorException
     */
    public function __construct(
        DynamoDbClient $client,
        Marshaler      $marshaler,
        string         $tableName,
        ?array         $keyConditions = []
    ) {
        parent::__construct($client, $marshaler, $tableName);
        if (!empty($keyConditions)) {
            foreach ($keyConditions as $key => $condition) {
                switch ($key) {
                    case 'partition':
                        $this->setPartitionKeyConditionExpression(
                            $condition['name'],
                            $condition['value']
                        );
                        break;
                    case 'sort':
                        $this->setSortKeyConditionExpression(
                            $condition['name'],
                            $condition['operator'],
                            $condition['value']
                        );
                        break;
                    default:
                        $this->setExpression([
                            $key => $condition,
                        ]);
                        break;
                }
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
    public function setPartitionKeyConditionExpression(string $keyName, mixed $value): QueryOperation
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
    public function setSortKeyConditionExpression(string $keyName, string $operator, mixed $value): QueryOperation
    {
        $sortKeyConditionExpression = $this->parseExpression($operator, $keyName);
        $this->addExpressionAttributeValue($keyName, $value);
        $this->keyConditionExpression .= ' AND ' . $sortKeyConditionExpression;
        return $this;
    }

    /**
     * Registers the ScanIndexForward value with this object.
     *
     * @param boolean $scanIndexForward Whether or not to scan forward.
     * @return QueryOperation This object.
     */
    public function setScanIndexForward(bool $scanIndexForward): QueryOperation
    {
        $this->scanIndexForward = $scanIndexForward;
        return $this;
    }

    public function execute(): CollectionInterface
    {
        try {
            $results = $this->client->query($this->toArray());
            $rows = [];
            foreach ($results['Items'] as $item) {
                $rows[] = $this->marshaler->unmarshalItem($item);
            }
            return Collection::make($rows)->limit($this->offset);
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        }
    }

    public function toArray(): array
    {
        $operation = parent::toArray();
        $operation['ScanIndexForward'] = $this->scanIndexForward;
        if (!empty($this->keyConditionExpression)) {
            $operation['KeyConditionExpression'] = $this->keyConditionExpression;
        }
        return $operation;
    }
}
