<?php

namespace GuillermoandraeTest;

use Guillermoandrae\HelloWorld;
use PHPUnit\Framework\TestCase;

final class HelloWorldTest extends TestCase
{
    public function testHello()
    {
        $this->assertSame('Hello, World!', (new HelloWorld())->hello());
    }
}
