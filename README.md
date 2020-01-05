# PHP DynamoDB
[![Travis](https://img.shields.io/travis/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://travis-ci.org/guillermoandrae/php-dynamodb) [![Scrutinizer](https://img.shields.io/scrutinizer/g/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://scrutinizer-ci.com/g/guillermoandrae/php-dynamodb/) [![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://scrutinizer-ci.com/g/guillermoandrae/php-dynamodb/)
 [![@guillermoandrae on Twitter](http://img.shields.io/badge/twitter-%40guillermoandrae-blue.svg?style=flat-square)](https://twitter.com/guillermoandrae)

This project provides a PHP library that can be used to interact with [Amazon DynamoDB](https://aws.amazon.com/dynamodb/). It provides a layer of abstraction between your code and the [`DynamoDbClient`](https://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.DynamoDb.DynamoDbClient.html) class (as well as other related classes) made available by the [AWS SDK for PHP](https://github.com/aws/aws-sdk-php). 

## Installation
The recommended way to install this library is through [Composer](https://getcomposer.org/):
```
composer install guillermoandrae/php-dynamodb
```

## Quick Examples
```php
<?php

require '../vendor/autoload.php';

use Guillermoandrae\Db\DynamoDb\AttributeTypes;
use Guillermoandrae\Db\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\Db\DynamoDb\KeyTypes;
use Guillermoandrae\Db\DynamoDb\LocalDynamoDbClient;

// create a local DynamoDB client
$dynamoDbClient = LocalDynamoDbClient::get();

// pass the client to the adapter
$adapter = new DynamoDbAdapter($dynamoDbClient);

try {
    $tableName = 'myTable';

    // create a table
    $keys = [
        'year' => [
            'type' => AttributeTypes::NUMBER,
            'keyType' => KeyTypes::HASH
        ],
        'title' => [
            'type' => AttributeTypes::STRING,
            'keyType' => KeyTypes::RANGE
        ],
    ];
    $this->useTable($tableName)->createTable($keys);

    // add an item to the table
    $this->useTable($tableName)->insert([
        'year' => 2015,
        'title' => 'The Big New Movie',
        'plot' => 'Nothing happens at all'
    ]);

    // fetch an item from the table
    $item = $this->useTable($tableName)->findById([
        'year' => 2015,
        'title' => 'The Big New Movie'
    ]);
    echo $item['title'];

    // delete the table
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
