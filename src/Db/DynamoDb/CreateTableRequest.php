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
     * @var string The billing mode.
     */
    private $billingMode = BillingModes::PROVISIONED;

    /**
     * @var array The server-side encryption settings.
     */
    private $sseSpecification = [];

    /**
     * @var array The tags.
     */
    private $tags = [];

    /**
     * Registers the Marshaler, table name, and key schema with this object.
     *
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $keySchema OPTIONAL The key schema.
     */
    public function __construct(Marshaler $marshaler, string $tableName, array $keySchema = [])
    {
        parent::__construct($marshaler, $tableName);
        if (!empty($keySchema)) {
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
                'attributeType' => $attributeType,
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
                'attributeType' => $attributeType,
                'keyType' => KeyTypes::RANGE
            ]
        ]);
        return $this;
    }

    /**
     * Registers the key schema and attribute definitions.
     *
     * The key schema argument should be an associative array with the following keys:
     *
     * $keySchema = [
     *      'MyAttribute' => [ // this is the name of your attribute
     *          'attributeType' => 'S', // this can be one of the AttributeTypes constants
     *          'keyType' => 'HASH' // this can be either 'HASH' or 'RANGE' (or one of the KeyTypes constants)
     *     ]
     * ];
     *
     * This method will use the information available in the provided array to build the 'KeySchema' and
     * 'AttributeDefinitions' arrays needed for table creation requests.
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
                'AttributeType' => $data['attributeType']
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
     * Registers the billing mode.
     *
     * @param string $billingMode The billing mode.
     * @return CreateTableRequest This object.
     */
    public function setBillingMode(string $billingMode)
    {
        $this->billingMode = $billingMode;
        return $this;
    }

    /**
     * Registers the server-side encryption settings.
     *
     * @param bool $isEnabled Whether or not SSE is enabled.
     * @param string $masterKeyId OPTIONAL The ID of the master key.
     * @return CreateTableRequest This object.
     */
    public function setSSESpecification(bool $isEnabled, string $masterKeyId = '')
    {
        $sseSpecification = [];
        if ($isEnabled) {
            $sseSpecification = [
                'Enabled' => $isEnabled,
                'SSEType' => 'KMS'
            ];
            if (!empty($masterKeyId)) {
                $sseSpecification['KMSMasterKeyId'] = $masterKeyId;
            }
        }
        $this->sseSpecification = $sseSpecification;
        return $this;
    }

    /**
     * Registers a tag.
     *
     * @param string $key The tag key.
     * @param string $value The tag value.
     * @return CreateTableRequest This object.
     */
    public function addTag(string $key, string $value)
    {
        $this->tags[] = [
            'Key' => $key,
            'Value' => $value
        ];
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
        $query['BillingMode'] = $this->billingMode;
        $query['ProvisionedThroughput'] = [
            'ReadCapacityUnits' => $this->readCapacityUnits,
            'WriteCapacityUnits' => $this->writeCapacityUnits,
        ];
        if (!empty($this->sseSpecification)) {
            $query['SSESpecification'] = $this->sseSpecification;
        }
        if (!empty($this->tags)) {
            $query['Tags'] = $this->tags;
        }
        return $query;
    }
}
