<?php

require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Guillermoandrae\Db\DynamoDb\DynamoDbAdapter;

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

// create a table
try {
    $tableName = 'myTable';
    $keys = [
        'source' => [

        ],
        'createdAt' => [

        ]
    ];
    $this->useTable($tableName)->createTable($keys);
} catch(\Exception $ex) {
    die($ex->getMessage());
}
