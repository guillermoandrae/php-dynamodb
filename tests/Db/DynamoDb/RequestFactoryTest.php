<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use PHPUnit\Framework\TestCase;
use Guillermoandrae\Fisher\Db\DbException;
use Guillermoandrae\Fisher\Db\DynamoDb\RequestFactory;

final class RequestFactoryTest extends TestCase
{
    public function testBadRequest()
    {
        $this->expectException(DbException::class);
        RequestFactory::factory('test', []);
    }
}
