<?php

namespace GuillermoandraeTest\DynamoDb\Contract;

use Guillermoandrae\DynamoDb\Constant\Select;
use Guillermoandrae\DynamoDb\Contract\AbstractSearchOperation;
use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;
use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
use PHPUnit\Framework\TestCase;

final class SearchOperationTest extends TestCase
{
    /**
     * @var AbstractSearchOperation The request.
     */
    private $request;

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

    protected function setUp(): void
    {
        $this->request = $this->getMockForAbstractClass(
            AbstractSearchOperation::class,
            [DynamoDbClientFactory::factory(), MarshalerFactory::factory(), 'test']
        );
    }

    protected function tearDown(): void
    {
        $this->request = null;
    }
}
