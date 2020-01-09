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
     * @var QueryOperation The request.
     */
    private $request;

    public function testSetLimit()
    {
        $expectedLimit = 50;
        $this->request->setLimit($expectedLimit);
        $this->assertEquals($expectedLimit, $this->request->toArray()['Limit']);
    }

    public function testFilterExpressionGT()
    {
        $expectedExpression = 'width > :width';
        $this->request->setFilterExpression([
            'width' => [
                'operator' => Operators::GT,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->request->toArray()['FilterExpression']);
    }

    public function testFilterExpressionGTE()
    {
        $expectedExpression = 'width >= :width';
        $this->request->setFilterExpression([
            'width' => [
                'operator' => Operators::GTE,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->request->toArray()['FilterExpression']);
    }

    public function testFilterExpressionLT()
    {
        $expectedExpression = 'width < :width';
        $this->request->setFilterExpression([
            'width' => [
                'operator' => Operators::LT,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->request->toArray()['FilterExpression']);
    }

    public function testFilterExpressionLTE()
    {
        $expectedExpression = 'width <= :width';
        $this->request->setFilterExpression([
            'width' => [
                'operator' => Operators::LTE,
                'value' => '10',
            ]
        ]);
        $this->assertEquals($expectedExpression, $this->request->toArray()['FilterExpression']);
    }

    public function testFilterExpressionBadOperator()
    {
        $this->expectException(\ErrorException::class);
        $this->request->setFilterExpression([
            'width' => [
                'operator' => 'TEST',
                'value' => '10',
            ]
        ]);
    }

    public function testSetFilterExpressionAndExpressionAttributeValues()
    {
        $this->request->setFilterExpression([
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
        $this->assertEquals($expectedFilterExpression, $this->request->toArray()['FilterExpression']);
        $this->assertEquals($expectedExpressionAttributeValues, $this->request->toArray()['ExpressionAttributeValues']);
    }

    public function testSetReturnConsumedCapacity()
    {
        $expectedReturnConsumedCapacity = 50;
        $this->request->setReturnConsumedCapacity($expectedReturnConsumedCapacity);
        $this->assertEquals($expectedReturnConsumedCapacity, $this->request->toArray()['ReturnConsumedCapacity']);
    }

    public function testSetScanIndexForward()
    {
        $this->request->setScanIndexForward(true);
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => true,
            'ConsistentRead' => false,
            'ReturnConsumedCapacity' => 'NONE'
        ];
        $this->assertEquals($expectedQuery, $this->request->toArray());
    }

    public function testSetConsistentRead()
    {
        $this->request->setConsistentRead(true);
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => false,
            'ConsistentRead' => true,
            'ReturnConsumedCapacity' => 'NONE'
        ];
        $this->assertEquals($expectedQuery, $this->request->toArray());
    }

    public function testSetIndexName()
    {
        $this->request->setIndexName('test');
        $this->assertEquals('test', $this->request->toArray()['IndexName']);
    }

    public function testSetSelect()
    {
        $this->request->setSelect(Select::ALL_ATTRIBUTES);
        $this->assertEquals('ALL_ATTRIBUTES', $this->request->toArray()['Select']);
    }

    public function testSetProjectionExpression()
    {
        $this->request->setProjectionExpression('test');
        $this->assertEquals('test', $this->request->toArray()['ProjectionExpression']);
    }

    public function testSetPartitionKeyConditionExpression()
    {
        $this->request->setPartitionKeyConditionExpression('test', 'something');
        $requestArray = $this->request->toArray();
        $this->assertEquals('test = :test', $requestArray['KeyConditionExpression']);
        $this->assertEquals(['S' => 'something'], $requestArray['ExpressionAttributeValues'][':test']);
    }

    public function testSetSortKeyConditionExpression()
    {
        $this->request->setPartitionKeyConditionExpression('test', 'something');
        $this->request->setSortKeyConditionExpression('anotherTest', Operators::BEGINS_WITH, 'somethingElse');
        $requestArray = $this->request->toArray();
        $this->assertEquals(
            'test = :test AND begins_with(anotherTest, :anotherTest)',
            $requestArray['KeyConditionExpression']
        );
        $this->assertEquals(['S' => 'something'], $requestArray['ExpressionAttributeValues'][':test']);
        $this->assertEquals(['S' => 'somethingElse'], $requestArray['ExpressionAttributeValues'][':anotherTest']);
    }

    protected function setUp(): void
    {
        $this->request = new QueryOperation(DynamoDbClientFactory::factory(), MarshalerFactory::factory(), 'test');
    }

    protected function tearDown(): void
    {
        $this->request = null;
    }
}
