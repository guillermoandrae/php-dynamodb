<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

abstract class AbstractKeyAwareRequest extends AbstractItemRequest
{
    /**
     * @var array The primary key values to be used when retrieving items.
     */
    protected $key;
    
    /**
     * Registers the JSON Marshaler, table name, and primary key with this object.
     *
     * @param Marshaler $marshaler The JSON Marshaler.
     * @param string $tableName The table name.
     * @param array $key The primary key values to be used when retrieving items.
     */
    public function __construct(Marshaler $marshaler, string $tableName, array $key)
    {
        parent::__construct($marshaler, $tableName);
        $this->setKey($key);
    }

    /**
     * Registers the operation's primary key with this object.
     *
     * @param array $key The primary key values to be used when retrieving items.
     * @return AbstractKeyAwareRequest An implementation of this abstract.
     */
    public function setKey(array $key): AbstractKeyAwareRequest
    {
        $this->key = $this->marshaler->marshalItem($key);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        return [
            'TableName' => $this->tableName,
            'ReturnConsumedCapacity' => $this->returnConsumedCapacity,
            'Key' => $this->key
        ];
    }
}
