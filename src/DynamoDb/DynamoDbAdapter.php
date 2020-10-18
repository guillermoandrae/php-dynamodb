<?php

namespace Guillermoandrae\DynamoDb;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\DynamoDb\Contract\DynamoDbAdapterInterface;
use Guillermoandrae\DynamoDb\Contract\DynamoDbClientAwareTrait;
use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;
use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
use Guillermoandrae\DynamoDb\Factory\OperationFactory;

/**
 * The DynamoDB adapter.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
final class DynamoDbAdapter implements DynamoDbAdapterInterface
{
    use DynamoDbClientAwareTrait;

    /**
     * @var string The table name.
     */
    private $tableName;

    /**
     * DynamoDBAdapter constructor.
     *
     * Registers the AWS DynamoDB client and Marshaler with this object. If no arguments are provided, we use the
     * respective factories to generate the necessary objects.
     *
     * @param DynamoDbClient|null $client OPTIONAL The DynamoDB client.
     * @param Marshaler|null $marshaler OPTIONAL The Marshaler.
     */
    public function __construct(?DynamoDbClient $client = null, ?Marshaler $marshaler = null)
    {
        if (empty($client)) {
            $client = DynamoDbClientFactory::factory();
        }
        $this->setClient($client);
        OperationFactory::setClient($client);

        if (empty($marshaler)) {
            $marshaler = MarshalerFactory::factory();
        }
        $this->setMarshaler($marshaler);
        OperationFactory::setMarshaler($marshaler);
    }

    public function createTable(array $data, ?string $tableName = '', ?array $options = []): bool
    {
        if (empty($tableName)) {
            $tableName = $this->tableName;
        }
        return OperationFactory::factory('create-table', $tableName, $data)->execute();
    }

    public function deleteTable(string $tableName = ''): bool
    {
        if (empty($tableName)) {
            $tableName = $this->tableName;
        }
        return OperationFactory::factory('delete-table', $tableName)->execute();
    }

    public function describeTable(string $tableName = ''): ?array
    {
        if (empty($tableName)) {
            $tableName = $this->tableName;
        }
        return OperationFactory::factory('describe-table', $tableName)->execute();
    }

    public function tableExists(string $tableName = ''): bool
    {
        if (empty($tableName)) {
            $tableName = $this->tableName;
        }
        $tables = $this->listTables();
        if (empty($tables)) {
            return false;
        }
        return in_array(
            strtolower($tableName),
            array_map('strtolower', $tables)
        );
    }

    public function listTables(): ?array
    {
        return OperationFactory::factory('list-tables')->execute();
    }

    public function useTable(string $tableName): DynamoDbAdapterInterface
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function findWhere(array $conditions, int $offset = 0, ?int $limit = null): CollectionInterface
    {
        return OperationFactory::factory('query', $this->tableName, $conditions)
            ->setOffset($offset)
            ->setLimit($limit)
            ->execute();
    }

    public function findAll(int $offset = 0, ?int $limit = null, ?array $conditions = []): CollectionInterface
    {
        return OperationFactory::factory('scan', $this->tableName, $conditions)
            ->setOffset($offset)
            ->setLimit($limit)
            ->execute();
    }

    public function find(array $primaryKey): array
    {
        foreach ($primaryKey as $key => $value) {
            if (is_array($value)) {
                return OperationFactory::factory('batch-get-item', $primaryKey)->execute();
            }
        }
        return OperationFactory::factory('get-item', $this->tableName, $primaryKey)->execute();
    }

    public function insert(array $data): bool
    {
        return OperationFactory::factory('put-item', $this->tableName, $data)->execute();
    }

    public function update(array $primaryKey, array $data): bool
    {
        return OperationFactory::factory('update-item', $this->tableName, $primaryKey, $data)->execute();
    }

    public function delete($primaryKey): bool
    {
        return OperationFactory::factory('delete-item', $this->tableName, $primaryKey)->execute();
    }
}
