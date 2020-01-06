<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Db\DynamoDb\QueryRequest;
use Guillermoandrae\Db\DynamoDb\RequestOperators;
use PHPUnit\Framework\TestCase;

final class QueryRequestTest extends TestCase
{
    /**
     * @var QueryRequest The request.
     */
    private $request;

    public function testSetPartitionKeyConditionExpression()
    {
        $this->request->setPartitionKeyConditionExpression('test', RequestOperators::GTE, 'something');
        $requestArray = $this->request->toArray();
        $this->assertEquals('test >= :test', $requestArray['KeyConditionExpression']);
        $this->assertEquals(['S' => 'something'], $requestArray['ExpressionAttributeValues'][':test']);
    }

    public function testSetSortKeyConditionExpression()
    {
        $this->request->setPartitionKeyConditionExpression('test', RequestOperators::GTE, 'something');
        $this->request->setSortKeyConditionExpression('anotherTest', RequestOperators::BEGINS_WITH, 'somethingElse');
        $requestArray = $this->request->toArray();
        $this->assertEquals(
            'test >= :test AND begins_with(anotherTest, :anotherTest)',
            $requestArray['KeyConditionExpression']
        );
        $this->assertEquals(['S' => 'something'], $requestArray['ExpressionAttributeValues'][':test']);
        $this->assertEquals(['S' => 'somethingElse'], $requestArray['ExpressionAttributeValues'][':anotherTest']);
    }

    protected function setUp(): void
    {
        $this->request = new QueryRequest(new Marshaler(), 'test');
    }

    protected function tearDown(): void
    {
        $this->request = null;
    }
}
