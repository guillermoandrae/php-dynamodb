<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Db\DynamoDb\AttributeTypes;
use Guillermoandrae\Db\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\Db\DynamoDb\KeyTypes;
use Guillermoandrae\Db\DynamoDb\LocalDynamoDbClient;

// create a new DynamoDB client
$dynamoDbClient = LocalDynamoDbClient::get();

// create a new Marshaler
$marshaler = new Marshaler();

// pass the client to the adapter
$adapter = new DynamoDbAdapter($dynamoDbClient, $marshaler);

try {
    $tableName = 'myTable';

    // create a table
    $keys = [
        'name' => [
            'attributeType' => AttributeTypes::STRING,
            'keyType' => KeyTypes::HASH
        ],
        'date' => [
            'attributeType' => AttributeTypes::NUMBER,
            'keyType' => KeyTypes::RANGE
        ],
    ];

    if ($adapter->useTable($tableName)->createTable($keys)) {
        printf("The '%s' table was successfully created!" . PHP_EOL, $tableName);
    }

    // list the created tables
    $tables = $adapter->listTables();
    foreach ($tables as $table) {
        printf("The '%s' table exists!" . PHP_EOL, $table);
    }

    // delete a table
    if ($adapter->useTable($tableName)->deleteTable()) {
        printf("The '%s' table was successfully deleted!" . PHP_EOL, $tableName);
    }

    // check for the existence of a table
    if (!$adapter->useTable($tableName)->tableExists()) {
        printf("The '%s' table no longer exists!" . PHP_EOL, $tableName);
    }

} catch(\Exception $ex) {
    die($ex->getMessage());
}
