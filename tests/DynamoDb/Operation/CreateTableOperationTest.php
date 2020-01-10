<?php

namespace GuillermoandraeTest\DynamoDb\Operation;

use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\BillingModes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\Constant\ProjectionTypes;
use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;
use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
use Guillermoandrae\DynamoDb\Operation\CreateTableOperation;
use GuillermoandraeTest\DynamoDb\TestCase;

final class CreateTableOperationTest extends TestCase
{
    /**
     * @var string The table name;
     */
    private $tableName = 'test';

    /**
     * @var array The primary key.
     */
    private $keySchema = ['name' => [AttributeTypes::STRING, KeyTypes::HASH]];

    public function testSetPartitionKey()
    {
        $operation = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            $this->tableName
        );
        $operation->setPartitionKey('test', AttributeTypes::STRING);
        $this->assertEquals(KeyTypes::HASH, $operation->toArray()['KeySchema'][0]['KeyType']);
    }

    public function testSetSortKey()
    {
        $operation = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            $this->tableName
        );
        $operation->setSortKey('test', AttributeTypes::STRING);
        $this->assertEquals(KeyTypes::RANGE, $operation->toArray()['KeySchema'][0]['KeyType']);
    }

    public function testSetReadCapacityUnits()
    {
        $operation = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            $this->tableName
        );
        $operation->setReadCapacityUnits(10);
        $this->assertEquals(10, $operation->toArray()['ProvisionedThroughput']['ReadCapacityUnits']);
    }
    
    public function testWriteCapacityUnits()
    {
        $operation = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            $this->tableName
        );
        $operation->setWriteCapacityUnits(20);
        $this->assertEquals(20, $operation->toArray()['ProvisionedThroughput']['WriteCapacityUnits']);
    }

    public function testSetBillingMode()
    {
        $operation = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            $this->tableName
        );
        $operation->setBillingMode(BillingModes::PAY_PER_REQUEST);
        $this->assertEquals(BillingModes::PAY_PER_REQUEST, $operation->toArray()['BillingMode']);
    }

    public function testSSESpecification()
    {
        $operation = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            $this->tableName
        );
        $operation->setSSESpecification(true, 'someKey');
        $this->assertEquals('someKey', $operation->toArray()['SSESpecification']['KMSMasterKeyId']);
        $operation->setSSESpecification(false);
        $this->assertArrayNotHasKey('SSESpecification', $operation->toArray());
    }

    public function testAddGlobalSecondaryIndex()
    {
        $operation = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            $this->tableName
        );
        $expectedIndexName = 'test';
        $expectedKeySchema = [['class', KeyTypes::HASH]];
        $expectedProjection = ['thing', ProjectionTypes::INCLUDE];
        $operation->addGlobalSecondaryIndex($expectedIndexName, $expectedKeySchema, $expectedProjection);
        $request = $operation->toArray()['GlobalSecondaryIndexes'][0];
        $this->assertEquals($expectedIndexName, $request['IndexName']);
        $this->assertEquals($expectedKeySchema[0][0], $request['KeySchema'][0]['AttributeName']);
        $this->assertEquals($expectedKeySchema[0][1], $request['KeySchema'][0]['KeyType']);
        $this->assertEquals($expectedProjection[0], $request['Projection']['NonKeyAttributes']);
        $this->assertEquals($expectedProjection[1], $request['Projection']['ProjectionType']);
    }

    public function testSetGlobalSecondaryIndexWithProvisionedThroughput()
    {
        $operation = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            $this->tableName
        );
        $expectedIndexName = 'test';
        $expectedKeySchema = [['class', KeyTypes::HASH]];
        $expectedProjection = ['thing', ProjectionTypes::INCLUDE];
        $expectedProvisionedThroughput = [5, 5];
        $operation->addGlobalSecondaryIndex(
            $expectedIndexName,
            $expectedKeySchema,
            $expectedProjection,
            $expectedProvisionedThroughput
        );
        $this->assertEquals(
            $expectedProvisionedThroughput[0],
            $operation->toArray()['GlobalSecondaryIndexes'][0]['ProvisionedThroughput']['ReadCapacityUnits']
        );
        $this->assertEquals(
            $expectedProvisionedThroughput[1],
            $operation->toArray()['GlobalSecondaryIndexes'][0]['ProvisionedThroughput']['WriteCapacityUnits']
        );
    }

    public function testAddTag()
    {
        $operation = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            $this->tableName,
            $this->keySchema
        );
        $operation->addTag('someKey', 'someValue');
        $operation->addTag('anotherKey', 'anotherValue');
        $this->assertEquals('someValue', $operation->toArray()['Tags'][0]['Value']);
        $this->assertEquals('anotherValue', $operation->toArray()['Tags'][1]['Value']);
    }
}
