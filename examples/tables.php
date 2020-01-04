<?php

require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Guillermoandrae\Db\DynamoDb\AttributeTypes;
use Guillermoandrae\Db\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\Db\DynamoDb\KeyTypes;

// create a new DynamoDB client
$dynamoDbClient = new DynamoDbClient([
    'region' => 'us-west-2',
    'version'  => 'latest',
    'endpoint' => 'http://localhost:8000',
    'credentials' => [
        'key' => 'not-a-real-key',
        'secret' => 'not-a-real-secret',
    ],
]);

// pass the client to the adapter
$adapter = new DynamoDbAdapter($dynamoDbClient);

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
