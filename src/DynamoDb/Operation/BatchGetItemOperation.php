<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\Contract\AbstractOperation;
use Guillermoandrae\DynamoDb\Contract\ReturnConsumedCapacityAwareOperationTrait;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

/**
 * BatchGetItem operation.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#batchgetitem
 */
final class BatchGetItemOperation extends AbstractOperation
{
    protected $primaryKeys = [];

    use ReturnConsumedCapacityAwareOperationTrait {
        ReturnConsumedCapacityAwareOperationTrait::toArray as returnConsumedCapacityAwareOperationTraitToArray;
    }

    /**
     * Registers the DynamoDb client, Marshaler, and the mapping of tables and primary keys with this object.
     *
     * @param DynamoDbClient $client The DynamoDB client.
     * @param Marshaler $marshaler The Marshaler.
     * @param array $primaryKeys The tables and primary keys.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, array $primaryKeys)
    {
        parent::__construct($client, $marshaler);
        $this->setPrimaryKeys($primaryKeys);
    }

    public function setPrimaryKeys(array $primaryKeys): BatchGetItemOperation
    {
        $this->primaryKeys = [];
        foreach ($primaryKeys as $tableName => $keys) {
            $this->primaryKeys[$tableName] = ['Keys' => []];
            foreach ($keys as $key) {
                $this->primaryKeys[$tableName]['Keys'][] = $this->getMarshaler()->marshalItem($key);
            }
        }
        return $this;
    }

    public function execute()
    {
        try {
            $items = [];
            $result = $this->client->batchGetItem($this->toArray());
            foreach ($result['Responses'] as $table => $itemArrays) {
                foreach ($itemArrays as $itemArray) {
                    $items[] = $this->getMarshaler()->unmarshalItem($itemArray);
                }
            }
            return $items;
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        }
    }

    public function toArray(): array
    {
        $operation = $this->returnConsumedCapacityAwareOperationTraitToArray();
        $operation['RequestItems'] = $this->primaryKeys;
        return $operation;
    }
}
