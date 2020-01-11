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
            $this->setClient($client);
            OperationFactory::setClient($client);
        }

        if (empty($marshaler)) {
            $marshaler = MarshalerFactory::factory();
            $this->setMarshaler($marshaler);
            OperationFactory::setMarshaler($marshaler);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function createTable(array $data, ?string $tableName = '', ?array $options = []): bool
    {
        if (empty($tableName)) {
            $tableName = $this->tableName;
        }
        return OperationFactory::factory('create-table', $tableName, $data)->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function deleteTable(string $tableName = ''): bool
    {
        if (empty($tableName)) {
            $tableName = $this->tableName;
        }
        return OperationFactory::factory('delete-table', $tableName)->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function describeTable(string $tableName = ''): ?array
    {
        if (empty($tableName)) {
            $tableName = $this->tableName;
        }
        return OperationFactory::factory('describe-table', $tableName)->execute();
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function listTables(): ?array
    {
        return OperationFactory::factory('list-tables')->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function useTable(string $tableName): DynamoDbAdapterInterface
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function findWhere(array $conditions, int $offset = 0, ?int $limit = null): CollectionInterface
    {
        return OperationFactory::factory('query', $this->tableName, $conditions)->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(int $offset = 0, ?int $limit = null, ?array $conditions = []): CollectionInterface
    {
        return OperationFactory::factory('scan', $this->tableName, $conditions)->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function find($primaryKey): array
    {
        return OperationFactory::factory('get-item', $this->tableName, $primaryKey)->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insert(array $data): bool
    {
        return OperationFactory::factory('put-item', $this->tableName, $data)->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function update(array $primaryKey, array $data): bool
    {
        return OperationFactory::factory('update-item', $this->tableName, $primaryKey, $data)->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id): bool
    {
        return OperationFactory::factory('delete-item', $this->tableName, $id)->execute();
    }
}
