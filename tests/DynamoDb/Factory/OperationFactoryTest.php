<?php

namespace GuillermoandraeTest\DynamoDb\Factory;

use Guillermoandrae\DynamoDb\Exception;
use Guillermoandrae\DynamoDb\Factory\OperationFactory;
use PHPUnit\Framework\TestCase;

final class OperationFactoryTest extends TestCase
{
    public function testBadRequest()
    {
        $this->expectException(Exception::class);
        OperationFactory::factory('test', []);
    }
}
