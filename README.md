# PHP DynamoDB
[![Travis](https://img.shields.io/travis/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://travis-ci.org/guillermoandrae/php-dynamodb) [![Scrutinizer](https://img.shields.io/scrutinizer/g/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://scrutinizer-ci.com/g/guillermoandrae/php-dynamodb/) [![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://scrutinizer-ci.com/g/guillermoandrae/php-dynamodb/)
 [![@guillermoandrae on Twitter](http://img.shields.io/badge/twitter-%40guillermoandrae-blue.svg?style=flat-square)](https://twitter.com/guillermoandrae)

This project provides a PHP library that can be used to interact with [Amazon DynamoDB](https://aws.amazon.com/dynamodb/). It provides a layer of abstraction between your code and the [AWS SDK for PHP](https://github.com/aws/aws-sdk-php) with a specific focus on the functionality provided via the [`DynamoDbClient`](https://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.DynamoDb.DynamoDbClient.html) and other related classes. 

## Installation
The recommended way to install this library is through [Composer](https://getcomposer.org/):
```
composer install guillermoandrae/php-dynamodb
```

## Quick Examples
```php
<?php

require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Guillermoandrae\Db\DynamoDb\AttributeTypes;
use Guillermoandrae\Db\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\Db\DynamoDb\KeyTypes;

// create a new DynamoDB client
$dynamoDbClient = new DynamoDbClient([
    'region' => 'us-east-1',
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
```

## Running DynamoDB locally
To aid in your development, you can run the following command to run DynamoDB locally:
```
composer start-db
```

## Testing
Run the following command to make sure your code is appropriately styled:
```
composer check-style
```

Run the following command to check style, run tests, and generate a Clover report:
```
composer test
```

Run the following command to check style, run tests, and generate an HTML report (access the report at http://localhost:8080):
```
composer test-html
```
