<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Db\DynamoDb\CreateTableRequest;
use Guillermoandrae\Db\DynamoDb\KeyTypes;
use PHPUnit\Framework\TestCase;

final class CreateTableRequestTest extends TestCase
{
    private $data = ['name' => ['type' => 'S', 'keyType' => KeyTypes::HASH]];
    
    public function testSetKeySchema()
    {
        $request = new CreateTableRequest(new Marshaler(), 'test', $this->data);
        $expectedQuery = [
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'name',
                    'AttributeType' => 'S',
                ],
            ],
            'KeySchema' => [
                [
                    'AttributeName' => 'name',
                    'KeyType' => KeyTypes::HASH,
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 5,
                'WriteCapacityUnits' => 5,
            ],
            'TableName' => 'test',
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }

    public function testSetReadCapacityUnits()
    {
        $request = new CreateTableRequest(new Marshaler(), 'test', $this->data);
        $request->setReadCapacityUnits(10);
        $expectedQuery = [
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'name',
                    'AttributeType' => 'S',
                ],
            ],
            'KeySchema' => [
                [
                    'AttributeName' => 'name',
                    'KeyType' => KeyTypes::HASH,
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 5,
            ],
            'TableName' => 'test',
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }
    
    public function testWriteCapacityUnits()
    {
        $request = new CreateTableRequest(new Marshaler(), 'test', $this->data);
        $request->setWriteCapacityUnits(20);
        $expectedQuery = [
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'name',
                    'AttributeType' => 'S',
                ],
            ],
            'KeySchema' => [
                [
                    'AttributeName' => 'name',
                    'KeyType' => KeyTypes::HASH,
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 5,
                'WriteCapacityUnits' => 20,
            ],
            'TableName' => 'test',
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }
}
