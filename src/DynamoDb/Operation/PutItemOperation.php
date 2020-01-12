<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\Contract\AbstractItemOperation;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

/**
 * PutItem operation.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#putitem
 */
final class PutItemOperation extends AbstractItemOperation
{
    /**
     * @var array $itemData The item data.
     */
    protected $itemData;

    /**
     * Registers the DynamoDb client, Marshaler, table name, and item data with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $itemData The item data.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName, array $itemData)
    {
        $this->setClient($client);
        $this->setMarshaler($marshaler);
        $this->setTableName($tableName);
        $this->setItemData($itemData);
    }

    /**
     * Registers the item data with this object.
     *
     * @param array $item The item data.
     * @return PutItemOperation This object.
     */
    public function setItemData(array $item): PutItemOperation
    {
        $this->itemData = $this->marshaler->marshalItem($item);
        return $this;
    }

    public function execute(): bool
    {
        try {
            $this->getClient()->putItem($this->toArray());
            return true;
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        }
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'Item' => $this->itemData,
        ]);
    }
}
