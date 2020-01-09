<?php

namespace GuillermoandraeTest\DynamoDb\Contract;

use Guillermoandrae\DynamoDb\Constant\Operators;
use Guillermoandrae\DynamoDb\Contract\FilterExpressionAwareOperationTrait;
use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
use PHPUnit\Framework\TestCase;

final class FilterExpressionAwareOperationTraitTest extends TestCase
{
    /**
     * @var FilterExpressionAwareOperationTrait The request.
     */
    private $request;

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
        $expectedQuery = [
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

    protected function setUp(): void
    {
        $this->request = $this->getMockForTrait(
            FilterExpressionAwareOperationTrait::class
        );
        $this->request->setMarshaler(MarshalerFactory::factory());
    }

    protected function tearDown(): void
    {
        $this->request = null;
    }
}
