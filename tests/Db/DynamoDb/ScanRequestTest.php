<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use PHPUnit\Framework\TestCase;
use Guillermoandrae\Fisher\Db\DynamoDb\ScanRequest;

final class ScanRequestTest extends TestCase
{

    public function testSetScanIndexForward()
    {
        $request = new ScanRequest(new Marshaler(), 'test');
        $request->setScanIndexForward(true);
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => true,
            'ConsistentRead' => false,
            'ReturnConsumedCapacity' => 'NONE'
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }

    public function testSetConsistentRead()
    {
        $request = new ScanRequest(new Marshaler(), 'test');
        $request->setConsistentRead(true);
        $expectedQuery = [
            'TableName' => 'test',
            'ScanIndexForward' => false,
            'ConsistentRead' => true,
            'ReturnConsumedCapacity' => 'NONE'
        ];
        $this->assertEquals($expectedQuery, $request->toArray());
    }
}
