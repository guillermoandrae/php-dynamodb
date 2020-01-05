<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

final class CreateTableRequest extends AbstractTableAwareRequest
{
    /**
     * @var array Attributes describing the key schema.
     */
    private $attributeDefinitions = [];

    /**
     * @var array The primary key.
     */
    private $keySchema = [];
    
    /**
     * @var int The maximum number of strongly consistent reads consumed per second.
     */
    private $readCapacityUnits = 5;

    /**
     * @var int The maximum number of writes consumed per second.
     */
    private $writeCapacityUnits = 5;

    /**
     * Registers the JSON Marshaler, table name, and key schema with this object.
     *
     * @param Marshaler $marshaler The JSON Marshaler.
     * @param string $tableName The table name.
     * @param array $keySchema OPTIONAL The key schema.
     */
    public function __construct(Marshaler $marshaler, string $tableName, array $keySchema = [])
    {
        parent::__construct($marshaler, $tableName);
        if ($keySchema) {
            $this->setKeySchema($keySchema);
        }
    }

    /**
     * Registers the partition key.
     *
     * @param string $name The name of the partition key.
     * @param string $attributeType The attribute type.
     * @return CreateTableRequest This object.
     */
    public function setPartitionKey(string $name, string $attributeType): CreateTableRequest
    {
        $this->setKeySchema([
            $name => [
                'type' => $attributeType,
                'keyType' => KeyTypes::HASH
            ]
        ]);
        return $this;
    }

    /**
     * Registers the sort key.
     *
     * @param string $name The name of the sort key.
     * @param string $attributeType The attribute type.
     * @return CreateTableRequest This object.
     */
    public function setSortKey(string $name, string $attributeType): CreateTableRequest
    {
        $this->setKeySchema([
            $name => [
                'type' => $attributeType,
                'keyType' => KeyTypes::RANGE
            ]
        ]);
        return $this;
    }

    /**
     * Registers the key schema and attribute definitions.
     *
     * @param array $keySchema The key schema.
     * @return CreateTableRequest This object.
     */
    public function setKeySchema(array $keySchema): CreateTableRequest
    {
        foreach ($keySchema as $name => $data) {
            $this->keySchema[] = [
                'AttributeName' => $name,
                'KeyType' => $data['keyType']
            ];
            $this->attributeDefinitions[] = [
                'AttributeName' => $name,
                'AttributeType' => $data['type']
            ];
        }
        return $this;
    }

    /**
     * Registers the maximum number of strongly consistent reads consumed per second.
     *
     * @param integer $readCapacityUnits The maximum number of strongly consistent reads consumed per second.
     * @return CreateTableRequest This object.
     */
    public function setReadCapacityUnits(int $readCapacityUnits): CreateTableRequest
    {
        $this->readCapacityUnits = $readCapacityUnits;
        return $this;
    }

    /**
     * Registers the maximum number of writes consumed per second.
     *
     * @param integer $writeCapacityUnits The maximum number of writes consumed per second.
     * @return CreateTableRequest This object.
     */
    public function setWriteCapacityUnits(int $writeCapacityUnits): CreateTableRequest
    {
        $this->writeCapacityUnits = $writeCapacityUnits;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        $query = parent::get();
        $query['KeySchema'] = $this->keySchema;
        $query['AttributeDefinitions'] = $this->attributeDefinitions;
        $query['ProvisionedThroughput'] = [
            'ReadCapacityUnits' => $this->readCapacityUnits,
            'WriteCapacityUnits' => $this->writeCapacityUnits,
        ];
        return $query;
    }
}
