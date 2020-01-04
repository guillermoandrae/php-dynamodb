<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use PHPUnit\Framework\TestCase;
use Guillermoandrae\Fisher\Db\DynamoDb\PutItemRequest;

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
