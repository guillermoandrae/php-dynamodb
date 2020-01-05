<?php

require '../vendor/autoload.php';

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
            'type' => AttributeTypes::STRING,
            'keyType' => KeyTypes::HASH
        ],
        'date' => [
            'type' => AttributeTypes::NUMBER,
            'keyType' => KeyTypes::RANGE
        ],
    ];
    $this->useTable($tableName)->createTable($keys);

    // delete a table
    $this->useTable($tableName)->deleteTable();

} catch(\Exception $ex) {
    die($ex->getMessage());
}
