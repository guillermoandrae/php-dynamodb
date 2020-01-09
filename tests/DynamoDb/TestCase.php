<?php

namespace GuillermoandraeTest\DynamoDb;

use Guillermoandrae\DynamoDb\DynamoDbAdapter;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var DynamoDbAdapter The DynamoDb adapter.
     */
    protected $adapter;

    protected function setUp(): void
    {
        $this->adapter = new DynamoDbAdapter();
        $tables = $this->adapter->listTables();
        foreach ($tables as $table) {
            $this->adapter->useTable($table)->deleteTable();
        }
    }

    protected function tearDown(): void
    {
        $tables = $this->adapter->listTables();
        foreach ($tables as $table) {
            $this->adapter->useTable($table)->deleteTable();
        }
    }
}
