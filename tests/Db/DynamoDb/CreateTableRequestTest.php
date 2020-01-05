<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Db\DynamoDb\AttributeTypes;
use Guillermoandrae\Db\DynamoDb\BillingModes;
use Guillermoandrae\Db\DynamoDb\CreateTableRequest;
use Guillermoandrae\Db\DynamoDb\KeyTypes;
use PHPUnit\Framework\TestCase;

final class CreateTableRequestTest extends TestCase
{
    private $data = ['name' => ['attributeType' => AttributeTypes::STRING, 'keyType' => KeyTypes::HASH]];

    public function testSetPartitionKey()
    {
        $request = new CreateTableRequest(new Marshaler(), 'test');
        $request->setPartitionKey('test', AttributeTypes::STRING);
        $this->assertEquals(KeyTypes::HASH, $request->toArray()['KeySchema'][0]['KeyType']);
    }

    public function testSetSortKey()
    {
        $request = new CreateTableRequest(new Marshaler(), 'test');
        $request->setSortKey('test', AttributeTypes::STRING);
        $this->assertEquals(KeyTypes::RANGE, $request->toArray()['KeySchema'][0]['KeyType']);
    }

    public function testSetReadCapacityUnits()
    {
        $request = new CreateTableRequest(new Marshaler(), 'test', $this->data);
        $request->setReadCapacityUnits(10);
        $this->assertEquals(10, $request->toArray()['ProvisionedThroughput']['ReadCapacityUnits']);
    }
    
    public function testWriteCapacityUnits()
    {
        $request = new CreateTableRequest(new Marshaler(), 'test', $this->data);
        $request->setWriteCapacityUnits(20);
        $this->assertEquals(20, $request->toArray()['ProvisionedThroughput']['WriteCapacityUnits']);
    }

    public function testSetBillingMode()
    {
        $request = new CreateTableRequest(new Marshaler(), 'test', $this->data);
        $request->setBillingMode(BillingModes::PAY_PER_REQUEST);
        $this->assertEquals(BillingModes::PAY_PER_REQUEST, $request->toArray()['BillingMode']);
    }

    public function testSSESpecification()
    {
        $request = new CreateTableRequest(new Marshaler(), 'test', $this->data);
        $request->setSSESpecification(true, 'someKey');
        $this->assertEquals('someKey', $request->toArray()['SSESpecification']['KMSMasterKeyId']);
    }
}
