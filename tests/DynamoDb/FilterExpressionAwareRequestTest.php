<?php

namespace GuillermoandraeTest\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\AbstractFilterExpressionAwareRequest;
use Guillermoandrae\DynamoDb\RequestOperators;
use PHPUnit\Framework\TestCase;

final class FilterExpressionAwareRequestTest extends TestCase
{
    /**
     * @var AbstractFilterExpressionAwareRequest The request.
     */
    private $request;

    public function testFilterExpressionGT()
    {
        $expectedExpression = 'width > :width';
        $this->request->setFilterExpression([
            'width' => [
                'operator' => RequestOperators::GT,
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
                'operator' => RequestOperators::GTE,
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
                'operator' => RequestOperators::LT,
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
                'operator' => RequestOperators::LTE,
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
                'operator' => RequestOperators::EQ,
                'value' => 'black',
            ],
            'shape' => [
                'operator' => RequestOperators::CONTAINS,
                'value' => 'square'
            ],
            'width' => [
                'operator' => RequestOperators::GTE,
                'value' => 10
            ]
        ]);
        $expectedQuery = [
            'TableName' => 'test',
            'ReturnConsumedCapacity' => 'NONE',
            'FilterExpression' => 'color = :color and contains(shape, :shape) and width >= :width',
            'ExpressionAttributeValues' => [
                ':color' => [
                    'S' => 'black'
                ],
                ':shape' => [
                    'S' => 'square',
                ],
                ':width' => [
                    'N' => 10
                ]
            ],
        ];
        $this->assertEquals($expectedQuery, $this->request->toArray());
    }
    
    public function testSetReturnConsumedCapacity()
    {
        $this->request->setReturnConsumedCapacity('INDEXES');
        $expectedQuery = [
            'TableName' => 'test',
            'ReturnConsumedCapacity' => 'INDEXES'
        ];
        $this->assertEquals($expectedQuery, $this->request->toArray());
    }

    public function testSetLimit()
    {
        $this->request->setLimit(2);
        $expectedQuery = [
            'TableName' => 'test',
            'ReturnConsumedCapacity' => 'NONE',
            'Limit' => 2
        ];
        $this->assertEquals($expectedQuery, $this->request->toArray());
    }

    protected function setUp(): void
    {
        $this->request = $this->getMockForAbstractClass(
            AbstractFilterExpressionAwareRequest::class,
            [new Marshaler(), 'test']
        );
    }

    protected function tearDown(): void
    {
        $this->request = null;
    }
}
