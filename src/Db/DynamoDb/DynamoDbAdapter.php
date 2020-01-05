<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Common\Collection;
use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\Db\AdapterInterface;
use Guillermoandrae\Db\DbException;
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
        RequestFactory::setMarshaler($marshaler);
    }

    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#createtable
     */
    public function createTable(array $data, string $tableName = ''): bool
    {
        try {
            if (empty($tableName)) {
                $tableName = $this->tableName;
            }
            $query = RequestFactory::factory('create-table', $tableName, $data)->get();
            $this->client->createTable($query);
            $this->client->waitUntil('TableExists', ['TableName' => $tableName]);
            return true;
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        } catch (InvalidArgumentException $ex) {
            throw new DbException('Bad key schema: ' . $ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deletetable
     */
    public function deleteTable(string $tableName = ''): bool
    {
        try {
            if (empty($tableName)) {
                $tableName = $this->tableName;
            }
            $query = RequestFactory::factory('delete-table', $tableName)->get();
            $this->client->deleteTable($query);
            $this->client->waitUntil('TableNotExists', ['TableName' => $tableName]);
            return true;
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#describetable
     */
    public function describeTable(string $tableName = ''): array
    {
        try {
            if (empty($tableName)) {
                $tableName = $this->tableName;
            }
            $query = RequestFactory::factory('describe-table', $tableName)->get();
            $result = $this->client->describeTable($query);
            return $result['Table'];
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#listtables
     */
    public function tableExists(string $tableName = ''): bool
    {
        if (empty($tableName)) {
            $tableName = $this->tableName;
        }
        $tables = $this->listTables();
        return in_array(
            strtolower($tableName),
            array_map('strtolower', $tables)
        );
    }

    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#listtables
     */
    public function listTables(): array
    {
        $query = RequestFactory::factory('list-tables')->get();
        $results = $this->client->listTables($query);
        return $results['TableNames'];
    }

    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#scan
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
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#scan
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
        return $this->findByPrimaryKey($id);
    }

    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#getitem
     */
    public function findByPrimaryKey($primaryKey): array
    {
        try {
            $query = RequestFactory::factory('get-item', $this->tableName, $primaryKey)->get();
            $results = $this->client->getItem($query);
            $item = [];
            if (is_array($results['Item'])) {
                $item = $this->marshaler->unmarshalItem($results['Item']);
            }
            return $item;
        } catch (DynamoDbException $ex) {
            throw new DbException($ex->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#putitem
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
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deleteitem
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
