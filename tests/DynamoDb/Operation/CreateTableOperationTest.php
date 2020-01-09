<?php

namespace GuillermoandraeTest\DynamoDb\Operation;

use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\BillingModes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;
use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
use Guillermoandrae\DynamoDb\Operation\CreateTableOperation;
use GuillermoandraeTest\DynamoDb\TestCase;

final class CreateTableOperationTest extends TestCase
{
    private $tableName = 'test';

    private $data = ['name' => ['attributeType' => AttributeTypes::STRING, 'keyType' => KeyTypes::HASH]];

    public function testSetPartitionKey()
    {
        $request = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            'test'
        );
        $request->setPartitionKey('test', AttributeTypes::STRING);
        $this->assertEquals(KeyTypes::HASH, $request->toArray()['KeySchema'][0]['KeyType']);
    }

    public function testSetSortKey()
    {
        $request = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            'test'
        );
        $request->setSortKey('test', AttributeTypes::STRING);
        $this->assertEquals(KeyTypes::RANGE, $request->toArray()['KeySchema'][0]['KeyType']);
    }

    public function testSetReadCapacityUnits()
    {
        $request = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            'test',
            $this->data
        );
        $request->setReadCapacityUnits(10);
        $this->assertEquals(10, $request->toArray()['ProvisionedThroughput']['ReadCapacityUnits']);
    }
    
    public function testWriteCapacityUnits()
    {
        $request = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            'test',
            $this->data
        );
        $request->setWriteCapacityUnits(20);
        $this->assertEquals(20, $request->toArray()['ProvisionedThroughput']['WriteCapacityUnits']);
    }

    public function testSetBillingMode()
    {
        $request = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            'test',
            $this->data
        );
        $request->setBillingMode(BillingModes::PAY_PER_REQUEST);
        $this->assertEquals(BillingModes::PAY_PER_REQUEST, $request->toArray()['BillingMode']);
    }

    public function testSSESpecification()
    {
        $request = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            'test',
            $this->data
        );
        $request->setSSESpecification(true, 'someKey');
        $this->assertEquals('someKey', $request->toArray()['SSESpecification']['KMSMasterKeyId']);
        $request->setSSESpecification(false);
        $this->assertArrayNotHasKey('SSESpecification', $request->toArray());
    }

    public function testAddTag()
    {
        $request = new CreateTableOperation(
            DynamoDbClientFactory::factory(),
            MarshalerFactory::factory(),
            'test',
            $this->data
        );
        $request->addTag('someKey', 'someValue');
        $request->addTag('anotherKey', 'anotherValue');
        $this->assertEquals('someValue', $request->toArray()['Tags'][0]['Value']);
        $this->assertEquals('anotherValue', $request->toArray()['Tags'][1]['Value']);
    }
}
