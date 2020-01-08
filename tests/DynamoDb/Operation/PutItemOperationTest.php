<?php

namespace GuillermoandraeTest\DynamoDb\Operation;

use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;
use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
use Guillermoandrae\DynamoDb\Operation\PutItemOperation;
use PHPUnit\Framework\TestCase;

final class PutItemOperationTest extends TestCase
{
    public function testToArray()
    {
        $data = ['black' => 'man'];
        $request = new PutItemOperation(DynamoDbClientFactory::factory(), MarshalerFactory::factory(), 'test', $data);
        $expectedItem = [
            'TableName' => 'test',
            'Item' => [
                'black' => [
                    'S' => $data['black']
                ]
            ]
        ];
        $this->assertSame($expectedItem, $request->toArray());
    }
}
