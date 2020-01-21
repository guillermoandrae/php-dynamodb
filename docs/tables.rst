Working with Tables
**************************
The following table operations are supported by **php-dynamodb**.

Listing Tables
####################
To see a list of the tables that exist your database, use the ``listTables()`` method. It will return an indexed array that stores the names of the existing tables.

**Example**
::
    $tables = $adapter->listTables();
    foreach ($tables as $table) {
        echo $table . PHP_EOL;
    }

Creating Tables
####################
Simple table creation can be accomplished using the adapter's ``createTable()`` method. The method takes the table name as an optional parameter (you can, instead, use the ``useTable()`` method and pass it the table name) and an optional array that specifies the table's key schema.

**Example**
::
    $result = $adapter->useTable('myTable')->createTable();

More complex table creation can be accomplished using the ``CreateTableOperation`` class, an instance of which can be created using the OperationFactory.

**Example**
::
    use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
    use Guillermoandrae\DynamoDb\Constant\KeyTypes;
    use Guillermoandrae\DynamoDb\DynamoDbAdapter;

    $operation = new CreateTableOperation('myTable', [
        'name' => [AttributeTypes::STRING, KeyTypes::HASH],
        'year' => [AttributeTypes::NUMBER, KeyTypes::RANGE],
    ]);
    $operation->setReadCapacityUnits(10);
    $result = $operation->execute();

.. note::
    By default, php-dynamodb will use 5 read capacity units and 5 write capacity units when creating tables.

Deleting Tables
####################
To delete a table in your database, use the ``deleteTable()`` method. It will return a boolean value that indicates whether or not the deletion was successful.

**Example**
::
    $result = $adapter->deleteTable('myTable');
