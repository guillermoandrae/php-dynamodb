<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\Contract\AbstractItemOperation;
use Guillermoandrae\DynamoDb\Exception;

final class PutItemOperation extends AbstractItemOperation
{
    /**
     * @var array $item The item data.
     */
    private $item;
    
    /**
     * PutItemRequest constructor.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $item The item data.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName, array $item)
    {
        parent::__construct($client, $marshaler, $tableName);
        $this->setItem($item);
    }

    /**
     * Registers the item data with this object.
     *
     * @param array $item The item data.
     * @return PutItemOperation This object.
     */
    public function setItem(array $item): PutItemOperation
    {
        $this->item = $this->marshaler->marshalItem($item);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'TableName' => $this->tableName,
            'Item' => $this->item,
        ];
    }

    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#putitem
     */
    public function execute(): bool
    {
        try {
            $this->client->putItem($this->toArray());
            return true;
        } catch (DynamoDbException $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
