<?php

namespace Guillermoandrae\DynamoDb;

use Guillermoandrae\Common\CollectionInterface;

interface DynamoDbAdapterInterface
{
    /**
     * Creates a database table.
     *
     * @param array $data The table options.
     * @param string $tableName OPTIONAL The name of the table to create.
     * @return bool Whether or not the table creation was successful.
     * @throws Exception Thrown when an error occurs during creation.
     */
    public function createTable(array $data, string $tableName = ''): bool;
    
    /**
     * Deletes a table from the database.
     *
     * @param string $tableName OPTIONAL The name of the table to delete.
     * @return boolean Whether or not the table deletion was successful.
     * @throws Exception Thrown when an error occurs during deletion.
     */
    public function deleteTable(string $tableName = ''): bool;

    /**
     * Returns information about a database table.
     *
     * @param string $tableName OPTIONAL The desired table name.
     * @return array The table data.
     * @throws Exception Thrown when an error occurs during the existence check.
     */
    public function describeTable(string $tableName = ''): ?array;

    /**
     * Determines whether or not a table exists in the database.
     *
     * @param string $tableName OPTIONAL The desired table name.
     * @return boolean Whether or not the table exists.
     */
    public function tableExists(string $tableName = ''): bool;

    /**
     * Returns an array of database tables.
     *
     * @return array The database tables.
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
     * Retrieves all of the rows in a database table that meet the provided conditions. When an offset and limit
     * are provided, the desired slice is returned.
     *
     * @param array $conditions The conditions.
     * @param integer $offset OPTIONAL The offset.
     * @param integer $limit OPTIONAL The limit.
     * @return CollectionInterface A collection of rows.
     * @throws Exception Thrown when a query error occurs.
     *@see DynamoDbAdapterInterface::useTable()
     */
    public function findWhere(array $conditions, int $offset = 0, ?int $limit = null): CollectionInterface;

    /**
     * Retrieves all of the rows in a database table. When an offset and limit
     * are provided, the desired slice is returned.
     *
     * @param integer $offset OPTIONAL The offset.
     * @param integer $limit OPTIONAL The limit.
     * @return CollectionInterface A collection of rows.
     * @throws Exception Thrown when a query error occurs.
     *@see DynamoDbAdapterInterface::useTable()
     */
    public function findAll(int $offset = 0, ?int $limit = null): CollectionInterface;

    /**
     * Retrieves the newest record from a database table.
     *
     * @return array The latest record.
     * @throws Exception Thrown when a query error occurs.
     *@see DynamoDbAdapterInterface::useTable()
     */
    public function findLatest(): ?array;

    /**
     * Alias for AdapterInterface::findByPrimaryKey().
     *
     * @param mixed $id The record ID.
     * @return array The record.
     * @see DynamoDbAdapterInterface::useTable()
     * @see DynamoDbAdapterInterface::findByPrimaryKey()
     */
    public function findById($id): array;

    /**
     * Retrieves a record from a database table by primary key.
     *
     * @param mixed $primaryKey The record primary key.
     * @return array The record.
     *@see DynamoDbAdapterInterface::useTable()
     */
    public function findByPrimaryKey($primaryKey): array;

    /**
     * Inserts a record into a database table.
     *
     * @param array $data The record data.
     * @return bool Whether or not the record creation was successful.
     * @throws Exception Thrown when a query error occurs.
     *@see DynamoDbAdapterInterface::useTable()
     */
    public function insert(array $data): bool;

    /**
     * Deletes a record from a database table.
     *
     * @param mixed $id The record ID.
     * @return bool Whether or not the record deletion was successful.
     * @throws Exception Thrown when a query error occurs.
     *@see DynamoDbAdapterInterface::useTable()
     */
    public function delete($id): bool;

    /**
     * Registers the client.
     *
     * @param mixed $client The client.
     * @return DynamoDbAdapterInterface An implementation of this interface.
     */
    public function setClient($client): DynamoDbAdapterInterface;

    /**
     * Returns the client.
     *
     * @return mixed The client.
     */
    public function getClient();
}
