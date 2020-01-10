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
        $expectedLastEvaluatedTableName = 'test';
        $operation = new ListTablesOperation(DynamoDbClientFactory::factory(), MarshalerFactory::factory());
        $operation->setLastEvaluatedTableName($expectedLastEvaluatedTableName);
        $this->assertEquals($expectedLastEvaluatedTableName, $operation->toArray()['LastEvaluatedTableName']);
    }

    public function testSetLimit()
    {
        $expectedLimit = 5;
        $operation = new ListTablesOperation(DynamoDbClientFactory::factory(), MarshalerFactory::factory());
        $operation->setLimit($expectedLimit);
        $this->assertEquals($expectedLimit, $operation->toArray()['Limit']);
    }
}
