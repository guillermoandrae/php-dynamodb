<?php

namespace GuillermoandraeTest\DynamoDb\Operation;

use Guillermoandrae\DynamoDb\Constant\Operators;
use Guillermoandrae\DynamoDb\Constant\Select;
use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;
use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
use Guillermoandrae\DynamoDb\Operation\QueryOperation;
use PHPUnit\Framework\TestCase;

final class QueryOperationTest extends TestCase
{
    /**
     * @var QueryOperation The operation.
     */
    private $operation;

    public function testSetLimit()
    {
        $expectedLimit = 50;
        $this->operation->setLimit($expectedLimit);
        $this->assertEquals($expectedLimit, $this->operation->toArray()['Limit']);
    }

    public function testFilterExpressionGT()
    {
        $expectedExpression = 'width > :width';
        $this->operation->setFilterExpression([
            'width' => [
                'operator' => Operators::GT,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->operation->toArray()['FilterExpression']);
    }

    public function testFilterExpressionGTE()
    {
        $expectedExpression = 'width >= :width';
        $this->operation->setFilterExpression([
            'width' => [
                'operator' => Operators::GTE,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->operation->toArray()['FilterExpression']);
    }

    public function testFilterExpressionLT()
    {
        $expectedExpression = 'width < :width';
        $this->operation->setFilterExpression([
            'width' => [
                'operator' => Operators::LT,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->operation->toArray()['FilterExpression']);
    }

    public function testFilterExpressionLTE()
    {
        $expectedExpression = 'width <= :width';
        $this->operation->setFilterExpression([
            'width' => [
                'operator' => Operators::LTE,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->operation->toArray()['FilterExpression']);
    }

    public function testFilterExpressionBadOperator()
    {
        $this->expectException(\ErrorException::class);
        $this->operation->setFilterExpression([
            'width' => [
                'operator' => 'TEST',
                'value' => '10',
            ]
        ]);
    }

    public function testSetFilterExpressionAndExpressionAttributeValues()
    {
        $this->operation->setFilterExpression([
            'color' => [
                'operator' => Operators::EQ,
                'value' => 'black',
            ],
            'shape' => [
                'operator' => Operators::CONTAINS,
                'value' => 'square'
            ],
            'width' => [
                'operator' => Operators::GTE,
                'value' => 10
            ]
        ]);
        $expectedFilterExpression = 'color = :color and contains(shape, :shape) and width >= :width';
        $expectedExpressionAttributeValues = [
            ':color' => [
                'S' => 'black'
            ],
            ':shape' => [
                'S' => 'square',
            ],
            ':width' => [
                'N' => 10
            ]
        ];
        $this->assertEquals($expectedFilterExpression, $this->operation->toArray()['FilterExpression']);
        $this->assertEquals(
            $expectedExpressionAttributeValues,
            $this->operation->toArray()['ExpressionAttributeValues']
        );
    }

    public function testSetReturnConsumedCapacity()
    {
        $expectedReturnConsumedCapacity = 50;
        $this->operation->setReturnConsumedCapacity($expectedReturnConsumedCapacity);
        $this->assertEquals($expectedReturnConsumedCapacity, $this->operation->toArray()['ReturnConsumedCapacity']);
    }

    public function testSetScanIndexForward()
    {
        $this->operation->setScanIndexForward(true);
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => true,
            'ConsistentRead' => false,
            'ReturnConsumedCapacity' => 'NONE'
        ];
        $this->assertEquals($expectedQuery, $this->operation->toArray());
    }

    public function testSetConsistentRead()
    {
        $this->operation->setConsistentRead(true);
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => false,
            'ConsistentRead' => true,
            'ReturnConsumedCapacity' => 'NONE'
        ];
        $this->assertEquals($expectedQuery, $this->operation->toArray());
    }

    public function testSetIndexName()
    {
        $this->operation->setIndexName('test');
        $this->assertEquals('test', $this->operation->toArray()['IndexName']);
    }

    public function testSetSelect()
    {
        $this->operation->setSelect(Select::ALL_ATTRIBUTES);
        $this->assertEquals('ALL_ATTRIBUTES', $this->operation->toArray()['Select']);
    }

    public function testSetProjectionExpression()
    {
        $this->operation->setProjectionExpression('test');
        $this->assertEquals('test', $this->operation->toArray()['ProjectionExpression']);
    }

    public function testSetPartitionKeyConditionExpression()
    {
        $this->operation->setPartitionKeyConditionExpression('test', 'something');
        $requestArray = $this->operation->toArray();
        $this->assertEquals('test = :test', $requestArray['KeyConditionExpression']);
        $this->assertEquals(['S' => 'something'], $requestArray['ExpressionAttributeValues'][':test']);
    }

    public function testSetSortKeyConditionExpression()
    {
        $this->operation->setPartitionKeyConditionExpression('test', 'something');
        $this->operation->setSortKeyConditionExpression('anotherTest', Operators::BEGINS_WITH, 'somethingElse');
        $requestArray = $this->operation->toArray();
        $this->assertEquals(
            'test = :test AND begins_with(anotherTest, :anotherTest)',
            $requestArray['KeyConditionExpression']
        );
        $this->assertEquals(['S' => 'something'], $requestArray['ExpressionAttributeValues'][':test']);
        $this->assertEquals(['S' => 'somethingElse'], $requestArray['ExpressionAttributeValues'][':anotherTest']);
    }

    protected function setUp(): void
    {
        $this->operation = new QueryOperation(DynamoDbClientFactory::factory(), MarshalerFactory::factory(), 'test');
    }

    protected function tearDown(): void
    {
        $this->operation = null;
    }
}
