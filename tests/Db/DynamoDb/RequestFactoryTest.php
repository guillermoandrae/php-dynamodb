<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use Guillermoandrae\Db\DbException;
use Guillermoandrae\Db\DynamoDb\RequestFactory;
use PHPUnit\Framework\TestCase;

final class RequestFactoryTest extends TestCase
{
    public function testBadRequest()
    {
        $this->expectException(DbException::class);
        RequestFactory::factory('test', []);
    }
}
