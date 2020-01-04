<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Common\Collection;
use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\Db\AdapterInterface;
use Guillermoandrae\Db\DbException;
use Guillermoandrae\Repositories\RepositoryFactory;
use InvalidArgumentException;

final class DynamoDbAdapter implements AdapterInterface
{
    /**
     * @var DynamoDbClient The DynamoDb client.
     */
    private $client;

    /**
     * @var Marshaler The JSON Marshaler.
     */
    private $marshaler;

    /**
     * @var string The table name.
     */
    private $tableName;

    /**
     * Registers the client and marshaler with this object. Sets up the
     * Repository factory and passes the Marshaler over to the request factory
     * as well.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The JSON Marshaler.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler)
    {
        $this->setClient($client);
        $this->marshaler = $marshaler;
        RepositoryFactory::setNamespace('Guillermoandrae\Fisher\Repositories');
        RequestFactory::setMarshaler($marshaler);
    }

    /**
     * {@inheritDoc}
     */
    public function createTable(array $data): bool
    {
        try {
            $query = RequestFactory::factory('create-table', $this->tableName, $data)->get();
            $this->client->createTable($query);
            return true;
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        } catch (InvalidArgumentException $ex) {
            throw new DbException('Bad key schema: ' . $ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function deleteTable(): bool
    {
        try {
            $query = RequestFactory::factory('delete-table', $this->tableName)->get();
            $this->client->deleteTable($query);
            return true;
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function describeTable(): array
    {
        try {
            $query = RequestFactory::factory('describe-table', $this->tableName)->get();
            $result = $this->client->describeTable($query);
            return $result['Table'];
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

     /**
     * {@inheritDoc}
     */
    public function tableExists(): bool
    {
        $tables = $this->listTables();
        return in_array(
            strtolower($this->tableName),
            array_map('strtolower', $tables)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function listTables(): array
    {
        $query = RequestFactory::factory('list-tables')->get();
        $results = $this->client->listTables($query);
        return $results['TableNames'];
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(int $offset = 0, ?int $limit = null): CollectionInterface
    {
        try {
            $query = RequestFactory::factory('scan', $this->tableName)->get();
            $results = $this->client->scan($query);
            $rows = [];
            foreach ($results['Items'] as $item) {
                $rows[] = $this->marshaler->unmarshalItem($item);
            }
            $collection = Collection::make($rows);
            return $collection->limit($offset, $limit);
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findLatest(): array
    {
        try {
            $query = RequestFactory::factory('scan', $this->tableName)
                ->setLimit(1)
                ->get();
            $results = $this->client->scan($query);
            return $this->marshaler->unmarshalItem($results['Items'][0]);
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findById($id): array
    {
        try {
            $query = RequestFactory::factory('get-item', $this->tableName, $id)->get();
            $results = $this->client->getItem($query);
            return $this->marshaler->unmarshalItem($results['Item']);
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function insert(array $data): bool
    {
        try {
            $query = RequestFactory::factory('put-item', $this->tableName, $data)->get();
            $this->client->putItem($query);
            return true;
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id): bool
    {
        try {
            $query = RequestFactory::factory('delete-item', $this->tableName, $id)->get();
            $this->client->deleteItem($query);
            return true;
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function useTable(string $tableName): AdapterInterface
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setClient($client): AdapterInterface
    {
        $this->client = $client;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getClient(): DynamoDbClient
    {
        return $this->client;
    }
}
