# PHP DynamoDB
[![Travis](https://img.shields.io/travis/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://travis-ci.org/guillermoandrae/php-dynamodb) [![Packagist](https://img.shields.io/packagist/php-v/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://packagist.org/packages/guillermoandrae/php-dynamodb) [![Scrutinizer](https://img.shields.io/scrutinizer/g/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://scrutinizer-ci.com/g/guillermoandrae/php-dynamodb/) [![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/guillermoandrae/php-dynamodb.svg?style=flat-square)](https://scrutinizer-ci.com/g/guillermoandrae/php-dynamodb/)
 [![@guillermoandrae on Twitter](http://img.shields.io/badge/twitter-%40guillermoandrae-blue.svg?style=flat-square)](https://twitter.com/guillermoandrae)

This project provides a PHP library that can be used to interact with [Amazon DynamoDB](https://aws.amazon.com/dynamodb/). It provides a layer of abstraction between your code and the DynamoDB-related classes made available by the [AWS SDK for PHP](https://github.com/aws/aws-sdk-php). 

## Installation
The recommended way to install this library is through [Composer](https://getcomposer.org/):
```
composer install guillermoandrae/php-dynamodb
```

## Getting Started
To get started using this library, you can perform operations such as this one:
```php
<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Guillermoandrae\DynamoDb\DynamoDbAdapter;
use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;

try {
    // create a new adapter
    $adapter = new DynamoDbAdapter();

    // create a table
    $keys = ;
    $adapter->useTable('myTable')->createTable([
        'year' => [AttributeTypes::NUMBER, KeyTypes::HASH],
        'title' => [AttributeTypes::STRING, KeyTypes::RANGE],
    ]);

} catch (\Exception $ex) {
    die($ex->getMessage());
}
```

You can also run the examples found in the `examples` directory:
```shell script
php ./examples/tables.php
```

## Documentation
More documentation can be found at [https://php-dynamodb.readthedocs.org](https://php-dynamodb.readthedocs.org).
