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
The examples below are borrowed from [AWS' PHP and DynamoDB documentation](https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GettingStarted.PHP.html) to illustrate this library's relative ease of use:
```php
<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Guillermoandrae\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;

// create a new adapter
$adapter = new DynamoDbAdapter();

try {
    $tableName = 'myTable';

    // create a table
    $keys = [
        'year' => [AttributeTypes::NUMBER, KeyTypes::HASH],
        'title' => [AttributeTypes::STRING, KeyTypes::RANGE],
    ];
    $adapter->useTable($tableName)->createTable($keys);

    // add an item to the table
    $adapter->useTable($tableName)->insert([
        'year' => 2015,
        'title' => 'The Big New Movie',
        'info' => [
            'plot' => 'Nothing happens at all',
            'rating' => 0,
        ],
    ]);

    // fetch an item from the table
    $item = $adapter->useTable($tableName)->find([
        'year' => 2015,
        'title' => 'The Big New Movie'
    ]);

    printf('Added item: %s - %s' . PHP_EOL, $item['year'], $item['title']);

    // delete the table
    $adapter->useTable($tableName)->deleteTable();

} catch (\Exception $ex) {
    die($ex->getMessage());
}
```

To run the examples found in the `examples` directory, run a command similar to the following:
```shell script
php ./examples/tables.php
```

## Running DynamoDB locally
To aid in your development, you can run the following commands to manage DynamoDB locally:
```shell script
composer install-db # downloads and installs DynamoDB locally
composer start-db # starts DynamoDB locally
composer stop-db # stops DynamoDB locally
composer restart-db # calls stop-db then start-db
```

## Testing
Run the following command to make sure your code is appropriately styled:
```shell script
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
