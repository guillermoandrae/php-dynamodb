<?php

namespace GuillermoandraeTest\DynamoDb\Operation;

use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;
use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
use Guillermoandrae\DynamoDb\Operation\UpdateItemOperation;
use GuillermoandraeTest\DynamoDb\TestCase;

final class UpdateItemOperationTest extends TestCase
{
    public function testToArray()
    {
        $client = DynamoDbClientFactory::factory();
        $marshaler = MarshalerFactory::factory();
        $primaryKey = [
            'name' => [AttributeTypes::STRING, KeyTypes::HASH],
            'date' => [AttributeTypes::NUMBER, KeyTypes::RANGE],
        ];
        $updateData = [
            'something' => 'wicked',
            'this' => 'way comes'
        ];
        $operation = new UpdateItemOperation($client, $marshaler, 'test', $primaryKey, $updateData);
        $this->assertSame('SET something = :something and this = :this', $operation->toArray()['UpdateExpression']);
    }
}
