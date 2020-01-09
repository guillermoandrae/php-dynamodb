<?php

namespace GuillermoandraeTest\DynamoDb\Operation;

use Guillermoandrae\DynamoDb\Constant\Operators;
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

    public function testSetReturnConsumedCapacity()
    {
        $expectedReturnConsumedCapacity = 50;
        $this->request->setReturnConsumedCapacity($expectedReturnConsumedCapacity);
        $this->assertEquals($expectedReturnConsumedCapacity, $this->request->toArray()['ReturnConsumedCapacity']);
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
