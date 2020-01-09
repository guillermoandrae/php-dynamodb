<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Common\Collection;
use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\DynamoDb\Contract\AbstractSearchOperation;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

/**
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#scan
 */
final class ScanOperation extends AbstractSearchOperation
{
    /**
     * Registers the DynamoDb client, Marshaler, and table name with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName)
    {
        $this->setClient($client);
        $this->setMarshaler($marshaler);
        $this->setTableName($tableName);
    }

    /**
     * {@inheritDoc}
     */
    public function execute(): CollectionInterface
    {
        try {
            $results = $this->client->scan($this->toArray());
            $rows = [];
            foreach ($results['Items'] as $item) {
                $rows[] = $this->marshaler->unmarshalItem($item);
            }
            return Collection::make($rows);
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        }
    }
}
