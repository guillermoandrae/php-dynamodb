<?php

namespace GuillermoandraeTest\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\ListTablesRequest;
use PHPUnit\Framework\TestCase;

final class ListTablesRequestTest extends TestCase
{
    public function testSetLastEvaluatedTableName()
    {
        $request = new ListTablesRequest(new Marshaler());
        $request->setLastEvaluatedTableName('test');
        $expectedQuery = [
            'LastEvaluatedTableName' => 'test',
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }

    public function testSetLimit()
    {
        $request = new ListTablesRequest(new Marshaler());
        $request->setLimit(5);
        $expectedQuery = [
            'Limit' => 5,
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }
}
