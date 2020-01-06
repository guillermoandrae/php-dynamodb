<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Db\DynamoDb\AbstractSearchRequest;
use PHPUnit\Framework\TestCase;

final class SearchRequestTest extends TestCase
{
    /**
     * @var AbstractSearchRequest The request.
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
        $this->request->setSelect(AbstractSearchRequest::SELECT_ALL_ATTRIBUTES);
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
            AbstractSearchRequest::class,
            [new Marshaler(), 'test']
        );
    }

    protected function tearDown(): void
    {
        $this->request = null;
    }
}
