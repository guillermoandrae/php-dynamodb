<?php

namespace GuillermoandraeTest\DynamoDb;

use Guillermoandrae\DynamoDb\Exception;
use Guillermoandrae\DynamoDb\RequestFactory;
use PHPUnit\Framework\TestCase;

final class RequestFactoryTest extends TestCase
{
    public function testBadRequest()
    {
        $this->expectException(Exception::class);
        RequestFactory::factory('test', []);
    }
}
