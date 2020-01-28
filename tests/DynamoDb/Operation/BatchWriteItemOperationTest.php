<?php

namespace GuillermoandraeTest\DynamoDb\Operation;

use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\Exception\Exception;
use Guillermoandrae\DynamoDb\Operation\BatchWriteItemOperation;
use GuillermoandraeTest\DynamoDb\TestCase;

final class BatchWriteItemOperationTest extends TestCase
{
    public function testBadBatchWrite()
    {
        $this->expectException(Exception::class);
        $operation = new BatchWriteItemOperation(
            $this->adapter->getClient(),
            $this->adapter->getMarshaler()
        );
        $operation->setDeleteRequest([])->execute();
    }

    public function testBatchInsert()
    {
        $timestamp1 = time();
        $timestamp2 = strtotime('tomorrow');
        $operation = new BatchWriteItemOperation(
            $this->adapter->getClient(),
            $this->adapter->getMarshaler(),
            [],
            [
                'test' => [
                    ['name' => 'me', 'date' => $timestamp1, 'age' => 20],
                    ['name' => 'us', 'date' => $timestamp2, 'age' => 22],
                    ['name' => 'me', 'date' => $timestamp2, 'age' => 24]
                ]
            ]
        );
        $request = $operation->toArray();
        $this->assertEquals('me', $request['RequestItems']['test'][0]['PutRequest']['Item']['name']['S']);
        $operation->execute();

        $items = $this->adapter->find([
            'test' => [
                ['name' => 'me', 'date' => $timestamp1],
                ['name' => 'us', 'date' => $timestamp2],
            ]
        ]);
        $this->assertEquals('us', $items[1]['name']);
        $this->assertNotEquals($items[0]['name'], $items[1]['name']);
    }

    public function testBatchDelete()
    {
        $timestamp1 = time();
        $timestamp2 = strtotime('tomorrow');
        $this->adapter->useTable('test')->insert(['name' => 'me', 'date' => $timestamp1, 'age' => 20]);
        $this->adapter->useTable('test')->insert(['name' => 'us', 'date' => $timestamp2, 'age' => 22]);
        $this->adapter->useTable('test')->insert(['name' => 'me', 'date' => $timestamp2, 'age' => 24]);

        $operation = new BatchWriteItemOperation(
            $this->adapter->getClient(),
            $this->adapter->getMarshaler(),
            ['test' => [
                ['name' => 'me', 'date' => $timestamp1],
                ['name' => 'me', 'date' => $timestamp2]
            ]]
        );
        $operation->execute();

        $items = $this->adapter->useTable('test')->findAll();
        $this->assertCount(1, $items);
        $this->assertEquals(22, $items[0]['age']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $adapter = $this->adapter->useTable('test');
        $adapter->createTable([
            'name' => [AttributeTypes::STRING, KeyTypes::HASH],
            'date' => [AttributeTypes::NUMBER, KeyTypes::RANGE],
        ]);
    }
}
