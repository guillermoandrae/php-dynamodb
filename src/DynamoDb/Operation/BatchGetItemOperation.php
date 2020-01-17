<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\Contract\AbstractBatchItemOperation;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

/**
 * BatchGetItem operation.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#batchgetitem
 */
final class BatchGetItemOperation extends AbstractBatchItemOperation
{
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
        $requestItems = [];
        foreach ($primaryKeys as $tableName => $keys) {
            $requestItems[$tableName] = ['Keys' => []];
            foreach ($keys as $key) {
                $requestItems[$tableName]['Keys'][] = $this->getMarshaler()->marshalItem($key);
            }
        }
        $this->setRequestItems($requestItems);
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
}
