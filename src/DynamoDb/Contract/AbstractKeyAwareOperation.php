<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

abstract class AbstractKeyAwareOperation extends AbstractItemOperation
{
    /**
     * @var array The primary key values to be used when retrieving items.
     */
    protected $key;
    
    /**
     * Registers the DynamoDb client, Marshaler, table name, and primary key with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $key The primary key values to be used when retrieving items.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName, array $key)
    {
        parent::__construct($client, $marshaler, $tableName);
        $this->setKey($key);
    }

    /**
     * Registers the operation's primary key with this object.
     *
     * @param array $key The primary key values to be used when retrieving items.
     * @return AbstractKeyAwareOperation An implementation of this abstract.
     */
    public function setKey(array $key): AbstractKeyAwareOperation
    {
        $this->key = $this->marshaler->marshalItem($key);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'TableName' => $this->tableName,
            'ReturnConsumedCapacity' => $this->returnConsumedCapacity,
            'Key' => $this->key
        ];
    }
}
