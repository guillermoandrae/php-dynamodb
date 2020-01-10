<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\Constant\BillingModes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\Contract\AbstractTableOperation;
use Guillermoandrae\DynamoDb\Exception\Exception;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

/**
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#createtable
 */
final class CreateTableOperation extends AbstractTableOperation
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
     * @var array The global secondary indexes.
     */
    private $globalSecondaryIndexes = [];

    /**
     * @var array The local secondary indexes.
     */
    private $localSecondaryIndexes = [];

    /**
     * @var array The tags.
     */
    private $tags = [];

    /**
     * CreateTableRequest constructor.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $keySchema OPTIONAL The key schema.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName, array $keySchema = [])
    {
        parent::__construct($client, $marshaler, $tableName);
        if (!empty($keySchema)) {
            $this->setKeySchema($keySchema);
        }
    }

    /**
     * Registers the key schema and attribute definitions.
     *
     * The key schema argument should be an associative array with the following keys:
     *
     * $keySchema = [
     *      'MyAttribute' => [ // this is the name of your attribute
     *          'S', // this must be one of the AttributeTypes constants
     *          'HASH' // this must be one of the KeyTypes constants
     *     ]
     * ];
     *
     * This method will use the information available in the provided array to build the 'KeySchema' and
     * 'AttributeDefinitions' arrays needed for table creation requests.
     *
     * @param array $keySchema The key schema.
     * @return CreateTableOperation This object.
     */
    public function setKeySchema(array $keySchema): CreateTableOperation
    {
        foreach ($keySchema as $name => $data) {
            $this->keySchema[] = [
                'AttributeName' => $name,
                'KeyType' => $data[1]
            ];
            $this->attributeDefinitions[] = [
                'AttributeName' => $name,
                'AttributeType' => $data[0]
            ];
        }
        return $this;
    }

    /**
     * Registers the partition key.
     *
     * @param string $name The name of the partition key.
     * @param string $attributeType The attribute type.
     * @return CreateTableOperation This object.
     */
    public function setPartitionKey(string $name, string $attributeType): CreateTableOperation
    {
        $this->setKeySchema([
            $name => [$attributeType, KeyTypes::HASH]
        ]);
        return $this;
    }

    /**
     * Registers the sort key.
     *
     * @param string $name The name of the sort key.
     * @param string $attributeType The attribute type.
     * @return CreateTableOperation This object.
     */
    public function setSortKey(string $name, string $attributeType): CreateTableOperation
    {
        $this->setKeySchema([
            $name => [$attributeType, KeyTypes::RANGE]
        ]);
        return $this;
    }

    /**
     * Registers the maximum number of strongly consistent reads consumed per second.
     *
     * @param integer $readCapacityUnits The maximum number of strongly consistent reads consumed per second.
     * @return CreateTableOperation This object.
     */
    public function setReadCapacityUnits(int $readCapacityUnits): CreateTableOperation
    {
        $this->readCapacityUnits = $readCapacityUnits;
        return $this;
    }

    /**
     * Registers the maximum number of writes consumed per second.
     *
     * @param integer $writeCapacityUnits The maximum number of writes consumed per second.
     * @return CreateTableOperation This object.
     */
    public function setWriteCapacityUnits(int $writeCapacityUnits): CreateTableOperation
    {
        $this->writeCapacityUnits = $writeCapacityUnits;
        return $this;
    }

    /**
     * Registers the billing mode.
     *
     * @param string $billingMode The billing mode.
     * @return CreateTableOperation This object.
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
     * @return CreateTableOperation This object.
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
     * Adds a global secondary index.
     *
     * @see CreateTableOperation::addSecondaryIndex()
     * @param string $indexName The index name.
     * @param array $keySchema The key schema.
     * @param array $projection The projection.
     * @param array|null $provisionedThroughput OPTIONAL The provisioned throughput.
     * @return CreateTableOperation This object.
     */
    public function addGlobalSecondaryIndex(
        string $indexName,
        array $keySchema,
        array $projection,
        ?array $provisionedThroughput = []
    ): CreateTableOperation {
        return $this->addSecondaryIndex('global', $indexName, $keySchema, $projection, $provisionedThroughput);
    }

    /**
     * Adds a local secondary index.
     *
     * @see CreateTableOperation::addSecondaryIndex()
     * @param string $indexName The index name.
     * @param array $keySchema The key schema.
     * @param array $projection The projection.
     * @return CreateTableOperation This object.
     */
    public function addLocalSecondaryIndex(
        string $indexName,
        array $keySchema,
        array $projection
    ): CreateTableOperation {
        return $this->addSecondaryIndex('local', $indexName, $keySchema, $projection);
    }

    /**
     * Registers a tag.
     *
     * @param string $key The tag key.
     * @param string $value The tag value.
     * @return CreateTableOperation This object.
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
    public function execute(): bool
    {
        try {
            $this->client->createTable($this->toArray());
            //$this->client->waitUntil('TableExists', ['TableName' => $this->toArray()['TableName']]);
            return true;
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $operation = parent::toArray();
        $operation['KeySchema'] = $this->keySchema;
        $operation['AttributeDefinitions'] = $this->attributeDefinitions;
        $operation['BillingMode'] = $this->billingMode;
        $operation['ProvisionedThroughput'] = [
            'ReadCapacityUnits' => $this->readCapacityUnits,
            'WriteCapacityUnits' => $this->writeCapacityUnits,
        ];
        if (!empty($this->sseSpecification)) {
            $operation['SSESpecification'] = $this->sseSpecification;
        }
        if (!empty($this->globalSecondaryIndexes)) {
            $operation['GlobalSecondaryIndexes'] = $this->globalSecondaryIndexes;
        }
        if (!empty($this->localSecondaryIndexes)) {
            $operation['LocalSecondaryIndexes'] = $this->localSecondaryIndexes;
        }
        if (!empty($this->tags)) {
            $operation['Tags'] = $this->tags;
        }
        return $operation;
    }

    /**
     * Adds a secondary index.
     *
     * @see CreateTableOperation::addSecondaryIndex()
     * @param string $indexType The index type.
     * @param string $indexName The index name.
     * @param array $keySchema The key schema.
     * @param array $projection The projection.
     * @param array|null $provisionedThroughput OPTIONAL The provisioned throughput.
     * @return CreateTableOperation This object.
     */
    private function addSecondaryIndex(
        string $indexType,
        string $indexName,
        array $keySchema,
        array $projection,
        ?array $provisionedThroughput = []
    ): CreateTableOperation {
        $index = [
            'IndexName' => $indexName,
            'KeySchema' => [],
            'Projection' => [
                'NonKeyAttributes' => $projection[0],
                'ProjectionType' => $projection[1]
            ]
        ];
        foreach ($keySchema as $key) {
            $index['KeySchema'][] = [
                'AttributeName' => $key[0],
                'KeyType' => $key[1]
            ];
        }
        switch ($indexType) {
            case 'local':
                $this->localSecondaryIndexes[] = $index;
                break;
            case 'global':
                if (!empty($provisionedThroughput)) {
                    $index['ProvisionedThroughput'] = [
                        'ReadCapacityUnits' => $provisionedThroughput[0],
                        'WriteCapacityUnits' => $provisionedThroughput[1],
                    ];
                }
                $this->globalSecondaryIndexes[] = $index;
                break;
        }
        return $this;
    }
}
