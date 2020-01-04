<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use PHPUnit\Framework\TestCase;
use Guillermoandrae\Fisher\Db\DynamoDb\ListTablesRequest;

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
