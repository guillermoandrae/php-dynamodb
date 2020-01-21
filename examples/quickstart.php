<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\DynamoDbAdapter;

// create a new adapter
$adapter = new DynamoDbAdapter();

try {
    $tableName = 'myTable';

    // create a table
    $adapter->useTable($tableName)->createTable([
        'year' => [AttributeTypes::NUMBER, KeyTypes::HASH],
        'title' => [AttributeTypes::STRING, KeyTypes::RANGE],
    ]);

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

    print_r($item);

    // delete the table
    $adapter->useTable($tableName)->deleteTable();

} catch (\Exception $ex) {
    die($ex->getMessage() . PHP_EOL);
}
