<?php

namespace GuillermoandraeTest\DynamoDb\Factory;

use Aws\CommandInterface;
use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Exception\Exception;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;
use PHPUnit\Framework\TestCase;

final class ExceptionFactoryTest extends TestCase
{
    public function testReflectionException()
    {
        $command = $this->getMockForAbstractClass(CommandInterface::class);
        $dynamoDbException = new DynamoDbException('test', $command);
        $exception = ExceptionFactory::factory($dynamoDbException);
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
