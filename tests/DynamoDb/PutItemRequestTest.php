<?php

namespace GuillermoandraeTest\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\PutItemRequest;
use PHPUnit\Framework\TestCase;

final class PutItemRequestTest extends TestCase
{
    public function testToArray()
    {
        $data = ['black' => 'man'];
        $request = new PutItemRequest(new Marshaler(), 'test', $data);
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