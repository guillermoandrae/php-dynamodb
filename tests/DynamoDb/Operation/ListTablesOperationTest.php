<?php

namespace GuillermoandraeTest\DynamoDb\Operation;

use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;
use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
use Guillermoandrae\DynamoDb\Operation\ListTablesOperation;
use PHPUnit\Framework\TestCase;

final class ListTablesOperationTest extends TestCase
{
    public function testSetLastEvaluatedTableName()
    {
        $request = new ListTablesOperation(DynamoDbClientFactory::factory(), MarshalerFactory::factory());
        $request->setLastEvaluatedTableName('test');
        $expectedQuery = [
            'LastEvaluatedTableName' => 'test',
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }

    public function testSetLimit()
    {
        $request = new ListTablesOperation(DynamoDbClientFactory::factory(), MarshalerFactory::factory());
        $request->setLimit(5);
        $expectedQuery = [
            'Limit' => 5,
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }
}
