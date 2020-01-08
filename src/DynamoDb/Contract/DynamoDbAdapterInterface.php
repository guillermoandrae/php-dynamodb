<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\DynamoDb\Exception;

interface DynamoDbAdapterInterface extends DynamoDbClientAwareInterface
{
    /**
     * Creates a table.
     *
     * @see CreateTableOperation
     * @param array $data The table options.
     * @param string $tableName OPTIONAL The name of the table to create.
     * @param array $options OPTIONAL The table options.
     * @return bool Whether or not the table creation was successful.
     * @throws Exception Thrown when an error occurs during creation.
     */
    public function createTable(array $data, ?string $tableName = '', ?array $options = []): bool;
    
    /**
     * Deletes a table.
     *
     * @param string $tableName OPTIONAL The name of the table to delete.
     * @return boolean Whether or not the table deletion was successful.
     * @throws Exception Thrown when an error occurs during deletion.
     *@see DeleteItemOperation
     */
    public function deleteTable(string $tableName = ''): bool;

    /**
     * Returns information about a table.
     *
     * @see DescribeTableOperation
     * @param string $tableName OPTIONAL The desired table name.
     * @return array The table data.
     * @throws Exception Thrown when an error occurs during the existence check.
     */
    public function describeTable(string $tableName = ''): ?array;

    /**
     * Determines whether or not a table exists.
     *
     * @param string $tableName OPTIONAL The desired table name.
     * @return boolean Whether or not the table exists.
     *@see ListTablesOperation
     */
    public function tableExists(string $tableName = ''): bool;

    /**
     * Returns an array of the existing tables.
     *
     * @return array An array of the existing tables.
     *@see ListTablesOperation
     */
    public function listTables(): ?array;

    /**
     * Specifies the table to be used during an operation.
     *
     * @param string $tableName The table name.
     * @return DynamoDbAdapterInterface An implementation of this interface.
     */
    public function useTable(string $tableName): DynamoDbAdapterInterface;

    /**
     * Retrieves all of the items in a database table that meet the provided conditions.
     *
     * When an offset and limit are provided, the desired slice is returned.
     *
     * @see QueryOperation
     * @param array $conditions The conditions.
     * @param integer $offset OPTIONAL The offset.
     * @param integer $limit OPTIONAL The limit.
     * @return CollectionInterface A collection of rows.
     * @throws Exception Thrown when a query error occurs.
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function findWhere(array $conditions, int $offset = 0, ?int $limit = null): CollectionInterface;

    /**
     * Retrieves all of the items in a database table.
     *
     * When an offset and limit are provided, the desired slice is returned.
     *
     * @see ScanOperation
     * @param integer $offset OPTIONAL The offset.
     * @param integer $limit OPTIONAL The limit.
     * @param array $conditions OPTIONAL The conditions.
     * @return CollectionInterface A collection of rows.
     * @throws Exception Thrown when a query error occurs.
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function findAll(int $offset = 0, ?int $limit = null, ?array $conditions = []): CollectionInterface;

    /**
     * Retrieves an item from a table by primary key.
     *
     * @see GetItemOperation
     * @param array $primaryKey The item primary key.
     * @return array The item.
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function find(array $primaryKey): array;

    /**
     * Inserts an item into a table.
     *
     * @see PutItemOperation
     * @param array $data The item data.
     * @return bool Whether or not the item creation was successful.
     * @throws Exception Thrown when an operation error occurs.
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function insert(array $data): bool;

    /**
     * Deletes an item from a table.
     *
     * @param array $primaryKey The item primary key.
     * @return bool Whether or not the item deletion was successful.
     * @throws Exception Thrown when an operation error occurs.
     * @see DeleteItemOperation
     * @see DynamoDbAdapterInterface::useTable()
     */
    public function delete(array $primaryKey): bool;
}
