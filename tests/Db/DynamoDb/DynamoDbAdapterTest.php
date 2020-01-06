<?php

namespace GuillermoandraeTest\Db\DynamoDb;

use Aws\DynamoDb\DynamoDbClient;
use Guillermoandrae\Db\DbException;
use Guillermoandrae\Db\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\Db\DynamoDb\RequestOperators;
use PHPUnit\Framework\TestCase;

final class DynamoDbAdapterTest extends TestCase
{
    /**
     * @var DynamoDbAdapter
     */
    private $adapter;

    public function testCreateDeleteListTable()
    {
        $this->adapter->useTable('widgets')->createTable([
            'name' => ['attributeType' => 'S', 'keyType' => 'HASH'],
            'date' => ['attributeType' => 'N', 'keyType' => 'RANGE'],
        ]);
        $this->assertTrue($this->adapter->useTable('widgets')->tableExists());
        $this->adapter->useTable('widgets')->deleteTable();
        $this->assertFalse($this->adapter->useTable('widgets')->tableExists());
    }

    public function testTableExists()
    {
        $this->adapter->useTable('widgets')->createTable([
            'name' => ['attributeType' => 'S', 'keyType' => 'HASH'],
            'date' => ['attributeType' => 'N', 'keyType' => 'RANGE'],
        ]);
        $this->assertFalse($this->adapter->tableExists('nonexistent'));
        $this->assertTrue($this->adapter->useTable('nonexistent')->tableExists('widgets'));
        $this->adapter->useTable('widgets')->deleteTable();
        $this->assertFalse($this->adapter->tableExists('widgets'));
    }
    
    public function testBadCreateTable()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('te\st')->createTable(['name' => ['attributeType' => 'S', 'keyType' => 'HASH']]);
    }

    public function testBadCreateTableBadKeySchema()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->createTable([]);
    }

    public function testBadDeleteTable()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->deleteTable();
    }

    public function testDescribeTable()
    {
        $this->adapter->useTable('test')->createTable(['name' => ['attributeType' => 'S', 'keyType' => 'HASH']]);
        $results = $this->adapter->useTable('test')->describeTable();
        $this->assertSame(5, $results['ProvisionedThroughput']['ReadCapacityUnits']);
        $this->adapter->useTable('test')->deleteTable();
    }

    public function testBadDescribeTable()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('juniper')->describeTable();
    }

    public function testBadFindAll()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->findAll();
    }

    public function testFindAll()
    {
        $adapter = $this->adapter->useTable('test');
        $adapter->createTable([
            'name' => ['attributeType' => 'S', 'keyType' => 'HASH'],
            'date' => ['attributeType' => 'N', 'keyType' => 'RANGE'],
        ]);
        $adapter->insert(['name' => 'Guillermo', 'date' => time()]);
        $adapter->insert(['name' => 'Fisher', 'date' => time()]);
        $items = $adapter->findAll();
        $adapter->deleteTable();
        $this->assertCount(2, $items);
    }

    public function testBadFindWhere()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->findWhere([]);
    }

    public function testFindWhere()
    {
        $adapter = $this->adapter->useTable('test');
        $adapter->createTable([
            'firstName' => ['attributeType' => 'S', 'keyType' => 'HASH'],
            'age' => ['attributeType' => 'N', 'keyType' => 'RANGE'],
        ]);
        $adapter->insert(['firstName' => 'Guillermo', 'hobby' => 'sleeping', 'age' => 40]);
        $adapter->insert(['firstName' => 'Guillermo', 'hobby' => 'coding', 'age' => 40]);
        $adapter->insert(['firstName' => 'William', 'hobby' => 'drawing', 'age' => 24]);
        $adapter->insert(['firstName' => 'William', 'hobby' => 'playing', 'age' => 15]);
        $adapter->insert(['firstName' => 'William', 'hobby' => 'writing', 'age' => 20]);

        $items = $adapter->findWhere([
            'partition' => [
                'name' => 'firstName',
                'value' => 'William'
            ],
            'sort' => [
                'name' => 'age',
                'operator' => RequestOperators::GTE,
                'value' => 16
            ]
        ]);
        $adapter->deleteTable();
        $this->assertCount(2, $items);
    }

    public function testBadFindLatest()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->findLatest();
    }

    public function testFindLatest()
    {
        $adapter = $this->adapter->useTable('test');
        $adapter->createTable([
            'name' => ['attributeType' => 'S', 'keyType' => 'HASH'],
            'date' => ['attributeType' => 'N', 'keyType' => 'RANGE'],
        ]);
        $adapter->insert(['name' => 'Guillermo', 'date' => time()]);
        $adapter->insert(['name' => 'Fisher', 'date' => time()]);
        $item = $adapter->findLatest();
        $adapter->deleteTable();
        $this->assertSame($item['name'], 'Fisher');
    }

    public function testBadFindById()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->findById([]);
    }

    public function testFindById()
    {
        $adapter = $this->adapter->useTable('test');
        $adapter->createTable([
            'name' => ['attributeType' => 'S', 'keyType' => 'HASH'],
            'date' => ['attributeType' => 'N', 'keyType' => 'RANGE'],
        ]);
        $key = ['name' => 'Guillermo', 'date' => time()];
        $adapter->insert(array_merge(['name' => 'Guillermo', 'date' => time(), 'lastName' => 'Fisher']));
        $item = $adapter->findById($key);
        $this->assertSame($item['lastName'], 'Fisher');
        $adapter->deleteTable();
    }

    public function testBadInsert()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->insert([]);
    }

    public function testBadDelete()
    {
        $this->expectException(DbException::class);
        $this->adapter->useTable('test')->delete([]);
    }

    public function testDelete()
    {
        $adapter = $this->adapter->useTable('test');
        $adapter->createTable([
            'name' => ['attributeType' => 'S', 'keyType' => 'HASH'],
            'date' => ['attributeType' => 'N', 'keyType' => 'RANGE'],
        ]);
        $key = ['name' => 'Guillermo', 'date' => time()];
        $adapter->insert(array_merge(['name' => 'Guillermo', 'date' => time(), 'lastName' => 'Fisher']));
        $this->assertTrue($adapter->delete($key));
        $this->assertCount(0, $adapter->findAll());
        $adapter->deleteTable();
    }

    public function testGetClient()
    {
        $this->assertInstanceOf(DynamoDbClient::class, $this->adapter->getClient());
    }

    protected function setUp(): void
    {
        $this->adapter = new DynamoDbAdapter();
    }

    protected function tearDown(): void
    {
        $tables = $this->adapter->listTables();
        foreach ($tables as $table) {
            $this->adapter->useTable($table)->deleteTable();
        }
    }
}
